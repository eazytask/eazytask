<div class="modal fade text-left p-md-1 p-0" id="addRevenue" role="dialog" aria-labelledby="myModalLabel17"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h4 class="modal-title text-white" id="myModalLabel17">Add Revenue</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0">

                                <div class="card-body pb-0">
                                    <form class="form" action="{{ route('store-revenue') }}" method="POST"
                                        id="newModalForm">
                                        @csrf
                                    <input type="hidden" name="id" id="id">
                                        <div class="row">
                                            <!-- <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="first-name-column">Client Name *</label>
                                                    <select class="form-control select2" name="client_name" id="client_name"
                                                        aria-label="Default select example" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        @foreach ($clients as $client)
                                                            @if ($client->status == 1)
                                                                <option value="{{ $client->id }}">
                                                                    {{ $client->cname }}
                                                                </option>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> -->

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="first-name-column">Venue Name *</label>
                                                    <select class="form-control select2" name="project_name" id="project_name"
                                                        aria-label="Default select example" required>
                                                        <option value="" disabled selected hidden>Please Choose...
                                                        </option>
                                                        @foreach ($projects as $project)
                                                            @if ($project->Status == 1)
                                                                <option value="{{ $project->id }}">
                                                                    {{ $project->pName }}
                                                                </option>
                                                            @else
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-12 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="roaster_date_from">Roster Date From *</label>
                                                    <input type="text" id="roaster_date_from" name="roaster_date_from" required class="form-control format-picker" placeholder="Roster Date From" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="roaster_date_to">Roster Date To *</label>
                                                    <input type="text" id="roaster_date_to" name="roaster_date_to" required class="form-control format-picker" placeholder="Roster Date To" />
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="email-id-column">Shift Start *</label>
                                                    <input type="text" class="form-control flatpickr-date-time"
                                                        name="shift_start" placeholder="Select shift start" required />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="email-id-column">Shift End *</label>
                                                    <input type="text" class="form-control flatpickr-date-time"
                                                        name="shift_end" placeholder="Select shift end" required />
                                                </div>
                                            </div> -->

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="email-id-column">Hours *</label>
                                                    <input type="number" class="form-control" name="hours" id="hours"
                                                        placeholder="Enter hours" required />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="email-id-column">Rate *</label>
                                                    <input type="number" class="form-control" name="rate" id="rate"
                                                        placeholder="Enter rate" required />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="email-id-column">Amount *</label>
                                                    <input type="number" class="form-control" name="amount" id="amount"
                                                        placeholder="Enter amount" required />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="company-column">Remarks</label>
                                                    <input type="text" class="form-control" name="remarks" id="remarks"
                                                        placeholder="Enter remarks" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 pl-25 pr-25">
                                                <div class="form-group">
                                                    <label for="payment_date">Payment Date *</label>
                                                    <input type="text" id="payment_date" name="payment_date" required class="form-control format-picker" placeholder="Select payment date" />
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="updatebtn"><i data-feather='check'></i></button>
                <button type="submit" class="btn btn-success" id="savebtn"><i data-feather='save'></i></button>
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i data-feather='x'></i></button>
            </div>
            </form>
        </div>
    </div>
</div>
