<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\User;
use App\Models\Account;          // ✅ hesabı oluşturmak/silmek için

class CustomerController extends Controller
{
    /* -------------------------------------------------
     | 1) GET /customers
     * ------------------------------------------------*/
    public function index()
    {
        // kullanıcı & hesap tek seferde yüklensin
        $customers = Customer::with(['user','account'])->get();
        return view('customers.index', compact('customers'));
    }

    /* -------------------------------------------------
     | 2) GET /customers/create
     * ------------------------------------------------*/
    public function create()
    {
        $roles = ['admin', 'manager', 'user'];
        return view('customers.create', compact('roles'));
    }

    /* -------------------------------------------------
     | 3) POST /customers
     * ------------------------------------------------*/
    public function store(Request $request)
    {
        // checkbox'ı bool yap
        $request->merge(['active' => $request->has('active') ? 1 : 0]);

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_type' => 'required|in:customer,supplier,candidate',
            'phone'         => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string',
            'username'      => 'required|string|unique:users,username',
            'password'      => 'required|string|min:8|confirmed',
            'active'        => 'boolean',
            'role'          => 'required|in:admin,manager,user',
        ], [
    'customer_name.required' => 'Müşteri adı zorunludur.',
    'customer_type.required' => 'Müşteri türü seçilmelidir.',
    'username.required'      => 'Kullanıcı adı zorunludur.',
    'username.unique'        => 'Bu kullanıcı adı zaten alınmış.',
    'password.required'      => 'Şifre girilmelidir.',
    'password.min'           => 'Şifre en az 8 karakter olmalıdır.',
    'password.confirmed'     => 'Şifreler uyuşmuyor.',
    'role.required'          => 'Rol seçilmelidir.',
]);

        DB::transaction(function () use ($data, $request) {

            /* --- 1) Customer --- */
            $customer = Customer::create([
                'customer_name' => $data['customer_name'],
                'customer_type' => $data['customer_type'],
                'phone'         => $data['phone']    ?? null,
                'email'         => $data['email']    ?? null,
                'address'       => $data['address']  ?? null,
                'created_by'    => Auth::id() ?? 1,
            ]);

            /* --- 2) User (müşteriye bağlı) --- */
            User::create([
                'username'    => $data['username'],
                'password'    => Hash::make($data['password']),
                'role'        => $data['role'],
                'active'      => $request->active,
                'customer_id' => $customer->id,
                'created_by'  => Auth::id() ?? 1,
            ]);

            /* --- 3) Tek hesap (Account) --- */
            Account::create([
                'customer_id' => $customer->id,
                'balance'     => 0,
                'opening_date'=> now(),
                'updated_by'  => Auth::id() ?? 1,
            ]);
        });

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Müşteri, kullanıcı ve hesabı oluşturuldu.');
    }

    /* -------------------------------------------------
     | 4) GET /customers/{customer}
     * ------------------------------------------------*/
    public function show(Customer $customer)
    {
        $customer->load(['user','account']);
        return view('customers.show', compact('customer'));
    }

    /* -------------------------------------------------
     | 5) GET /customers/{customer}/edit
     * ------------------------------------------------*/
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /* -------------------------------------------------
     | 6) PUT /customers/{customer}
     * ------------------------------------------------*/
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_type' => 'required|in:customer,supplier,candidate',
            'phone'         => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string',
        ], [
        'customer_name.required' => 'Müşteri adı zorunludur.',
        'customer_name.string'   => 'Müşteri adı metin olmalıdır.',
        'customer_name.max'      => 'Müşteri adı en fazla 255 karakter olabilir.',

        'customer_type.required' => 'Müşteri türü zorunludur.',
        'customer_type.in'       => 'Geçerli bir müşteri türü seçiniz.',

        'phone.string'           => 'Telefon metin formatında olmalıdır.',
        'phone.max'              => 'Telefon numarası en fazla 50 karakter olabilir.',

        'email.email'            => 'Geçerli bir e-posta adresi giriniz.',
        'email.max'              => 'E-posta en fazla 255 karakter olabilir.',

        'address.string'         => 'Adres metin formatında olmalıdır.',
    ]);

        $customer->update($data + ['updated_by' => Auth::id() ?? 1]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Müşteri bilgileri güncellendi.');
    }

    /* -------------------------------------------------
     | 7) DELETE /customers/{customer}
     * ------------------------------------------------*/
    public function destroy(Customer $customer)
    {
        DB::transaction(function () use ($customer) {

            // 1) bağlı kullanıcı
            $customer->user?->delete();

            // 2) bağlı tek hesap
            $customer->account?->delete();

            // 3) müşteri
            $customer->delete();
        });

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Müşteri, kullanıcı ve hesabı silindi.');
    }
}
