@extends('layouts.Admin.master')

@section('admin_page_content')
    @component('components.breadcrumb')
        @slot('li_1')
            Employee
        @endslot
        @slot('title')
            Compliance
        @endslot
    @endcomponent
    @component('components.employeeTab')
        @slot('active') compliance @endslot
    @endcomponent
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="flex-grow-1">
                        <button class="btn btn-info add-btn" data-bs-toggle="modal" data-bs-target="#addCompliance" id="add" disabled>
                            <i class="ri-add-fill me-1 align-bottom"></i>Add Compliance
                        </button>
                        @include('pages.Admin.compliance.modals.compliance_add')
                    </div>
                </div>
            </div>
        </div>
    
            <!--end col-->
        <div class="card mb-0" id="compliance">
            <div class="card-body">
                <div class="table-responsive mb-3">
                    <table class="display table table-bordered mt-3" id="compliance_table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Number</th>
                                <th>License No</th>
                                <th>First Aid</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all" id="compliance_body">

                        </tbody>
                    </table>
                    <div class="noresult" style="display: none">
                        <div class="text-center">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                            </lord-icon>
                            <h5 class="mt-2">Sorry! No Result Found</h5>
                            <p class="text-muted mb-0">We've searched more than 150+ companies
                                We did not find any
                                companies for you search.</p>
                        </div>
                    </div>
                </div>

                <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1"
                    aria-labelledby="deleteRecordLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="btn-close deleteRecord-close"></button>
                            </div>
                            <div class="modal-body p-5 text-center">
                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                    colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                                </lord-icon>
                                <div class="mt-4 text-center">
                                    <h4 class="fs-semibold">You are about to delete a company ?</h4>
                                    <p class="text-muted fs-14 mb-4 pt-1">Deleting your company will
                                        remove all of your information from our database.</p>
                                    <div class="hstack gap-2 justify-content-center remove">
                                        <button class="btn btn-link link-success fw-medium text-decoration-none"
                                            data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i>
                                            Close</button>
                                        <button class="btn btn-danger" id="delete-record">Yes,
                                            Delete It!!</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end delete modal -->

            </div>
        </div>
        <!--end card-->
    </div>
@endsection
@push('scripts')
    @include('components.select2');
    <script>
        $(document).ready(function(){
            $('#add').removeAttr('disabled');
            function get_compliance(){
                $.ajax({
                    url: "{{route('get_compliance')}}",
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(result){
                        console.log(result);
                        if(result.status){
                            compliance_entries(result.data);
                        }
                    }
                });
            }
            get_compliance();
            function compliance_entries(data){
                let html = '';
                let container = $('#compliance_body');
                if(data.length){
                    data.forEach((compliance)=>{
                        console.log(compliance);
                    });
                }else{
                    html += '<h5 class="text-center text-muted">Not Found!</h5>'
                }
                container.html(html);
            }
        });
    </script>
@endpush