<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Account;     // Gerçek veri geldiğinde açın
// use App\Models\Customer;

class AccountController extends Controller
{
    /** GET /accounts  */
    public function index()
    {
        // $accounts = Account::with('customer')->get();
        $accounts = collect();          // Şimdilik boş
        return view('accounts.index', compact('accounts'));
    }

    /** GET /accounts/create */
    public function create()
    {
        // $customers = Customer::orderBy('customer_name')->get();
        $customers = collect();         // Şimdilik boş
        return view('accounts.create', compact('customers'));
    }

    /** POST /accounts  – şimdilik sahte geri dönüş */
    public function store(Request $request)
    {
        // Validasyon + kayıt sonra eklenecek
        return back()->with('success', 'Demo: veri kaydedilmedi, sadece sayfa yüklendi.');
    }

    /* Diğer metodlar şimdilik boş */
    public function show($id)    {}
    public function edit($id)    {}
    public function update(Request $r, $id) {}
    public function destroy($id) {}
}
