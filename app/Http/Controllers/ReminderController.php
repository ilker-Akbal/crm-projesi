<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Str;

class ReminderController extends Controller
{
    /** GET /reminders */
    public function index()
{
    $reminders = Reminder::with(['customer', 'user'])
                 ->latest('reminder_date')
                 ->get();

    return view('reminders.index', compact('reminders'));
}


    /** GET /reminders/create */
    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        $users     = User::orderBy('username')->get();

        return view('reminders.create', compact('customers','users'));
    }

    /** POST /reminders */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'reminder_date' => 'required|date',
            'customer_id'   => 'required|exists:customers,id',
            'user_id'       => 'required|exists:users,id',
            'explanation'   => 'nullable|string',
        ]);

        Reminder::create($data);

        return redirect()
            ->route('reminders.index')
            ->with('success', 'Reminder created successfully.');
    }

    /** GET /reminders/{reminder} */
    public function show(Reminder $reminder)
    {
        $reminder->load(['customer','user']);
        return view('reminders.show', compact('reminder'));
    }

    /** GET /reminders/{reminder}/edit */
    public function edit(Reminder $reminder)
    {
        $customers = Customer::orderBy('customer_name')->get();
        $users     = User::orderBy('username')->get();

        return view('reminders.edit', compact('reminder','customers','users'));
    }

    /** PUT /reminders/{reminder} */
    public function update(Request $request, Reminder $reminder)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'reminder_date' => 'required|date',
            'customer_id'   => 'required|exists:customers,id',
            'user_id'       => 'required|exists:users,id',
            'explanation'   => 'nullable|string',
        ]);

        $reminder->update($data);

        return redirect()
            ->route('reminders.index')
            ->with('success', 'Reminder updated successfully.');
    }

    /** DELETE /reminders/{reminder} */
    public function destroy(Reminder $reminder)
    {
        $reminder->delete();

        return redirect()
            ->route('reminders.index')
            ->with('success', 'Reminder deleted successfully.');
    }
}
