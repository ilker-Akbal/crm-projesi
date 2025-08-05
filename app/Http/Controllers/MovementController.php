<?php

namespace App\Http\Controllers;

use App\Models\CurrentCard;
use App\Models\CurrentMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller
{
    /* ───────────────────────────────────────────────
       LİSTE  ( /movements )
    ─────────────────────────────────────────────── */
    public function index(Request $req)
    {
        $customerId = Auth::user()->customer_id;

        // Ana sorgu: aktif kullanıcının cari kartlarındaki hareketler
        $query = CurrentMovement::select([
                        'current_movements.*',
                        // ✅ Koşan bakiye (Debit işlemler eksi, Credit artı)
                        DB::raw('SUM(CASE WHEN movement_type = "Debit" THEN -amount ELSE amount END)
                                 OVER (PARTITION BY current_id ORDER BY departure_date, id) AS running_balance')
                  ])
                  ->with('currentCard.customer')
                  ->whereHas('currentCard', fn($q) => $q->where('customer_id', $customerId));

        /* --- Filtreler --- */
        if ($req->filled('type')) $query->where('movement_type', $req->type);  // Debit / Credit
        if ($req->filled('from')) $query->whereDate('departure_date', '>=', $req->from);
        if ($req->filled('to'))   $query->whereDate('departure_date', '<=', $req->to);
        if ($req->filled('q'))    $query->where('explanation', 'like', '%'.$req->q.'%');

        // En yeni üste gelsin
        $movements = $query->latest('departure_date')
                           ->paginate(25)
                           ->withQueryString();

        return view('movements.index', compact('movements'));
    }

    /* ───────────────────────────────────────────────
       PDF – TÜM HESAP HAREKETLERİ  ( /movements/pdf )
    ─────────────────────────────────────────────── */
    public function exportPdf()
    {
        $customerId = Auth::user()->customer_id;

        $movements = CurrentMovement::select([
                            'current_movements.*',
                            DB::raw('SUM(CASE WHEN movement_type = "Debit" THEN -amount ELSE amount END)
                                     OVER (PARTITION BY current_id ORDER BY departure_date, id) AS running_balance')
                       ])
                       ->with('currentCard.customer')
                       ->whereHas('currentCard', fn($q) => $q->where('customer_id', $customerId))
                       ->latest('departure_date')
                       ->get();

        return Pdf::loadView('movements.pdf', compact('movements'))
                  ->download('hesap_hareketleri.pdf');
    }

    /* ───────────────────────────────────────────────
       PDF – FİLTRELİ  ( /movements/pdf/filter )
    ─────────────────────────────────────────────── */
    public function exportPdfWithFilter(Request $r)
    {
        $customerId = Auth::user()->customer_id;

        $query = CurrentMovement::select([
                        'current_movements.*',
                        DB::raw('SUM(CASE WHEN movement_type = "Debit" THEN -amount ELSE amount END)
                                 OVER (PARTITION BY current_id ORDER BY departure_date, id) AS running_balance')
                 ])
                 ->with('currentCard.customer')
                 ->whereHas('currentCard', fn($q) => $q->where('customer_id', $customerId));

        if ($r->filled('type')) $query->where('movement_type', $r->type);
        if ($r->filled('from')) $query->whereDate('departure_date', '>=', $r->from);
        if ($r->filled('to'))   $query->whereDate('departure_date', '<=', $r->to);

        $movements = $query->latest('departure_date')->get();

        $rangeTxt = ($r->from && $r->to)
            ? [Carbon::parse($r->from)->format('d.m.Y'), Carbon::parse($r->to)->format('d.m.Y')]
            : null;

        return Pdf::loadView('movements.pdf', [
                    'movements' => $movements,
                    'range'     => $rangeTxt,
                    'type'      => $r->type ?: null,
                ])->download('hesap_hareketleri_filtreli.pdf');
    }

    /* ───────────────────────────────────────────────
       CRUD METOTLARI (orijinal hâlleri değişmedi)
    ─────────────────────────────────────────────── */

    public function create()
    {
        $accounts = CurrentCard::where('customer_id', Auth::user()->customer_id)
                               ->with('customer')
                               ->orderByDesc('opening_date')
                               ->get();

        return view('movements.create', compact('accounts'));
    }

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
                         ->with('success', 'Hareket başarıyla eklendi.');
    }

    public function show(CurrentMovement $movement)
    {
        $this->authorizeMovement($movement);
        $movement->load('currentCard.customer');
        return view('movements.show', compact('movement'));
    }

    public function edit(CurrentMovement $movement)
    {
        $this->authorizeMovement($movement);

        $accounts = CurrentCard::where('customer_id', Auth::user()->customer_id)
                               ->with('customer')
                               ->orderByDesc('opening_date')
                               ->get();

        return view('movements.edit', compact('movement', 'accounts'));
    }

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

        if (! CurrentCard::where('id', $data['current_id'])
                         ->where('customer_id', Auth::user()->customer_id)
                         ->exists()) {
            abort(403, 'Bu hesaba hareket taşıma yetkiniz yok.');
        }

        $movement->update($data);

        return redirect()->route('movements.index')
                         ->with('success', 'Hareket güncellendi.');
    }

    public function destroy(CurrentMovement $movement)
    {
        $this->authorizeMovement($movement);
        $movement->delete();
        return redirect()->route('movements.index')
                         ->with('success', 'Hareket silindi.');
    }

    /* ───────────────────────────────────────────────
       Yardımcı: hareket sahibini doğrula
    ─────────────────────────────────────────────── */
    private function authorizeMovement(CurrentMovement $movement): void
    {
        if ($movement->currentCard->customer_id !== Auth::user()->customer_id) {
            abort(403, 'Bu harekete erişim yetkiniz yok.');
        }
    }
}
