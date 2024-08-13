@extends('layouts.app')
@section('page-title', 'Create Leave Policy')
@section('content')
<div class="block">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form>

                        <div class="card">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="leave-policy-tab" data-bs-toggle="pill" data-bs-target="#leave-policy" type="button" role="tab" aria-controls="leave-policy" aria-selected="true">Leave Policy</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link disabled" id="leave-plan-tab" data-bs-toggle="pill" data-bs-target="#leave-plan" type="button" role="tab" aria-controls="leave-plan" aria-selected="false" disabled>Leave Plan</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link disabled" id="year-end-tab" data-bs-toggle="pill" data-bs-target="#year-end" type="button" role="tab" aria-controls="year-end" aria-selected="false" disabled>Year End Processing</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link disabled" id="summary-tab" data-bs-toggle="pill" data-bs-target="#summary" type="button" role="tab" aria-controls="summary" aria-selected="false" disabled>Summary</button>
                                </li>
                            </ul>

                            <!-- Leave Policy Tab -->
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="leave-policy" role="tabpanel" aria-labelledby="leave-policy-tab">
                                    <div class="card-body">
                                        @include('leave.leave-policy.forms.leave-policy')
                                        <div class="card-footer float-end">
                                            <button type="button" class="btn btn-primary" id="saveAndContinue"><i class="fa fa-upload" disabled></i> Save and Continue</button>
                                            <a href="" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Leave Plan-->
                                <div class="tab-pane fade" id="leave-plan" role="tabpanel" aria-labelledby="leave-plan-tab">
                                    <div class="card-body">
                                        @include('leave.leave-policy.forms.leave-plan')
                                        <div class="card-footer float-end">
                                            <button type="button" class="btn btn-primary" id="saveAndContinue"><i class="fa fa-upload" disabled></i> Save and Continue</button>
                                            <a href="" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
                                        </div>
                                    </div>


                                </div>
                                <!-- region-->
                                <div class="tab-pane fade" id="year-end" role="tabpanel" aria-labelledby="year-end-tab">
                                    <div class="row">
                                        <div class="col-3">
                                            <label style="float:left">Show &nbsp;</label>
                                            <div class="dataTables_length" id="tbl_attendancesheet_length" style="float:left">
                                                <select name="tbl_attendancesheet_length" aria-controls="tbl_attendancesheet" class="form-control">
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                            </div>
                                            &nbsp;
                                            <label>entries</label>
                                        </div>

                                        <div class="col-3">
                                            <input type="text" name="search" class="form-control" value="" placeholder="Search">
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <table class="table table-bordered table-sm table-striped">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Code</th>
                                                    <th>Region Name</th>
                                                    <th>Country</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- dzongkhag-->
                                <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                                    <h4 class="font-w400">dzongkhag</h4>
                                    <p>...</p>
                                </div>
                                <!-- store location-->
                                <div class="tab-pane fade" id="pills-store" role="tabpanel" aria-labelledby="pills-store-tab">
                                    <h4 class="font-w400">Store Location</h4>
                                    <p>...</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
    $(document).ready(function() {
        // Disable tab switching on hover and click if the tab is disabled
        $('.nav-link.disabled').on('click', function(event) {
            event.preventDefault();
        }).on('mouseenter', function() {
            $(this).css('cursor', 'not-allowed');
        });

        // Handle the Save and Continue button
        $('#saveAndContinue').on('click', function() {
            // Validate the form
            var isValid = true;
            $('#leave-policy').find('input, select, textarea').each(function() {
                if ($(this).prop('required') && $(this).val() === '') {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (isValid) {
                // Enable the next tab
                var currentTab = $('#pills-tab .nav-link.active');
                var nextTab = currentTab.parent().next().find('.nav-link');
                if (nextTab.length) {
                    nextTab.removeClass('disabled').removeAttr('disabled').tab('show');
                }
            } else {
                alert('Please fill in all required fields.');
            }
        });
    });
</script>


@include('layouts.includes.delete-modal')
@endsection