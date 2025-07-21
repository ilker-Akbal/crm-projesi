<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Customer;
use App\Models\CurrentMovement;
class AccountController extends Controller
{
    /* -------------------------------------------------
     |  GET /accounts  →  Liste
     * ------------------------------------------------*/
    public function index()
    {
        $accounts = Account::where('customer_id', Auth::user()->customer_id)
                           ->with('customer')
                           ->orderBy('opening_date', 'desc')
                           ->get();

        return view('accounts.index', compact('accounts'));
    }

    /* -------------------------------------------------
     |  GET /accounts/create  →  Form
     * ------------------------------------------------*/
    public function create()
    {
        // Başka müşteri seçimi yapılmasın
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        return view('accounts.create', compact('customers'));
    }

    /* -------------------------------------------------
     |  POST /accounts  →  Kaydet
     * ------------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'balance'      => 'required|numeric|min:0',
            'opening_date' => 'required|date',
        ]);

        Account::create($data + [
            'customer_id' => Auth::user()->customer_id,
        ]);

        return redirect()->route('accounts.index')
                         ->with('success', 'Account created successfully.');
    }

    /* -------------------------------------------------
     |  GET /accounts/{account}  →  Detay
     * ------------------------------------------------*/
    public function show(Account $account)
{
    $this->authorizeAccount($account);

    
    // müşteri + hareketleri tek sorguda getir
    $account->load(['customer', 'movements']);

    // hareketlerin neti (credit - debit) — modelde hazır
    $net       = $account->computed_balance;

    // Açılış = mevcut - hareketlerin neti
    $opening   = round($account->balance - $net, 2);

    // Kapanış = cari tablo bakiyesi
    $closing   = round($account->balance, 2);

    return view('accounts.show', compact('account', 'opening', 'closing'));
}


    /* -------------------------------------------------
     |  GET /accounts/{account}/edit  →  Form
     * ------------------------------------------------*/
    public function edit(Account $account)
    {
        $this->authorizeAccount($account);

        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        return view('accounts.edit', compact('account', 'customers'));
    }

    /* -------------------------------------------------
     |  PUT /accounts/{account}  →  Güncelle
     * ------------------------------------------------*/
    public function update(Request $request, Account $account)
    {
        $this->authorizeAccount($account);

        $data = $request->validate([
            'balance'      => 'required|numeric|min:0',
            'opening_date' => 'required|date',
        ]);

        $account->update($data);

        return redirect()->route('accounts.index')
                         ->with('success', 'Account updated successfully.');
    }

    /* -------------------------------------------------
     |  DELETE /accounts/{account}  →  Sil
     * ------------------------------------------------*/
    public function destroy(Account $account)
    {
        $this->authorizeAccount($account);

        $account->delete();

        return redirect()->route('accounts.index')
                         ->with('success', 'Account deleted successfully.');
    }

    /* -------------------------------------------------
     |  Yardımcı: hesap sahibi mi?
     * ------------------------------------------------*/
    private function authorizeAccount(Account $account): void
    {
        if ($account->customer_id !== Auth::user()->customer_id) {
            abort(403, 'Bu hesaba erişim yetkiniz yok.');
        }
    }
}
