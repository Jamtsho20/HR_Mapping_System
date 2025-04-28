@extends('layouts.app')
@section('page-title', 'Asset Transfer')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
<div class="block-header block-header-default">
    <div class="col-lg-12">
        <div class="card">
            {{-- <div class="card-header"></div> --}}
            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="transfer_no">Transfer No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="transfer_no" value="" placeholder="Generating..." disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="transfer_type">Transfer Type<span class="text-danger">*</span></label>
                            <select class="form-control" name="transfer_type">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('transfer_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="commission_date">Transfer Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="commission_date" value="{{ old('commission_date') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="employee">From Employee<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="from_employee" value="{{ LoggedInUserEmpIdName() ?? config('global.null_value') }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="new_employee">To Employee<span class="text-danger">*</span></label>
                            <select name="to_employee" class="form-control select2 select2-hidden-accessible"
                                data-placeholder="Select your option" tabindex="-1">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('to_employee') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->emp_id_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="from_site">From Site<span class="text-danger">*</span></label>
                            <select class="form-control select2 select2-hidden-accessible" name="from_site">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}" {{ old('from_site') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="to_site">To Site<span class="text-danger">*</span></label>
                            <select class="form-control select2 select2-hidden-accessible" name="to_site">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}" {{ old('to_site') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="commission_date">Reason of Transfer<span class="text-danger">*</span></label>
                            {{-- <input type="text" class="form-control" name="reason_of_transfer" value="{{ old('reason_of_transfers') }}"> --}}
                            <textarea class="form-control" name="reason_of_transfer" rows="2">{{ old('reason_of_transfer') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="file-uploader">
                                <label for="file">Attachment (s)</label>
                                <div class="file-upload-box">
                                    <div class="box-title">
                                        <!-- <span class="file-instruction">Drag files here or</span> -->
                                        <span class="file-browse-button">Upload Files</span>
                                    </div>
                                    <input class="file-browse-input" type="file" multiple hidden name="attachments[]"
                                        id="attachment" class="form-control" accept="image/*,.pdf,.doc,.docx" />

                                </div>
                                <ul class="file-list">

                                </ul>
                            </div>
                        </div>

                    </div>


                    <div class="table-responsive">
                        <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>
                                        Asset No.*
                                    </th>
                                    <th>
                                        Category*
                                    </th>
                                    <th>
                                        Description*
                                    </th>
                                    {{-- <th>
                                        Asset Key
                                    </th> --}}
                                    <th>
                                        Asset Type*
                                    </th>
                                    <th>
                                        UOM*
                                    </th>
                                    <th>
                                        QTY*
                                    </th>
                                    <th>
                                        Date Placed in Service
                                    </th>
                                    {{-- <th>
                                        Property Type
                                    </th> --}}


                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td class="text-center">
                                        <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="asset_no">
                                            <option value="" disabled selected hidden>Select</option>
                                            @foreach($assetNos as $assetNo)
                                                <option value="{{ $assetNo->id }}">{{ $assetNo->asset_serial_no }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="category" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="text" name="description" class="form-control form-control-sm resetKeyForNew" disabled>
                                    </td>
                                    {{-- <td>
                                        <input type="text" name="asset_key" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td> --}}
                                    <td>
                                        <input type="text" name="asset_type" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="text" name="uom" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="number" name="quantity" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="date" name="date_placed_in_service" class="form-control form-control-sm resetKeyForNew">

                                    </td>
                                    {{-- <td>
                                        <input type="property_type" name="unit" class="form-control form-control-sm resetKeyForNew">

                                    </td> --}}

                                </tr>

                                <tr class="notremovefornew">
                                    <td colspan="7"></td>
                                    <td class="text-right">
                                        <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>

            </div>
            <div class="card-footer">
                @include('layouts.includes.buttons', [
                'buttonName' => 'Submit',
                'cancelUrl' => url('asset/asset-transfer') ,
                'cancelName' => 'CANCEL'
                ])

                {{-- <input class="btn btn-info" type="reset" value="Reset"> --}}

            </div>

        </div>
    </div>
</div>
@include('layouts.includes.alert-message')

@endsection