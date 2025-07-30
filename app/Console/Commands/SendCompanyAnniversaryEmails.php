<?php

// app/Console/Commands/SendCompanyAnniversaryEmails.php
namespace App\Console\Commands;

use App\Mail\CompanyAnniversaryMail;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Resend\Laravel\Facades\Resend;

class SendCompanyAnniversaryEmails extends Command
{
    protected $signature   = 'crm:send-anniversary-mails';
    protected $description = 'Şirket yıl dönümü tebrik e-postalarını gönderir';

    public function handle(): int
    {
        $today = Carbon::today();
        Company::whereMonth('foundation_date', $today->month)
               ->whereDay('foundation_date', $today->day)
               ->chunk(100, function ($companies) use ($today) {
                   foreach ($companies as $company) {
                       $years = $today->year - Carbon::parse($company->foundation_date)->year;

                       // 1) Laravel mail (kuşkusuz en temiz yol)
                       Mail::to($company->email)
                           ->queue(new CompanyAnniversaryMail($company, $years));

                       // 2) İstersen doğrudan Resend API
                       Resend::emails()->send(
                           [
                               'from'    => 'CRM Bot <onboarding@resend.dev>',
                               'to'      => [$company->email],
                               'subject' => "{$company->company_name} – {$years}. Yıl Dönümü",
                               'html'    => view('emails.anniversary', compact('company','years'))->render(),
                           ],
                           [
                               // Idempotency: aynı gün aynı şirkete 2 kez gitmesin
                               'idempotencyKey' => "anniv/{$company->id}/{$today->year}",
                           ]
                       );
                   }
               });

        $this->info('Yıl dönümü e-postaları kontrol edildi.');
        return self::SUCCESS;
    }
}
