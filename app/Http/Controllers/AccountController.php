<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Customer;

class AccountController extends Controller
{
    // GET /accounts
    public function index()
    {
        $accounts = Account::with('customer')
            ->orderBy('opening_date','desc')
            ->get();

        return view('accounts.index', compact('accounts'));
    }

    // GET /accounts/create
    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('accounts.create', compact('customers'));
    }

    // POST /accounts
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'balance'      => 'required|numeric|min:0',
            'opening_date' => 'required|date',
        ]);

        Account::create($data);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    // GET /accounts/{account}
    public function show(Account $account)
    {
        $account->load('customer');
        return view('accounts.show', compact('account'));
    }

    // GET /accounts/{account}/edit
    public function edit(Account $account)
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('accounts.edit', compact('account','customers'));
    }

    // PUT /accounts/{account}
    public function update(Request $request, Account $account)
    {
        $data = $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'balance'      => 'required|numeric|min:0',
            'opening_date' => 'required|date',
        ]);

        $account->update($data);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    // DELETE /accounts/{account}
    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}
