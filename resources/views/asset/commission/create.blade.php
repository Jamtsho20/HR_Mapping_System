@extends('layouts.app')
@section('page-title', 'Commission')
@section('content')
    <form action="{{ route('commission.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="block-header block-header-default">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="commission_no">Requisition No. <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="commission_no" name="commission_no" value="{{ old('commission_no') }}" placeholder="Generating..." readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="grn">GRN <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="grn" id="grn">
                                        <option value="" disabled selected hidden>Select Your Option</option>
                                        @foreach ($faItems as $faItem) 
                                            @foreach ($faItem->details as $item) 
                                                <option value="{{ $item->id }}">
                                                    {{ $item->grnItem->grn_no }}
                                                </option>
                                            @endforeach
                                        @endforeach
            
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="commission_date">Commission Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="commission_date" id="commission_date"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="employee">Employee Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="employee"
                                        value="{{ $empDetails->name }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="department">Department <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="department"
                                        value="{{ $empDetails->empJob->department->name ?? config('global.null_value') }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="file-uploader">
                                        <label for="file">Upload File</label>
                                        <div class="file-upload-box">
                                            <div class="box-title">
                                                <!-- <span class="file-instruction">Drag files here or</span> -->
                                                <span class="file-browse-button">Upload Files</span>
                                            </div>
                                            <input class="file-browse-input" type="file" multiple hidden
                                                name="attachments[]" id="attachment" class="form-control"
                                                accept="image/*,.pdf,.doc,.docx">

                                        </div>
                                        <ul class="file-list">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th width="3%" class="text-center">#</th>
                                        <th>Asset No*</th>
                                        <th>Item Description*</th>
                                        <th>UOM*</th>
                                        <th>Dzongkhag*</th>
                                        <th>Quantity*</th>
                                        <th>Date Place In Service*</th>
                                        <th>Site*</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td class="text-center">
                                            <a href="" class="delete-table-row btn btn-danger btn-sm"><i
                                                    class="fa fa-times"></i></a>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm resetKeyForNew select2" name="details[AAAAA][asset_no]" required />
                                            <option value="" disabled selected hidden>Select Your Option</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm resetKeyForNew select2" name="details[AAAAA][item_description]" required />
                                            <option value="" disabled selected hidden>Select Item</option>

                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="details[AAAAA][uom]" value="" class="form-control form-control-sm resetKeyForNew" readonly required />
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm resetKeyForNew select2" name="details[AAAAA][dzongkhag]" required />
                                            <option value="" disabled selected hidden>Select</option>

                                            </select>
                                        </td>
                                        
                                        <td>
                                            <input type="number" name="details[AAAAA][quantity]" class="form-control form-control-sm resetKeyForNew quantity-input" required />
                                        </td>
                                        <td>
                                            <input type="date" class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][date_placed_in_service]" required />
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm resetKeyForNew select2" name="details[AAAAA][site]" required />
                                                <option value="" disabled selected hidden>Select Site</option>
                                            </select>
                                        </td>
                                        <td>
                                            <textarea class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][remark]"></textarea>
                                        </td>
                                    </tr>

                                    <tr class="notremovefornew">
                                        <td colspan="8"></td>
                                        <td class="text-right">
                                            <a href="#" class="add-table-row btn btn-sm btn-info"
                                                style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
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
                        'cancelUrl' => url('asset/commission'),
                        'cancelName' => 'CANCEL',
                    ])

                    <input class="btn btn-info" type="reset" value="Reset">

                </div>

            </div>
        </div>
        </div>
    </form>

@endsection
@push('page_scripts')
    <script>
        
    </script>
@endpush
