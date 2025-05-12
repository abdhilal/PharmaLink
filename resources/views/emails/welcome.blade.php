@component('mail::message')
# مرحبًا {{ $user->name }}

شكرًا لتسجيلك معنا!

@component('mail::button', ['url' => route('warehouse.dashboard')])
ابدأ الآن
@endcomponent

مع تحياتنا،
{{ config('app.name') }}
@endcomponent
