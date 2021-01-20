@component('mail::message')
# Dziękujemy {{$name}} za złożenie zamówienia

Do zapłaty: {{$toPay}} zł

Kliknij w przycisk aby zapłacić za zamówienie

@component('mail::button', ['url' => $paymentLink, 'color' => 'gold'])
Przejdź do płatności
@endcomponent

Dziękuje,<br>
{{ config('app.name') }}
@endcomponent
