<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        $roles = $this->roles;
        return view('users.create', compact('roles'));
    }

    /** POST /users */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'role'     => 'required|in:' . implode(',', $this->roles),
            'active'   => 'nullable|boolean',
        ]);

        // checkbox'tan gelmezse aktif kabul edelim
        $data['active'] = $request->has('active');

        User::create($data);

        return redirect()
            ->route('users.index')
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
        $roles = $this->roles;
        return view('users.edit', compact('user','roles'));
    }

    /** PUT /users/{user} */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role'     => 'required|in:' . implode(',', $this->roles),
            'active'   => 'nullable|boolean',
        ]);
        $data['active'] = $request->has('active');

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /** DELETE /users/{user} */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /** GET /users/roles */
    public function roles()
    {
        $roles = $this->roles;
        return view('users.roles', compact('roles'));
    }
}
