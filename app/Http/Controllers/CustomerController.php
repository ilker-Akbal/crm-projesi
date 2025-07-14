<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;  // <-- bunu ekledik

class CustomerController extends Controller
{
    
    // Müşteri listesini gösterir
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    // Yeni müşteri ekleme formunu gösterir
    public function create()
    {
        return view('customers.create');
    }

    // Formdan gelen verileri doğrula ve kaydet
    public function store(Request $request)
{
    $data = $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_type' => 'required|in:customer,supplier,candidate',
        'phone'         => 'nullable|string|max:50',
        'email'         => 'nullable|email|max:255',
        'address'       => 'nullable|string',
    ]);

    // created_by/updated_by otomatik atanacak artık
    Customer::create($data);

    return redirect()->route('customers.index')
                     ->with('success','Customer created successfully.');
}


    // Belirli bir müşterinin detayını gösterir
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    // (Opsiyonel) Güncelleme metodunu da ekleyebilirsiniz:
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_type' => 'required|in:customer,supplier,candidate',
            'phone'         => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string',
        ]);

        // Sadece updated_by değiştiriyoruz
        $data['updated_by'] = Auth::id() ?? 1;

        $customer->update($data);

        return redirect()
            ->route('customers.index')
            ->with('success','Customer updated successfully.');
    }
    
}
