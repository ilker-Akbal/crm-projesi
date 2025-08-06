<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Company;

class ContactController extends Controller
{
    /* ───────────────────────────────────────────────────────────
       GET /contacts   → Liste  (firma filtresi destekli)
    ─────────────────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $query = Contact::with('company');

        // Firma filtresi
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $contacts  = $query->latest('updated_at')->get();
        $companies = Company::orderBy('company_name')->get();   // Selectbox doldurmak için

        return view('contacts.index', compact('contacts', 'companies'));
    }

    /* ───────────────────────────────────────────────────────────
       GET /contacts/pdf   → PDF (firma filtresi destekli)
    ─────────────────────────────────────────────────────────── */
    public function exportPdf(Request $request)
    {
        $query = Contact::with('company');

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $contacts = $query->orderBy('name')->get();

        // Tek firma seçiliyse başlığa gösterilecek ad
        $range = $request->filled('company_id')
               ? $contacts->first()?->company?->company_name
               : null;

        return Pdf::loadView('contacts.pdf', compact('contacts', 'range'))
                  ->download('kisiler.pdf');
    }

    /* ───────────────────────────────────────────────────────────
       GET /contacts/pdf/filter   → Tarih + isteğe bağlı firma filtresi
    ─────────────────────────────────────────────────────────── */
    public function exportPdfWithFilter(Request $request)
    {
        $request->validate([
            'start' => ['required', 'date'],
            'end'   => ['required', 'date', 'after_or_equal:start'],
        ]);

        $query = Contact::with('company')
                 ->whereBetween('created_at', [$request->start, $request->end]);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($qry) =>
                $qry->where('name',  'like', "%$q%")
                    ->orWhere('email','like', "%$q%"));
        }

        $contacts = $query->orderBy('created_at','desc')->get();

        $range = [
            Carbon::parse($request->start)->format('d.m.Y'),
            Carbon::parse($request->end)->format('d.m.Y'),
        ];

        return Pdf::loadView('contacts.pdf', compact('contacts','range'))
                  ->download("kisiler_{$request->start}_{$request->end}.pdf");
    }

    /* ───────────────────────────────────────────────────────────
       GET /contacts/create   → Form
    ─────────────────────────────────────────────────────────── */
    public function create()
    {
        $companies = Company::orderBy('company_name')->get();
        return view('contacts.create', compact('companies'));
    }

    /* ───────────────────────────────────────────────────────────
       POST /contacts   → Kaydet
    ─────────────────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $data = $this->validateContact($request);
        Contact::create($data);

        return redirect()->route('contacts.index')
                         ->with('success','Kişi başarıyla eklendi.');
    }

    /* ───────────────────────────────────────────────────────────
       GET /contacts/{contact}/edit   → Form
    ─────────────────────────────────────────────────────────── */
    public function edit(Contact $contact)
    {
        $companies = Company::orderBy('company_name')->get();
        return view('contacts.edit', compact('contact','companies'));
    }

    /* ───────────────────────────────────────────────────────────
       PUT /contacts/{contact}   → Güncelle
    ─────────────────────────────────────────────────────────── */
    public function update(Request $request, Contact $contact)
    {
        $data = $this->validateContact($request, $contact->id);
        $contact->update($data);

        return redirect()->route('contacts.index')
                         ->with('success','Kişi başarıyla güncellendi.');
    }

    /* ───────────────────────────────────────────────────────────
       GET /contacts/{contact}   → Göster
    ─────────────────────────────────────────────────────────── */
    public function show(Contact $contact)
    {
        $contact->load('company');
        return view('contacts.show', compact('contact'));
    }

    /* ───────────────────────────────────────────────────────────
       DELETE /contacts/{contact}   → Sil
    ─────────────────────────────────────────────────────────── */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index')
                         ->with('success','Kişi başarıyla silindi.');
    }

    /* ─────────────────────  ORTAK VALIDASYON  ───────────────── */
    private function validateContact(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name'       => ['required', 'regex:/^[A-Za-zÇçĞğİıÖöŞşÜü\s]+$/u', 'min:2'],
            'phone'      => ['required','digits:11', Rule::unique('contacts','phone')->ignore($id)],
            'email'      => ['nullable','email',    Rule::unique('contacts','email')->ignore($id)],
            'company_id' => ['nullable','exists:companies,id'],
            'position'   => ['nullable','string'],
        ], [
            'name.regex'   => 'Ad sadece harf ve boşluk içerebilir.',
            'phone.digits' => 'Telefon numarası 11 haneli olmalıdır.',
        ]);
    }
}
