@component('mail::message')
# Hello {{$data->name}}

{{ucfirst(auth()->user()->name)}} approves your leave day (for {{$data->leave_type}}) from company **{{strtoupper(auth()->user()->company->company)}}**.

from **{{\Carbon\Carbon::parse($data->start_date)->format('d/m/Y')}}** to  **{{\Carbon\Carbon::parse($data->end_date)->format('d/m/Y')}}**

Thanks,<br>
{{ config('app.name') }}
@endcomponent
