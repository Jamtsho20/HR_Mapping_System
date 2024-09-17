@extends('layouts.app')
@section('page-title', 'Pay Heads')
@section('content')
@if ($privileges->create)
    @section('buttons')
    <a href="{{ route('pay-heads.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Pay Head</a>
    @endsection
@endif

<div class="block-header block-header-default">
     @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="payheads" class="form-control" value="{{ request()->get('payheads') }}"placeholder="Search">
        </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pay Heads</h3>
                </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="dataTables_length" id="responsive-datatable_length"
                                            data-select2-id="responsive-datatable_length">
                                            <label data-select2-id="26">
                                                Show
                                                <select class="select2">
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                                entries
                                            </label>
                                        </div>
                                            <div class="dataTables_scroll">
                                                <div class="dataTables_scrollHead"
                                                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                    <div class="dataTables_scrollHeadInner"
                                                        style="box-sizing: content-box; padding-right: 0px;">
                                                        <table
                                                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                            id="basic-datatable table-responsive">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th>
                                                                        Name
                                                                    </th>
                                                                    <th>
                                                                        Pay Head Type
                                                                    </th>
                                                                    <th>
                                                                        Account head
                                                                    </th>
                                                                    <th>
                                                                        Code
                                                                    </th>
                                                                    <th>
                                                                        Calculation Method
                                                                    </th>
                                                                    <th>
                                                                        On what is it calculated?
                                                                    </th>
                                                                    <th>
                                                                        Amount
                                                                    </th>
                                                                    <th>
                                                                        Created At
                                                                    </th>
                                                                    <th>
                                                                        Updated At
                                                                    </th>
                                                                    <th>
                                                                        Action
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($payHeads as $payHead)
                                                                    <tr>
                                                                        <td>{{ $payHead->name }}</td>
                                                                        <td>
                                                                            @if($payHead->payhead_type == 1)
                                                                                Allowance
                                                                            @elseif($payHead->payhead_type == 2)
                                                                                Deduction
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $payHead->accountHead->name ?? 'N/A' }}</td>
                                                                        <td>{{ $payHead->code }}</td>
                                                                        <td>
                                                                            @if($payHead->calculation_method == 1)
                                                                                Actual Amount
                                                                            @elseif($payHead->calculation_method == 2)
                                                                                Division Method
                                                                            @elseif($payHead->calculation_method == 3)
                                                                                On Pay Slab
                                                                            @elseif($payHead->calculation_method == 4)
                                                                                On Pay Group
                                                                            @elseif($payHead->calculation_method == 5)
                                                                                Percentage Method
                                                                            @elseif($payHead->calculation_method == 6)
                                                                                By Formula
                                                                            @elseif($payHead->calculation_method == 7)
                                                                                Employment Wise
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if($payHead->calculated_on == 1)
                                                                                Basic Pay
                                                                            @elseif($payHead->calculated_on == 2)
                                                                                Gross Pay
                                                                            @elseif($payHead->calculated_on == 3)
                                                                                Net Pay
                                                                            @elseif($payHead->calculated_on == 4)
                                                                                PIT Net Pay
                                                                            @elseif($payHead->calculated_on == 5)
                                                                                Lumpsum
                                                                            @elseif($payHead->calculated_on == 6)
                                                                                Pay Scale Base Pay
                                                                            @elseif($payHead->calculated_on == 7)
                                                                                By Formula
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $payHead->amount }}</td>
                                                                        <td>{{ $payHead->created_at ? $payHead->created_at->format('Y-m-d H:i:s') : ''  }}</td>
                                                                        <td>{{ $payHead->updated_at ? $payHead->updated_at->format('Y-m-d H:i:s') : '' }}</td>
                                                                        <td class="text-center">
                                                                            @if ($privileges->edit)
                                                                                <a href="{{ url('paymaster/pay-heads/' . $payHead->id . '/edit') }}"
                                                                                    data-name="{{ $payHead->name }}"
                                                                                    data-code="{{ $payHead->code }}"
                                                                                    data-payhead_type="{{ $payHead->payhead_type }}"
                                                                                    data-calculation_method="{{ $payHead->calculation_method }}"
                                                                                    data-calculated_on="{{ $payHead->calculated_on }}"
                                                                                    data-formula="{{ $payHead->formula }}"
                                                                                    class="edit-btn btn btn-sm btn-rounded btn-outline-success">
                                                                                    <i class="fa fa-edit"></i> EDIT
                                                                                </a>
                                                                            @endif
                                                                            @if ($privileges->delete)
                                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                                    data-url="{{ url('paymaster/pay-heads/' . $payHead->id) }}">
                                                                                    <i class="fa fa-trash"></i> DELETE
                                                                                </a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="7" class="text-center text-danger">No pay heads found</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div> 
</div>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush