@php
   $companyId = Auth::user()->company_roles->first()->company->id; 
@endphp
<div class="border bg-white p-0">
    <ul class="nav nav-pills custom-hover-nav-tabs">
        <li class="nav-item" role="presentation">
            <a href="/admin/home/employee/{{ $companyId }}" class="nav-link @if($active == 'employee') active @endif">
                <i class="ri-user-fill nav-icon nav-tab-position"></i>
                <h5 class="nav-titl nav-tab-position m-0">Employee</h5>
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="/admin/home/myavailability/go" class="nav-link @if($active == 'time_off') active @endif" >
                <i class="ri-rest-time-fill nav-icon nav-tab-position"></i>
                <h5 class="nav-titl nav-tab-position m-0">Time Off</h5>
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="/admin/home/inducted/site/{{ $companyId }}"  class="nav-link @if($active == 'inducted_site') active @endif" >
                <i class="ri-global-line nav-icon nav-tab-position"></i>
                <h5 class="nav-titl nav-tab-position m-0">Inducted Site</h5>
            </a>
        </li>
        
        <li class="nav-item" role="presentation">
            <a href="{{ route('compliance_page') }}"  class="nav-link @if($active == 'compliance') active @endif" >
                <i class="ri-file-text-line nav-icon nav-tab-position"></i>
                <h5 class="nav-titl nav-tab-position m-0">Compliance</h5>
            </a>
        </li>

    </ul>
</div>