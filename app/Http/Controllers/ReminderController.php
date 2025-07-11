<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Reminder;  // Gerçek model ekleyince açın
// use App\Models\Customer;
// use App\Models\User;

class ReminderController extends Controller
{
    /** GET /reminders */
    public function index()
    {
        // $reminders = Reminder::with(['customer','user'])->get();
        $reminders = collect();   // şimdilik boş
        return view('reminders.index', compact('reminders'));
    }

    /** GET /reminders/create */
    public function create()
    {
        $customers = collect();   // Customer::all();
        $users     = collect();   // User::all();
        return view('reminders.create', compact('customers','users'));
    }

    /* CRUD metodları şimdilik boş */
    public function store(Request $r) {}           // POST /reminders
    public function show(string $id)  {}           // GET /reminders/{id}
    public function edit(string $id)  {}           // GET /reminders/{id}/edit
    public function update(Request $r, string $id) {}  // PUT/PATCH
    public function destroy(string $id) {}         // DELETE
}
