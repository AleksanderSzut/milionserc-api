@component('mail::message')
# Dziękujemy {{$name}} za stworzenie wyznania.

Link do wyznania: {{$confessionLink}}

@component('mail::button', ['url' => $confessionLink])
    Przejdź do wyznania
@endcomponent

Dziękuje,<br>
{{ config('app.name') }}
@endcomponent
