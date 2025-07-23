<?php
// app/Http/Controllers/CompanyController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Customer;

class CompanyController extends Controller
{
    /* -------------------------------------------------
     |  Liste – sadece kendi müşteri kayıtları
     * ------------------------------------------------*/
    public function index()
    {
        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->with('customer')
                            ->get();

        return view('companies.index', compact('companies'));
    }

    /* -------------------------------------------------
     |  Yeni – sadece kendi müşterisi (isterseniz dropdown’ı
     |  tamamen kaldırabilirsiniz)
     * ------------------------------------------------*/
    public function create()
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        return view('companies.create', compact('customers'));
    }

    /* -------------------------------------------------
     |  Kaydet
     * ------------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
    'company_name'      => 'required|string|max:255',
    'tax_number'        => 'required|digits:11|unique:companies,tax_number',
    'phone_number'      => 'nullable|digits:11|unique:companies,phone_number',
    'email'             => 'nullable|email|max:255',
    'address'           => 'nullable|string|max:500',
    'registration_date' => 'nullable|date',
    'current_role'      => 'required|in:customer,supplier,candidate',
]);

        $data['customer_id'] = Auth::user()->customer_id;

        Company::create($data);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Firma başarıyla oluşturuldu.');
    }

    /* -------------------------------------------------
     |  Detay
     * ------------------------------------------------*/
    public function show(Company $company)
    {
        $company->load('customer', 'contacts');

        return view('companies.show', compact('company'));
    }

    /* -------------------------------------------------
     |  Düzenle
     * ------------------------------------------------*/
    public function edit(Company $company)
    {
        // EnsureCompanyOwner middleware’i erişim kontrolü yapıyor
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        return view('companies.edit', compact('company', 'customers'));
    }

    /* -------------------------------------------------
     |  Güncelle
     * ------------------------------------------------*/
    public function update(Request $request, Company $company)
    {
    $validated = $request->validate([
        'company_name'     => 'required|string|max:255',
        'tax_number'       => 'required|digits:11|unique:companies,tax_number,' . $company->id,
        'phone_number'     => 'required|digits:11|unique:companies,phone_number,' . $company->id,
        'email'            => 'nullable|email',
        'address'          => 'nullable|string',
        'registration_date'=> 'nullable|date',
        'current_role'     => 'required|in:customer,supplier,candidate',
    ]);

    $company->update($validated);

    return redirect()->route('companies.index')
                     ->with('success', 'Firma başarıyla güncellendi.');
}

    /* -------------------------------------------------
     |  Sil
     * ------------------------------------------------*/
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Firma başarıyla silindi.');
    }
}
