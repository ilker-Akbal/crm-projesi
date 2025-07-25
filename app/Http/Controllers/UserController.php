<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Roller: uygulama çapında sabit liste
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
    $customers = Customer::orderBy('customer_name')->get();   // ➊
    return view('users.create', compact('roles','customers'));
}

    /** POST /users */
    public function store(Request $request)
    {
        $data = $request->validate([
    'username'    => 'required|string|max:255|unique:users,username',
    'role'        => 'required|in:' . implode(',', $this->roles),
    'customer_id' => 'required|exists:customers,id',          // ➋
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

$data['active']   = $request->has('active');
$data['password'] = $data['password'] ? bcrypt($data['password']) : null;
User::create($data);

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
    $customers = Customer::orderBy('customer_name')->get();   // ➊
    return view('users.edit', compact('user','roles','customers'));
}

    /** PUT /users/{user} */
    public function update(Request $request, User $user)
    {
       $data = $request->validate([
    'username'    => 'required|string|max:255|unique:users,username,' . $user->id,
    'role'        => 'required|in:' . implode(',', $this->roles),
    'customer_id' => 'required|exists:customers,id',          // ➋
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

$data['active']   = $request->has('active');
if ($data['password']) $data['password'] = bcrypt($data['password']);
else unset($data['password']);

$user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /** DELETE /users/{user} */
    public function destroy(User $user)
{
    \DB::transaction(function() use ($user) {
        // varsayılan devretme kullanıcısı (ör: ilk kullanıcı)
        $fallback = User::first()?->id ?? null;

        // created_by/updated_by olan tüm müşteri kayıtlarını devret
        \App\Models\Customer::where('created_by', $user->id)
            ->update(['created_by' => $fallback]);

        \App\Models\Customer::where('updated_by', $user->id)
            ->update(['updated_by' => $fallback]);

        // (Eğer başka modellerde de benzer alanlar varsa, onları da ekleyin)

        // Son olarak kullanıcıyı sil
        $user->delete();
    });

    return redirect()
        ->route('admin.users.index')
        ->with('success','User deleted successfully.');
}

    /** GET /users/roles */
    public function roles()
    {
        $roles = $this->roles;
        return view('users.roles', compact('roles'));
    }
}
