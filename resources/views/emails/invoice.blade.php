<!DOCTYPE html>
<html lang="tr">
<body>
    <p>Sayın {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }},</p>
    <p>Kliniğimizde yapılan işlemlere ait faturanız ektedir.</p>
    <p>Sağlıklı günler dileriz.</p>
    <p><strong>{{ config('app.name') }}</strong></p>
</body>
</html>