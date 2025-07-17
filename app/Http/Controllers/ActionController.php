<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Action;
use App\Models\Customer;
use App\Models\User;

class ActionController extends Controller
{
    /** GET /actions */
    public function index()
    {
        $actions = Action::with(['customer', 'user'])
            ->orderBy('action_date', 'desc')
            ->get();

        return view('actions.index', compact('actions'));
    }

    /** GET /actions/create */
    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        $users     = User::orderBy('username')->get();

        return view('actions.create', compact('customers', 'users'));
    }

    /** POST /actions */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'user_id'      => 'required|exists:users,id',
            'action_type'  => 'required|string|max:255',
            'action_date'  => 'required|date',
            'updated_by'   => 'nullable|exists:users,id',
        ]);

        Action::create($data);

        return redirect()
            ->route('actions.index')
            ->with('success', 'Action recorded successfully.');
    }

    /** GET /actions/{action} */
    public function show(Action $action)
    {
        $action->load(['customer','user']);
        return view('actions.show', compact('action'));
    }

    /** GET /actions/{action}/edit */
    public function edit(Action $action)
    {
        $customers = Customer::orderBy('customer_name')->get();
        $users     = User::orderBy('username')->get();

        return view('actions.edit', compact('action','customers','users'));
    }

    /** PUT /actions/{action} */
    public function update(Request $request, Action $action)
    {
        $data = $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'user_id'      => 'required|exists:users,id',
            'action_type'  => 'required|string|max:255',
            'action_date'  => 'required|date',
            'updated_by'   => 'nullable|exists:users,id',
        ]);

        $action->update($data);

        return redirect()
            ->route('actions.index')
            ->with('success', 'Action updated successfully.');
    }

    /** DELETE /actions/{action} */
    public function destroy(Action $action)
    {
        $action->delete();

        return redirect()
            ->route('actions.index')
            ->with('success', 'Action deleted successfully.');
    }

    /** GET /actions/by-customer */
    public function byCustomer(Request $request)
    {
        $customers = Customer::orderBy('customer_name')->get();
        $selected  = $request->input('customer_id');
        $query     = Action::with(['customer','user'])->orderBy('action_date','desc');

        if ($selected) {
            $query->where('customer_id', $selected);
        }

        $actions = $query->get();

        return view('actions.by-customer', compact('actions','customers','selected'));
    }
}
