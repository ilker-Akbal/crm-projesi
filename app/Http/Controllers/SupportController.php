<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /** GET /support */
    public function index()
    {
        $supports = SupportRequest::with('customer')
            ->orderBy('registration_date', 'desc')
            ->get();

        return view('support.index', compact('supports'));
    }

    /** GET /support/create */
    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('support.create', compact('customers'));
    }

    /** POST /support */
   public function store(Request $request)
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'explanation'       => 'nullable|string',
            'situation'         => 'required|in:pending,resolved',
            'registration_date' => 'required|date',
        ]);

        // oturum açan kullanıcının ilişkili customer_id'sini atıyoruz
        $data['customer_id'] = Auth::user()->customer_id;

        SupportRequest::create($data);

        return redirect()
            ->route('support.index')
            ->with('success','Support request created successfully.');
    }
    /** GET /support/{id} */
    public function show(SupportRequest $support)
    {
        $support->load('customer');
        return view('support.show', compact('support'));
    }

    /** GET /support/{id}/edit */
    public function edit(SupportRequest $support)
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('support.edit', compact('support', 'customers'));
    }

    /** PUT /support/{id} */
    public function update(Request $request, SupportRequest $support)
    {
        $data = $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'title'             => 'required|string|max:255',
            'explanation'       => 'nullable|string',
            'situation'         => 'required|in:pending,resolved',
            'registration_date' => 'required|date',
        ]);

        $support->update($data);

        return redirect()
            ->route('support.index')
            ->with('success', 'Support request updated successfully.');
    }

    /** DELETE /support/{id} */
    public function destroy(SupportRequest $support)
    {
        $support->delete();

        return redirect()
            ->route('support.index')
            ->with('success', 'Support request deleted successfully.');
    }

    /** GET /support/pending */
    public function pending()
    {
        $supports = SupportRequest::with('customer')
            ->where('situation', 'pending')
            ->orderBy('registration_date', 'desc')
            ->get();

        return view('support.pending', compact('supports'));
    }

    /** GET /support/resolved */
    public function resolved()
    {
        $supports = SupportRequest::with('customer')
            ->where('situation', 'resolved')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('support.resolved', compact('supports'));
    }
}
