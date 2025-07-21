<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Reminder;
use App\Models\Customer;
use App\Models\User;

class ReminderController extends Controller
{
    /* -------------------------------------------------
     |  GET /reminders → Liste
     * ------------------------------------------------*/
    public function index()
    {
        $reminders = Reminder::where('customer_id', Auth::user()->customer_id)
                             ->with(['customer', 'user'])
                             ->latest('reminder_date')
                             ->get();

        return view('reminders.index', compact('reminders'));
    }

    /* -------------------------------------------------
     |  GET /reminders/create → Form
     * ------------------------------------------------*/
    public function create()
    {
        // Başka müşteri seçilmesin
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        // Kullanıcı listesi – tüm aktif kullanıcılar veya dilerseniz
        // aynı müşteri içindeki kullanıcılar filtrelenebilir
        $users = User::orderBy('username')->get();

        return view('reminders.create', compact('customers', 'users'));
    }

    /* -------------------------------------------------
     |  POST /reminders → Kaydet
     * ------------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'reminder_date' => 'required|date',
            'explanation'   => 'nullable|string',
        ]);

        Reminder::create($data + [
            'user_id'     => Auth::id(),
            'customer_id' => Auth::user()->customer_id,
        ]);

        return redirect()->route('reminders.index')
                         ->with('success', 'Reminder created successfully.');
    }

    /* -------------------------------------------------
     |  GET /reminders/{reminder} → Detay
     * ------------------------------------------------*/
    public function show(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        $reminder->load(['customer', 'user']);

        return view('reminders.show', compact('reminder'));
    }

    /* -------------------------------------------------
     |  GET /reminders/{reminder}/edit → Form
     * ------------------------------------------------*/
    public function edit(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        $users     = User::orderBy('username')->get();

        return view('reminders.edit', compact('reminder', 'customers', 'users'));
    }

    /* -------------------------------------------------
     |  PUT /reminders/{reminder} → Güncelle
     * ------------------------------------------------*/
    public function update(Request $request, Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'reminder_date' => 'required|date',
            'explanation'   => 'nullable|string',
        ]);

        $reminder->update($data);

        return redirect()->route('reminders.index')
                         ->with('success', 'Reminder updated successfully.');
    }

    /* -------------------------------------------------
     |  DELETE /reminders/{reminder} → Sil
     * ------------------------------------------------*/
    public function destroy(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        $reminder->delete();

        return redirect()->route('reminders.index')
                         ->with('success', 'Reminder deleted successfully.');
    }

    /* -------------------------------------------------
     |  Yardımcı: hatırlatma sahibi mi?
     * ------------------------------------------------*/
    private function authorizeReminder(Reminder $reminder): void
    {
        if ($reminder->customer_id !== Auth::user()->customer_id) {
            abort(403, 'Bu hatırlatmaya erişim yetkiniz yok.');
        }
    }
}
