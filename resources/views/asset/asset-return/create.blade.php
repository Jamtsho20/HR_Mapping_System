@extends('layouts.app')
@section('page-title', 'Asset Return')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
<div class="block-header block-header-default">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="return_no">Asset Return No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="return_no" value="" placeholder="Generating..." disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="return_date">Return Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="return_date" value="{{ old('return_date') }}">
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
                                        Unit*
                                    </th>
                                    <th>
                                        Description*
                                    </th>
                                    <th>
                                        Dzongkhag*
                                    </th>
                                    <th>
                                        Store*
                                    </th>
                                    <th>
                                        Condition Code*
                                    </th>
                                    <th>
                                        Remarks*
                                    </th>
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

                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="unit" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="text" name="description" class="form-control form-control-sm resetKeyForNew" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="mas_dzongkhag_id">
                                            <option value="" disabled selected hidden>Select</option>
                                            @foreach ($dzongkhags as $dzongkhag)
                                            <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->dzongkhag }}</option>
                                            @endforeach
                                        </select>

                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="store">
                                            <option value="" disabled selected hidden>Select</option>
                                            @foreach ($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="condition_code">
                                            <option value="" disabled selected hidden>Select</option>
                                            @foreach(config('global.asset_condition_codes') as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="remarks" class="form-control form-control-sm resetKeyForNew" disabled>
                                    </td>

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


@endsection