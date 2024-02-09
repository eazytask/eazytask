@extends('layouts.Admin.master')
@section('title') Schedule @endsection

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1') Dashboard @endslot
        @slot('title') Schedule @endslot
    @endcomponent
@endsection