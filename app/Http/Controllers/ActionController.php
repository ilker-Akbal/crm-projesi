<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Action;
use App\Models\Contact;

class ActionController extends Controller
{
    /* --------------------------------------------------------- */
    /* Liste                                                     */
    /* --------------------------------------------------------- */
    public function index()
    {
        $actions = Action::with(['customer', 'contact'])
            ->where('customer_id', auth()->user()->customer_id)
            ->orderBy('action_date', 'desc')
            ->get();

        return view('actions.index', compact('actions'));
    }

    /* --------------------------------------------------------- */
    /* Yeni form                                                 */
    /* --------------------------------------------------------- */
    public function create()
    {
        $contacts = Contact::with('company')
            ->whereHas('company', fn($q) =>
                $q->where('customer_id', auth()->user()->customer_id)
            )
            ->orderBy('name')
            ->get();

        return view('actions.create', compact('contacts'));
    }

    /* --------------------------------------------------------- */
    /* Kaydet                                                    */
    /* --------------------------------------------------------- */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'action_type' => 'required|string|max:255',
            'action_date' => 'required|date',
            'status'      => 'required|in:potansiyel,açık,kapalı,iptal',
            'description' => 'nullable|string|max:1000',
            'contact_id'  => 'required|exists:contacts,id',
        ]);

        Action::create([
            'action_type' => $validated['action_type'],
            'action_date' => $validated['action_date'],
            'status'      => $validated['status'],
            'description' => $validated['description'] ?? null,
            'contact_id'  => $validated['contact_id'],
            'customer_id' => auth()->user()->customer_id,
            'user_id'     => auth()->id(),        // ← ekledik
            'updated_by'  => auth()->id(),
        ]);

        return redirect()->route('actions.index')
            ->with('success', 'Faaliyet başarıyla kaydedildi.');
    }

    /* --------------------------------------------------------- */
    /* Detay                                                     */
    /* --------------------------------------------------------- */
    public function show(Action $action)
    {
        $this->authorizeAction($action);
        $action->load(['customer', 'contact']);
        return view('actions.show', compact('action'));
    }

    /* --------------------------------------------------------- */
    /* Düzenle form                                              */
    /* --------------------------------------------------------- */
    public function edit(Action $action)
    {
        $this->authorizeAction($action);

        $contacts = Contact::with('company')
            ->whereHas('company', fn($q) =>
                $q->where('customer_id', auth()->user()->customer_id)
            )
            ->orderBy('name')
            ->get();

        return view('actions.edit', compact('action', 'contacts'));
    }

    /* --------------------------------------------------------- */
    /* Güncelle                                                  */
    /* --------------------------------------------------------- */
    public function update(Request $request, Action $action)
    {
        $this->authorizeAction($action);

        $validated = $request->validate([
            'action_type' => 'required|string|max:255',
            'action_date' => 'required|date',
            'status'      => 'required|in:potansiyel,açık,kapalı,iptal',
            'description' => 'nullable|string|max:1000',
            'contact_id'  => 'required|exists:contacts,id',
        ]);

        $action->update([
            'action_type' => $validated['action_type'],
            'action_date' => $validated['action_date'],
            'status'      => $validated['status'],
            'description' => $validated['description'] ?? null,
            'contact_id'  => $validated['contact_id'],
            'user_id'     => auth()->id(),        // ← ekledik
            'updated_by'  => auth()->id(),
        ]);

        return redirect()->route('actions.index')
            ->with('success', 'Faaliyet güncellendi.');
    }

    /* --------------------------------------------------------- */
    /* Sil                                                       */
    /* --------------------------------------------------------- */
    public function destroy(Action $action)
    {
        $this->authorizeAction($action);
        $action->delete();

        return redirect()->route('actions.index')
            ->with('success', 'Faaliyet silindi.');
    }

    /* --------------------------------------------------------- */
    /* Yetkilendirme                                             */
    /* --------------------------------------------------------- */
    private function authorizeAction(Action $action)
    {
        if ($action->customer_id !== auth()->user()->customer_id && !auth()->user()->is_admin) {
            abort(403, 'Bu kayda erişim yetkiniz yok.');
        }
    }
}
