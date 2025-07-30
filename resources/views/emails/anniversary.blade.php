<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5;">
  <h1 style="color:#111;">
    {{ $company->company_name }} {{ $years }} Yaşında! 🎉
  </h1>

  <p>Değerli iş ortağımız, şirketinizin {{ $years }}. kuruluş yıl dönümünü kutlarız.</p>

  <p style="margin:24px 0;">
    <a href="{{ config('app.url') }}"
       style="background:#2563eb;color:#fff;padding:12px 24px;text-decoration:none;border-radius:6px;">
      CRM’e Git
    </a>
  </p>

  <p>Saygılarımızla,<br>{{ config('app.name') }}</p>
</body>
</html>
