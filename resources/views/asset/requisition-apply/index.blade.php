@extends('layouts.app')
@section('page-title', 'Requisition')
@section('content')

<div class="block-header block-header-default">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Requisition No</label>
                            <input type="text" class="form-control" name="name" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="requisition_type">Requisition Type</label>
                            <select class="form-control" name="requisition_type">
                                <option value="" disabled selected hidden>Select your option</option>
                                <option value="">Individual</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="requisition_date">Requisition Date</label>
                            <input type="date" class="form-control" name="requisition_date" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="need_by_date">Need By Date</label>
                            <input type="date" class="form-control" name="need_by_date" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="need_by_date">Employee Name</label>
                            <input type="text" class="form-control" name="need_by_date" value="{{$user->name}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="need_by_date">Department</label>
                            <input type="text" class="form-control" name="need_by_date" value="{{$user->empJob->department->name??'N/A'}}" disabled>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="requisition_type">Item Category</label>
                            <select class="form-control" name="requisition_type">
                                <option value="" disabled selected hidden>Select your option</option>
                                <option value="">FA.MISC</option>

                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>
                                        PO
                                    </th>
                                    <th>
                                        Item Description
                                    </th>
                                    <th>
                                        UOM
                                    </th>
                                    <th>
                                        Store
                                    </th>
                                    <th>
                                        Stock Status
                                    </th>
                                    <th>
                                        Quantity Required
                                    </th>
                                    <th>
                                        Dzongkhang
                                    </th>
                                    <th>
                                        Site Name
                                    </th>

                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td class="text-center">
                                        <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="po">
                                            <option value="" disabled selected hidden>Select</option>
                                            <option value="122">1212</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="item">
                                            <option value="" disabled selected hidden>Select</option>
                                            <option value="122">1212</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="UOM" class="form-control form-control-sm resetKeyForNew" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="store">
                                            <option value="" disabled selected hidden>Select</option>
                                            @foreach($stores as $store)
                                            <option value="{{$store->id}}">{{$store->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="stock_status" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="number" name="quantity" class="form-control form-control-sm resetKeyForNew">

                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="dzongkhag">
                                            <option value="" disabled selected hidden>Select</option>
                                            @foreach($dzongkhags as $dzongkhag)
                                            <option value="{{$dzongkhag->id}}">{{$dzongkhag->dzongkhag}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="store">
                                            <option value="" disabled selected hidden>Select </option>
                                            <option value="122">1212</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr class="notremovefornew">
                                    <td colspan="8"></td>
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
                'buttonName' => 'Create Requisition',
                'cancelUrl' => url('asset/requisition-apply') ,
                'cancelName' => 'CANCEL'
                ])

                <input class="btn btn-info" type="reset" value="Reset">

            </div>

        </div>
    </div>
</div>


@endsection