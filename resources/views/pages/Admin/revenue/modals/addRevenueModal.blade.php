<div class="modal fade text-left p-md-1 p-0" id="addRevenue" role="dialog" aria-labelledby="myModalLabel17"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <form action="{{ route('store-revenue') }}" method="POST" id="newModalForm" class="form modal-content">
            <div class="modal-header bg-info-subtle py-3">
                <h5 class="modal-title" id="myModalLabel17">Add Revenue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            @csrf
                            <input type="hidden" name="id" id="id">
                            <div class="row">
                                <!-- <div class="col-md-6 col-6 pl-25 pr-25">
                                    <div class="mb-3">
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
                                    <div class="mb-3">
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
                                    <div class="mb-3">
                                        <label for="roaster_date_from">Roster Date From *</label>
                                        <input type="text" id="roaster_date_from" name="roaster_date_from" required class="form-control format-picker" placeholder="Roster Date From" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 pl-25 pr-25">
                                    <div class="mb-3">
                                        <label for="roaster_date_to">Roster Date To *</label>
                                        <input type="text" id="roaster_date_to" name="roaster_date_to" required class="form-control format-picker" placeholder="Roster Date To" />
                                    </div>
                                </div>
                                <!-- <div class="col-md-6 col-6 pl-25 pr-25">
                                    <div class="mb-3">
                                        <label for="email-id-column">Shift Start *</label>
                                        <input type="text" class="form-control flatpickr-date-time"
                                            name="shift_start" placeholder="Select shift start" required />
                                    </div>
                                </div>

                                <div class="col-md-6 col-6 pl-25 pr-25">
                                    <div class="mb-3">
                                        <label for="email-id-column">Shift End *</label>
                                        <input type="text" class="form-control flatpickr-date-time"
                                            name="shift_end" placeholder="Select shift end" required />
                                    </div>
                                </div> -->

                                <div class="col-md-6 col-6 pl-25 pr-25">
                                    <div class="mb-3">
                                        <label for="email-id-column">Hours *</label>
                                        <input type="number" class="form-control" name="hours" id="hours"
                                            placeholder="Enter hours" required />
                                    </div>
                                </div>

                                <div class="col-md-6 col-6 pl-25 pr-25">
                                    <div class="mb-3">
                                        <label for="email-id-column">Rate *</label>
                                        <input type="number" class="form-control" name="rate" id="rate"
                                            placeholder="Enter rate" required />
                                    </div>
                                </div>

                                <div class="col-md-6 col-6 pl-25 pr-25">
                                    <div class="mb-3">
                                        <label for="email-id-column">Amount *</label>
                                        <input type="number" class="form-control" name="amount" id="amount"
                                            placeholder="Enter amount" required />
                                    </div>
                                </div>

                                <div class="col-md-6 col-6 pl-25 pr-25">
                                    <div class="mb-3">
                                        <label for="company-column">Remarks</label>
                                        <input type="text" class="form-control" name="remarks" id="remarks"
                                            placeholder="Enter remarks" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 pl-25 pr-25">
                                    <div class="mb-3">
                                        <label for="payment_date">Payment Date *</label>
                                        <input type="text" id="payment_date" name="payment_date" required class="form-control format-picker" placeholder="Select payment date" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="updatebtn">Update</button>
                <button type="submit" class="btn btn-success" id="savebtn">Save</button>
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
