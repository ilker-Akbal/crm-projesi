<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Action;

class ActionController extends Controller
{
    /** GET /actions */
    public function index()
    {
        $actions = Action::with(['customer', 'user'])
                         ->where('customer_id', auth()->user()->customer_id) // sadece kendi firmasının kayıtları
                         ->orderBy('action_date', 'desc')
                         ->get();

        return view('actions.index', compact('actions'));
    }

    /** GET /actions/create */
    public function create()
    {
        // Artık müşteri ve kullanıcı listesine gerek yok
        return view('actions.create');
    }

    /** POST /actions */
    public function store(Request $request)
    {
        // 1) Formdan gelen verileri doğrula (yalnızca işlem bilgileri)
        $validated = $request->validate([
            'action_type' => 'required|string|max:255',
            'action_date' => 'required|date',
        ]);

        // 2) Modeli oluştur ve sistem alanlarını ata
        Action::create([
            'action_type' => $validated['action_type'],
            'action_date' => $validated['action_date'],
            'user_id'     => auth()->id(),
            'customer_id' => auth()->user()->customer_id,
            'updated_by'  => auth()->id(),
        ]);

        return redirect()
            ->route('actions.index')
            ->with('success', 'Action recorded successfully.');
    }

    /** GET /actions/{action} */
    public function show(Action $action)
    {
        $this->authorizeAction($action);

        $action->load(['customer', 'user']);
        return view('actions.show', compact('action'));
    }

    /** GET /actions/{action}/edit */
    public function edit(Action $action)
    {
        $this->authorizeAction($action);

        return view('actions.edit', compact('action'));   // listeler kaldırıldı
    }

    /** PUT /actions/{action} */
    public function update(Request $request, Action $action)
    {
        $this->authorizeAction($action);

        $validated = $request->validate([
            'action_type' => 'required|string|max:255',
            'action_date' => 'required|date',
        ]);

        $action->update([
            'action_type' => $validated['action_type'],
            'action_date' => $validated['action_date'],
            'updated_by'  => auth()->id(),
        ]);

        return redirect()
            ->route('actions.index')
            ->with('success', 'Action updated successfully.');
    }

    /** DELETE /actions/{action} */
    public function destroy(Action $action)
    {
        $this->authorizeAction($action);

        $action->delete();

        return redirect()
            ->route('actions.index')
            ->with('success', 'Action deleted successfully.');
    }

    /** GET /actions/by-customer */
    public function byCustomer(Request $request)
    {
        // İsteğe bağlı: admin yetkisine göre farklı müşteri seçimi yapılabilir
        $selected = $request->input('customer_id');

        $query = Action::with(['customer', 'user'])
                       ->orderBy('action_date', 'desc');

        // Admin değilse yalnızca kendi müşteri kaydını görsün
        if (!auth()->user()->is_admin) {
            $query->where('customer_id', auth()->user()->customer_id);
        } elseif ($selected) {
            $query->where('customer_id', $selected);
        }

        $actions = $query->get();

        return view('actions.by-customer', compact('actions', 'selected'));
    }

    /* --------------------------------------------------------- */
    /* Yardımcı metot                                             */
    /* --------------------------------------------------------- */
    private function authorizeAction(Action $action)
    {
        if ($action->customer_id !== auth()->user()->customer_id && !auth()->user()->is_admin) {
            abort(403, 'Bu kayda erişim yetkiniz yok.');
        }
    } // <-- burası kapanmalı

} // sınıf kapanışı burada olmalı
