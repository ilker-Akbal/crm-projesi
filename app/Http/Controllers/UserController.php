<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /** GET /users */
    public function index()
    {
        // $users = User::all();  // Gerçek model ekleyince açın
        $users = collect();      // şimdilik boş
        return view('users.index', compact('users'));
    }

    /** GET /users/create */
    public function create()
    {
        return view('users.create');
    }

    /** GET /users/roles */
    public function roles()
    {
        $roles = ['admin', 'manager', 'user']; // örnek roller
        return view('users.roles', compact('roles'));
    }

    /* Diğer CRUD metotlarını boş bırakıyoruz */
    public function store(Request $r) {}
    public function show(string $id)  {}
    public function edit(string $id)  {}
    public function update(Request $r, string $id) {}
    public function destroy(string $id) {}
}
