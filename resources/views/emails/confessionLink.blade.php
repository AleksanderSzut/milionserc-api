@component('mail::message')
# Dziękujemy {{$name}} za stworzenie wyznania.

Link do wyznania:

@component('mail::button', ['url' => $confessionLink])
    $confessionLink
@endcomponent

Dziękuje,<br>
{{ config('app.name') }}
@endcomponent
