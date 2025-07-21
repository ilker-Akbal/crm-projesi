<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CurrentMovement;
use App\Models\CurrentCard;

class MovementController extends Controller
{
    /* -------------------------------------------------
     |  GET /movements  →  Liste
     * ------------------------------------------------*/
    public function index()
    {
        $movements = CurrentMovement::whereHas(
                'currentCard',
                fn ($q) => $q->where('customer_id', Auth::user()->customer_id)
            )
            ->with('currentCard.customer')
            ->orderBy('departure_date', 'desc')
            ->get();

        return view('movements.index', compact('movements'));
    }

    /* -------------------------------------------------
     |  GET /movements/create  →  Form
     * ------------------------------------------------*/
    public function create()
    {
        $accounts = CurrentCard::where('customer_id', Auth::user()->customer_id)
                               ->with('customer')
                               ->orderBy('opening_date', 'desc')
                               ->get();

        return view('movements.create', compact('accounts'));
    }

    /* -------------------------------------------------
     |  POST /movements  →  Kaydet
     * ------------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'current_id'     => 'required|exists:current_cards,id',
            'departure_date' => 'required|date',
            'movement_type'  => 'required|in:Debit,Credit',
            'amount'         => 'required|numeric|min:0',
            'explanation'    => 'nullable|string',
        ]);

        // Seçilen cari kart gerçekten giriş yapan müşteriye mi ait?
        if (! CurrentCard::where('id', $data['current_id'])
                         ->where('customer_id', Auth::user()->customer_id)
                         ->exists()) {
            abort(403, 'Bu hesaba hareket ekleme yetkiniz yok.');
        }

        CurrentMovement::create($data + ['updated_by' => Auth::id()]);

        return redirect()->route('movements.index')
                         ->with('success', 'Movement recorded successfully.');
    }

    /* -------------------------------------------------
     |  GET /movements/{movement}  →  Detay
     * ------------------------------------------------*/
    public function show(CurrentMovement $movement)
    {
        $this->authorizeMovement($movement);

        $movement->load('currentCard.customer');

        return view('movements.show', compact('movement'));
    }

    /* -------------------------------------------------
     |  GET /movements/{movement}/edit  →  Form
     * ------------------------------------------------*/
    public function edit(CurrentMovement $movement)
    {
        $this->authorizeMovement($movement);

        $accounts = CurrentCard::where('customer_id', Auth::user()->customer_id)
                               ->with('customer')
                               ->orderBy('opening_date', 'desc')
                               ->get();

        return view('movements.edit', compact('movement', 'accounts'));
    }

    /* -------------------------------------------------
     |  PUT /movements/{movement}  →  Güncelle
     * ------------------------------------------------*/
    public function update(Request $request, CurrentMovement $movement)
    {
        $this->authorizeMovement($movement);

        $data = $request->validate([
            'current_id'     => 'required|exists:current_cards,id',
            'departure_date' => 'required|date',
            'movement_type'  => 'required|in:Debit,Credit',
            'amount'         => 'required|numeric|min:0',
            'explanation'    => 'nullable|string',
        ]);

        // Yeni current_id de aynı müşteriye ait mi?
        if (! CurrentCard::where('id', $data['current_id'])
                         ->where('customer_id', Auth::user()->customer_id)
                         ->exists()) {
            abort(403, 'Bu hesaba hareket taşıma yetkiniz yok.');
        }

        $movement->update($data);

        return redirect()->route('movements.index')
                         ->with('success', 'Movement updated successfully.');
    }

    /* -------------------------------------------------
     |  DELETE /movements/{movement}  →  Sil
     * ------------------------------------------------*/
    public function destroy(CurrentMovement $movement)
    {
        $this->authorizeMovement($movement);

        $movement->delete();

        return redirect()->route('movements.index')
                         ->with('success', 'Movement deleted successfully.');
    }

    /* -------------------------------------------------
     |  Yardımcı: hareket sahibi mi?
     * ------------------------------------------------*/
    private function authorizeMovement(CurrentMovement $movement): void
    {
        if (
            $movement->currentCard->customer_id !== Auth::user()->customer_id
        ) {
            abort(403, 'Bu harekete erişim yetkiniz yok.');
        }
    }
}
