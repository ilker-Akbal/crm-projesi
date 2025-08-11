<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    // Uygulama çapında sabit roller
    protected $roles = ['admin', 'manager', 'user'];

    /** GET /users */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /** GET /users/create */
    public function create()
    {
        $roles     = $this->roles;
        $customers = Customer::orderBy('customer_name')->get();
        return view('users.create', compact('roles', 'customers'));
    }

    /** POST /users */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username'    => 'required|string|max:255|unique:users,username',
            'role'        => 'required|in:' . implode(',', $this->roles),
            'customer_id' => 'required|exists:customers,id',
            'active'      => 'nullable|boolean',
            'password'    => 'nullable|string|min:8',
        ], [
            'username.required'    => 'Kullanıcı adı girilmesi zorunludur.',
            'username.unique'      => 'Bu kullanıcı adı zaten kullanımda.',
            'role.required'        => 'Rol seçilmelidir.',
            'role.in'              => 'Geçersiz bir rol seçildi.',
            'customer_id.required' => 'Bağlı müşteri seçilmelidir.',
            'customer_id.exists'   => 'Seçilen müşteri sistemde bulunamadı.',
            'password.min'         => 'Parola en az 8 karakter olmalıdır.',
        ]);

        // Checkbox işaretli ise 1, değilse 0
        $data['active'] = $request->boolean('active');
        $data['password'] = $data['password'] ? bcrypt($data['password']) : null;

        User::create($data);

        // Dashboard KPI cache'ini temizle
        Cache::forget('admin:kpis:v3');

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /** GET /users/{user} */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /** GET /users/{user}/edit */
    public function edit(User $user)
    {
        $roles     = $this->roles;
        $customers = Customer::orderBy('customer_name')->get();
        return view('users.edit', compact('user', 'roles', 'customers'));
    }

    /** PUT /users/{user} */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username'    => 'required|string|max:255|unique:users,username,' . $user->id,
            'role'        => 'required|in:' . implode(',', $this->roles),
            'customer_id' => 'required|exists:customers,id',
            'active'      => 'nullable|boolean',
            'password'    => 'nullable|string|min:8',
        ], [
            'username.required'    => 'Kullanıcı adı girilmesi zorunludur.',
            'username.unique'      => 'Bu kullanıcı adı zaten kullanımda.',
            'role.required'        => 'Rol seçilmelidir.',
            'role.in'              => 'Geçersiz bir rol seçildi.',
            'customer_id.required' => 'Bağlı müşteri seçilmelidir.',
            'customer_id.exists'   => 'Seçilen müşteri sistemde bulunamadı.',
            'password.min'         => 'Parola en az 8 karakter olmalıdır.',
        ]);

        // Checkbox işaretli ise 1, değilse 0
        $data['active'] = $request->boolean('active');

        if ($data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        // Dashboard KPI cache'ini temizle
        Cache::forget('admin:kpis:v3');

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /** DELETE /users/{user} */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            $fallback = User::first()?->id ?? null;

            Customer::where('created_by', $user->id)
                ->update(['created_by' => $fallback]);
            Customer::where('updated_by', $user->id)
                ->update(['updated_by' => $fallback]);

            $user->delete();
        });

        // Dashboard KPI cache'ini temizle
        Cache::forget('admin:kpis:v3');

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /** GET /users/roles */
    public function roles()
    {
        $roles = $this->roles;
        return view('users.roles', compact('roles'));
    }
}
