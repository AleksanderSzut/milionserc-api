@component('mail::message')
# Wiadomość od: {{$name}}
## Adres email: {{$email}}

{{$content}}


Dziękuje,<br>
{{ config('app.name') }}
@endcomponent
