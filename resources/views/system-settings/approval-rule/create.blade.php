@extends('layouts.app')
@section('page-title', 'Approval Rules')
@section('content')

<form action="" method="POST">
    @csrf
    <div class="card">
        <div class="card-header ">
            <h3 class="card-title">Add Approval Rules</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-4">
                    <label for="mas_dzongkhag_id">For <span class="text-danger">*</span></label>
                    <select class="form-control" name="mas_dzongkhag_id" required="required">
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach($heads as $head)
                        <option value="{{$head->id}}">{{$head->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-4">
                    <label for="mas_dzongkhag_id">Type <span class="text-danger">*</span></label>
                    <select class="form-control" name="mas_dzongkhag_id" required="required">
                        <option value="" disabled selected hidden>Select your option</option>
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group col-4">
                    <label for="mas_dzongkhag_id">Rule Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="" required="required">


                </div>

                <div class="form-group col-4">
                    <label for="start_date">Start Date </label>
                    <input type="date" class="js-datepicker form-control js-datepicker" id="example-datepicker1" name="example-datepicker1" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                </div>
                <div class="form-group col-4">
                    <label for="end_date">End Date </label>
                    <input type="date" class="js-datepicker form-control js-datepicker" id="example-datepicker1" name="example-datepicker1" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                </div>

                <div class="form-group col-4">
                    <label for="">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status">
                        <option value="" disabled selected hidden>Select Status</option>
                        @foreach (config('global.status') as $key => $type)
                        <option value="{{ $key}}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </div>
    <a class="btn btn-primary" data-bs-target="#conditions" data-bs-toggle="modal" href=""> Conditions</a>

    @include('system-settings.approval-rule.modal.rules')
    <div class="row card">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="tbl_all_condition" class="table table-striped custom-table m-b-0 ">
                    <thead>
                        <tr>
                            <th style="width:40%">
                                Formula
                            </th>
                            <th style="width:20%">
                                Hierarchy Name
                            </th>
                            <th style="display:none;">
                                Hierarchy Max Level
                            </th>
                            <th style="width:20%">
                                Single User
                            </th>

                            <th style="width:15%">
                                Auto Approval
                            </th>
                            <th style="display:none;">
                                FYI-Frequency
                            </th>


                            <th style="width:5%">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> Save
        </button>
        <a href="{{ url('system-setting/approval-rules') }}" class="btn btn-danger "> CANCEL</a>
    </div> -->
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush