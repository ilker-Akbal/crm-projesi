<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Company;
use App\Models\Customer;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->with('customer')
                            ->get();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        return view('companies.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name'      => [
                'required', 'string', 'max:255',
                Rule::unique('companies', 'company_name')
                    ->where('customer_id', Auth::user()->customer_id),
            ],
            'tax_number'        => 'required|digits:11|unique:companies,tax_number',
            'phone_number'      => 'nullable|digits:11|unique:companies,phone_number',
            'email'             => 'nullable|email|max:255',
            'address'           => 'nullable|string|max:500',
            'registration_date' => 'nullable|date',
            'foundation_date'   => 'nullable|date', // eklendi
            'current_role'      => 'required|in:customer,supplier,candidate',
        ]);

        // customer_id ve foundation_date dahil ediliyor
        $data['customer_id'] = Auth::user()->customer_id;
        $data['foundation_date'] = $request->foundation_date;

        Company::create($data);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Firma başarıyla oluşturuldu.');
    }

    public function show(Company $company)
    {
        $company->load('customer', 'contacts');
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        return view('companies.edit', compact('company', 'customers'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'company_name'      => [
                'required', 'string', 'max:255',
                Rule::unique('companies', 'company_name')
                    ->ignore($company->id)
                    ->where('customer_id', Auth::user()->customer_id),
            ],
            'tax_number'        => 'required|digits:11|unique:companies,tax_number,' . $company->id,
            'phone_number'      => 'required|digits:11|unique:companies,phone_number,' . $company->id,
            'email'             => 'nullable|email',
            'address'           => 'nullable|string',
            'registration_date' => 'nullable|date',
            'foundation_date'   => 'nullable|date', // eklendi
            'current_role'      => 'required|in:customer,supplier,candidate',
        ]);

        // foundation_date kaydı ekleniyor
        $validated['foundation_date'] = $request->foundation_date;

        $company->update($validated);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Firma başarıyla güncellendi.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()
            ->route('companies.index')
            ->with('success', 'Firma başarıyla silindi.');
    }
}
