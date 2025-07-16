<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\User;

class CustomerController extends Controller
{
    // 1) GET /customers
    public function index()
    {
        $customers = Customer::with('user')->get();
        return view('customers.index', compact('customers'));
    }

    // 2) GET /customers/create
    public function create()
{
    // BOŞ ÖĞE YOK: Sadece bu üç string var
    $roles = ['admin', 'manager', 'user'];
    return view('customers.create', compact('roles'));
}

    // 3) POST /customers
    public function store(Request $request)
    {
        // checkbox'ı boolean 1/0 olarak işleyelim
        $request->merge(['active' => $request->has('active') ? 1 : 0]);

        $data = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_type'    => 'required|in:customer,supplier,candidate',
            'phone'            => 'nullable|string|max:50',
            'email'            => 'nullable|email|max:255',
            'address'          => 'nullable|string',
            'username'         => 'required|string|unique:users,username',
            'password'         => 'required|string|min:8|confirmed',
            'active'           => 'boolean',
            'role'             => 'required|in:'.implode(',', ['admin','manager','user']),
        ]);

        DB::transaction(function () use ($data, $request) {
            $customer = Customer::create([
                'customer_name' => $data['customer_name'],
                'customer_type' => $data['customer_type'],
                'phone'         => $data['phone'] ?? null,
                'email'         => $data['email'] ?? null,
                'address'       => $data['address'] ?? null,
                'created_by'    => Auth::id() ?? 1,
            ]);

            User::create([
                'username'    => $data['username'],
                'password'    => Hash::make($data['password']),
                'role'        => $data['role'],
                'active'      => $request->has('active'),
                'customer_id' => $customer->id,
                'created_by'  => Auth::id() ?? 1,
            ]);
        });

        return redirect()->route('customers.index')
                         ->with('success','Müşteri ve kullanıcı başarıyla oluşturuldu.');
    }

    // 4) GET /customers/{customer}
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    // 5) GET /customers/{customer}/edit
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // 6) PUT/PATCH /customers/{customer}
    public function update(Request $request, Customer $customer)
    {
        $request->merge(['active' => $request->has('active') ? 1 : 0]);

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_type' => 'required|in:customer,supplier,candidate',
            'phone'         => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string',
            'username'      => 'required|string|unique:users,username,' . $customer->user->id,
            'password'      => 'nullable|string|min:8|confirmed',
            'active'        => 'boolean',
        ]);

        DB::transaction(function () use ($data, $request, $customer) {
            // 1️⃣ müşteri güncelle
            $customer->update([
                'customer_name' => $data['customer_name'],
                'customer_type' => $data['customer_type'],
                'phone'         => $data['phone'] ?? null,
                'email'         => $data['email'] ?? null,
                'address'       => $data['address'] ?? null,
                'updated_by'    => Auth::id() ?? 1,
            ]);

            // 2️⃣ ilişkili kullanıcı güncelle
            $user = $customer->user;
            $user->username = $data['username'];
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->active = $request->has('active');
            $user->updated_by = Auth::id() ?? 1;
            $user->save();
        });

        return redirect()->route('customers.index')
                         ->with('success','Müşteri ve kullanıcı başarıyla güncellendi.');
    }

    // 7) DELETE /customers/{customer}
    public function destroy(Customer $customer)
    {
        DB::transaction(function () use ($customer) {
            // önce kullanıcıyı sil (isteğe bağlı)
            if ($customer->user) {
                $customer->user->delete();
            }
            $customer->delete();
        });

        return redirect()->route('customers.index')
                         ->with('success','Müşteri ve kullanıcı başarıyla silindi.');
    }
}
