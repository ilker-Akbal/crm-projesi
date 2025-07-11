<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActionController extends Controller
{
    /** GET /actions */
    public function index()
    {
        // $actions = Action::with(['customer','user'])->get();
        $actions = collect();          // şimdilik boş
        return view('actions.index', compact('actions'));
    }

    /** GET /actions/create */
    public function create()
    {
        return view('actions.create');
    }

    /** GET /actions/by-customer */
    public function byCustomer()
    {
        // $actions = Action::where('customer_id', ...)->get();
        $actions = collect();
        return view('actions.by_customer', compact('actions'));
    }

    /* CRUD metotları (boş bırakıldı) */
    public function store(Request $r) {}
    public function show(string $id)  {}
    public function edit(string $id)  {}
    public function update(Request $r, string $id) {}
    public function destroy(string $id) {}
}
