<?php
// app/Http/Controllers/CompanyController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class CompanyController extends Controller
{
    // Liste
    public function index()
    {
        $companies = Company::with('customer')->get();
        return view('companies.index', compact('companies'));
    }

    // Yeni
    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('companies.create', compact('customers'));
    }

    // Kaydet
   public function store(Request $request)
{
    $data = $request->validate([
        'company_name'      => 'required|string|max:255',
        'tax_number'        => 'nullable|string|max:100',
        'address'           => 'nullable|string',
        'phone_number'      => 'nullable|string|max:50',
        'email'             => 'nullable|email|max:255',
        'registration_date' => 'nullable|date',
        'current_role'      => 'required|in:customer,supplier,candidate',
        'customer_id'       => 'nullable|exists:customers,id',
    ]);

    Company::create($data);

    return redirect()
        ->route('companies.index')
        ->with('success', 'Firma başarıyla oluşturuldu.');
}

    // Detay
    public function show(Company $company)
    {
        $company->load('customer','contacts');
        return view('companies.show', compact('company'));
    }

    // Düzenle
    public function edit(Company $company)
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('companies.edit', compact('company','customers'));
    }

    // Güncelle
    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
        'company_name'      => 'required|string|max:255',
        'tax_number'        => 'nullable|string|max:100',
        'address'           => 'nullable|string',
        'phone_number'      => 'nullable|string|max:50',
        'email'             => 'nullable|email|max:255',
        'registration_date' => 'nullable|date',
        'current_role'      => 'nullable|string|max:100',
        'customer_id'       => 'nullable|exists:customers,id',  // ✔ kural
    ]);

        $data['customer_id'] = Auth::user()->customer_id;

    $company->update($data);

    return redirect()
        ->route('companies.index')
        ->with('success', 'Firma başarıyla güncellendi.');
}

    // Sil
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}
