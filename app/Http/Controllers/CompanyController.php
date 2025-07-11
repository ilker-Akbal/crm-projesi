<?php

namespace App\Http\Controllers;

use App\Models\Company;   // <-- Modeliniz varsa
use App\Models\Customer;  // create() sayfası için

class CompanyController extends Controller
{
    /** /companies  --------------------------------------------------*/
    public function index()
    {
        // Henüz veri eklemediyseniz collect() kullanılabilir
        $companies = Company::with('customer')->get();   // yoksa: $companies = collect();

        return view('companies.index', compact('companies'));
    }

    /** /companies/create  ------------------------------------------*/
    public function create()
    {
        // Formdaki “Bağlı Müşteri” listesi için; ihtiyacınız yoksa silin
        $customers = Customer::orderBy('customer_name')->get();

        return view('companies.create', compact('customers'));
    }
}
