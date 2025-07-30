<?php

// app/Mail/CompanyAnniversaryMail.php
namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyAnniversaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Company $company,
        public int $years
    ) {}

    public function build(): self
    {
        return $this
            ->subject("{$this->company->company_name} – {$this->years}. Yıl Dönümünüz Kutlu Olsun!")
            ->markdown('emails.anniversary');
    }
}
