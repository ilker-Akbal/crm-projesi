<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportRequest;
use App\Models\Customer;

class SupportController extends Controller
{
    /* -------------------------------------------------
     |  GET /support → Liste
     * ------------------------------------------------*/
    public function index()
    {
        $supports = SupportRequest::where('customer_id', Auth::user()->customer_id)
                                  ->with('customer')
                                  ->orderBy('registration_date', 'desc')
                                  ->get();

        return view('support.index', compact('supports'));
    }

    /* -------------------------------------------------
     |  GET /support/create → Form
     * ------------------------------------------------*/
    public function create()
    {
        // Başka müşteri seçilmesin
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        return view('support.create', compact('customers'));
    }

    /* -------------------------------------------------
     |  POST /support → Kaydet
     * ------------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'explanation'       => 'nullable|string',
            'situation'         => 'required|in:pending,resolved',
            'registration_date' => 'required|date',
        ]);

        SupportRequest::create($data + [
            'customer_id' => Auth::user()->customer_id,
        ]);

        return redirect()->route('support.index')
                         ->with('success', 'Support request created successfully.');
    }

    /* -------------------------------------------------
     |  GET /support/{support} → Detay
     * ------------------------------------------------*/
    public function show(SupportRequest $support)
    {
        $this->authorizeSupport($support);

        $support->load('customer');

        return view('support.show', compact('support'));
    }

    /* -------------------------------------------------
     |  GET /support/{support}/edit → Form
     * ------------------------------------------------*/
    public function edit(SupportRequest $support)
    {
        $this->authorizeSupport($support);

        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        return view('support.edit', compact('support', 'customers'));
    }

    /* -------------------------------------------------
     |  PUT /support/{support} → Güncelle
     * ------------------------------------------------*/
    public function update(Request $request, SupportRequest $support)
    {
        $this->authorizeSupport($support);

        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'explanation'       => 'nullable|string',
            'situation'         => 'required|in:pending,resolved',
            'registration_date' => 'required|date',
        ]);

        $support->update($data);

        return redirect()->route('support.index')
                         ->with('success', 'Support request updated successfully.');
    }

    /* -------------------------------------------------
     |  DELETE /support/{support} → Sil
     * ------------------------------------------------*/
    public function destroy(SupportRequest $support)
    {
        $this->authorizeSupport($support);

        $support->delete();

        return redirect()->route('support.index')
                         ->with('success', 'Support request deleted successfully.');
    }

    /* -------------------------------------------------
     |  GET /support/pending → Bekleyenler
     * ------------------------------------------------*/
    public function pending()
    {
        $supports = SupportRequest::where('customer_id', Auth::user()->customer_id)
                                  ->where('situation', 'pending')
                                  ->with('customer')
                                  ->orderBy('registration_date', 'desc')
                                  ->get();

        return view('support.pending', compact('supports'));
    }

    /* -------------------------------------------------
     |  GET /support/resolved → Çözülenler
     * ------------------------------------------------*/
    public function resolved()
    {
        $supports = SupportRequest::where('customer_id', Auth::user()->customer_id)
                                  ->where('situation', 'resolved')
                                  ->with('customer')
                                  ->orderBy('updated_at', 'desc')
                                  ->get();

        return view('support.resolved', compact('supports'));
    }

    /* -------------------------------------------------
     |  Yardımcı: talep sahibi mi?
     * ------------------------------------------------*/
    private function authorizeSupport(SupportRequest $support): void
    {
        if ($support->customer_id !== Auth::user()->customer_id) {
            abort(403, 'Bu destek kaydına erişim yetkiniz yok.');
        }
    }
}
