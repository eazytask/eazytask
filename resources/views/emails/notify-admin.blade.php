@component('mail::message')
# Hello {{$admin->name}},

An employee {{$action}} your offer. Please check it.

@component('mail::button', ['url' => 'http://myroaster.info/'])
Check Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
