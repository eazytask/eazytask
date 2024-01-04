@component('mail::message')
# Hello {{ ucwords($user->fname) }},

one of your shift {{ $roster->project->pName }} week ending
{{ \Carbon\Carbon::parse($roster->roaster_date)->endOfWeek()->format('d-m-Y') }} has been {{ $ext }}. You are
not required to sign on this shift. Please
check eazytask for changes.

**{{ $roster->project->pName }}**, {{ \Carbon\Carbon::parse($roster->roaster_date)->format('d/m/Y') }},
{{ \Carbon\Carbon::parse($roster->shift_start)->format('H:i') }}-{{ \Carbon\Carbon::parse($roster->shift_end)->format('H:i') }}.

@component('mail::button', ['url' => 'https://eazytask.au/'])
    Check Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
