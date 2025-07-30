<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reminder;
use App\Models\Customer;
use App\Models\User;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Resend\Laravel\Facades\Resend;   
class ReminderController extends Controller
{
    /* -------------------------------------------------
     |  GET /reminders → Liste (Dinamik yıldönümü ekli)
     * ------------------------------------------------*/
        public function index()
    {
        // 1) Mevcut hatırlatıcıları getir
        $reminders = Reminder::where('customer_id', Auth::user()->customer_id)
                             ->with(['customer', 'user'])
                             ->latest('reminder_date')
                             ->get();

        // 2) Şirket yıl dönümlerini kontrol et
        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->whereNotNull('foundation_date')
                            ->get();

        foreach ($companies as $company) {
            $foundation = Carbon::parse($company->foundation_date);

            if ($foundation->format('m-d') === now()->format('m-d')) {
                $years = now()->year - $foundation->year;

                /* ---------- TEKRAR KONTROLÜ ---------- */
                $cacheKey = "anniv-sent:{$company->id}:" . now()->toDateString();
                if (! Cache::add($cacheKey, true, now()->endOfDay())) {
                    // bugün mail ve hatırlatıcı zaten oluşturulmuş, atla
                    continue;
                }
                /* ------------------------------------- */

                // — Dinamik hatırlatıcı listesine ekle (yalnızca ilk görmede)
                $reminders->push(new Reminder([
                    'title'         => "{$company->company_name} – {$years}. Yıl Dönümü Kutlaması",
                    'reminder_date' => now()->toDateString(),
                    'explanation'   => "{$company->company_name} şirketinin {$years}. yıl dönümü.",
                    'customer_id'   => $company->customer_id,
                    'user_id'       => Auth::id(),
                ]));

                // — Kuruluşa tebrik e-postası gönder
                Resend::emails()->send([
                    'from'    => 'onboarding@resend.dev',
                    'to'      => $company->email,
                    'subject' => "{$company->company_name}’nin {$years}. Yıl Dönümü Kutlaması!",
                    'html'    => view('emails.anniversary', compact('company', 'years'))->render(),
                ]);
            }
        }

        // 3) Görünümü döndür
        return view('reminders.index', compact('reminders'));
    }

    /* -------------------------------------------------
     |  GET /reminders/create → Yeni hatırlatıcı formu
     * ------------------------------------------------*/
    public function create()
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        $users     = User::orderBy('username')->get();

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
                         ->with('success', 'Hatırlatıcı başarıyla eklendi.');
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
     |  GET /reminders/{reminder}/edit → Düzenle formu
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
                         ->with('success', 'Hatırlatıcı başarıyla güncellendi.');
    }

    /* -------------------------------------------------
     |  DELETE /reminders/{reminder} → Sil
     * ------------------------------------------------*/
    public function destroy(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);
        $reminder->delete();
        return redirect()->route('reminders.index')
                         ->with('success', 'Hatırlatıcı başarıyla silindi.');
    }

    /* -------------------------------------------------
     |  Yardımcı: hatırlatma sahibini kontrol et
     * ------------------------------------------------*/
    private function authorizeReminder(Reminder $reminder): void
    {
        if ($reminder->customer_id !== Auth::user()->customer_id) {
            abort(403, 'Bu hatırlatmaya erişim yetkiniz yok.');
        }
    }
}
