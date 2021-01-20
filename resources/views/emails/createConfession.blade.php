@component('mail::message')
# Dziękujemy {{$name}} za opłacenie zamówienia

Kliknij w przycisk aby stworzyć swoje wyznanie

@foreach ($createConfessionLinks as $link)
@component('mail::button', ['url' => $link, 'color' => 'gold'])
    Stwórz wyznanie
@endcomponent
@endforeach

Dziękuje,<br>
{{ config('app.name') }}
@endcomponent
