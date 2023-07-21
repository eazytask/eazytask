@extends('layouts.Admin.master')
@php
$role_name = ['Super-Admin','Admin','Employee','Supervisor']
@endphp
@section('admincontent')

<div class="row">
    <div class="col-md-12">
        <div class="card plan-card border-primary text-center">
            <div class="justify-content-between align-items-center row p-1">
                @foreach($companies as $k => $company)
                <div class="col-md-6 col-sm-6 text-uppercase">
                    <div>
                        <h5>
                            <a href="/home/switch/company/{{$company->id}}" class="btn btn-block text-center text-light {{auth()->user()->company_roles->first()->id ==$company->id ?'bg-danger disabled':'bg-light-primary'}}">
                                {{$company->company->company_code}} ({{$role_name[$company->role - 1]}})
                            </a>
                        </h5>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection