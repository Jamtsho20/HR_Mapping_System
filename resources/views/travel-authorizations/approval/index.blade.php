@extends('layouts.app')
@section('page-title', 'Advance Approval')
@section('content')


<div class="block">
    <div class="block-header block-header-default">
    @component('layouts.includes.filter')

    <div class="col-4 form-group">
        <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request()->get('from_date') }}"
            placeholder="Start Date">
    </div>
    <div class="col-4 form-group">
        <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request()->get('to_date') }}"
            placeholder="End Date">
    </div>
    <div class="col-4 form-group">
        <select class="form-control" id="mode_of_travel" name="mode_of_travel" onchange="displaySelectedValue()">
            <option value="" disabled selected hidden>Select Mode of Travel</option>
            @foreach(config('global.travel_modes') as $key => $label)
                <option value="{{ $key }}" >{{ $label }}</option>
            @endforeach
        </select>
    </div>

    @endcomponent
        <div class="block-options">
            <div class="row" style="float:right">
                <div class="col-6 ">
                    <div class="btn-group mt-2 mb-2">
                        <button type="button" class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown">
                            Approval Status
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="javascript:void(0);">Pending</a></li>
                            <li><a href="javascript:void(0);">Approved</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="block-content">
        <div class="block-options">
            <div class="col-sm-8">
                <h5>Travel Authorization Approval</h5>
            </div>
            <div class="col-sm-6">
                <input class="btn-sm btn-success buttonsubmit" type="button" id="btn_approved" data-value="approve" value="Approve">
                <input class="btn-sm  btn-danger buttonsubmit " data-value="reject" type="button" value="Reject" id="btn_reject">
            </div>
        </div>
        <br>
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="dataTables_length" id="responsive-datatable_length" data-select2-id="responsive-datatable_length">
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
                                            <div class="dataTables_scrollHead" style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; padding-right: 0px;">
                                                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>#</th>
                                                            <th>START DATE</th>
                                                            <th>END DATE</th>
                                                            <th>FROM</th>
                                                            <th>TO</th>
                                                            <th>MODE OF TRAVEL</th>
                                                            <th>ESTIMATED EXPENSES</th>
                                                            <th>ADVANCE REQUIRED</th>
                                                            <th>STATUS</th>
                                                            <th>ACTION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($travelAuthorizations as $travelAuthorization)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $travelAuthorization->from_date }}</td>
                                                            <td>{{ $travelAuthorization->to_date }}</td>
                                                            <td>{{ $travelAuthorization->from_location }}</td>
                                                            <td>{{ $travelAuthorization->to_location }}</td>
                                                            <td>{{ config('global.travel_modes')[$travelAuthorization->mode_of_travel] ?? 'Unknown' }}</td> 
                                                            <td>{{ $travelAuthorization->estimated_travel_expenses }}</td>
                                                            <td>{{ $travelAuthorization->advance_amount }}</td>
                                                            
                                                            <td>
                                                                @if($travelAuthorization->status == 1)
                                                                <span class="badge bg-primary">Applied</span>
                                                                @elseif($travelAuthorization->status == 2)
                                                                <span class="badge bg-summary">Approved</span>
                                                                @elseif($travelAuthorization->status == 0)
                                                                <span class="badge bg-warning">Cancelled</span>
                                                                @elseif($travelAuthorization->status == -1)
                                                                <span class="badge bg-danger">Rejected</span>
                                                                @else
                                                                <span class="badge bg-secondary">Unknown Status</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($privileges->view)
                                                                <a href="{{ route('apply-travel-authorization.show', $travelAuthorization->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                                @endif
                                                                @if ($privileges->edit)
                                                                <a href="{{ route('apply-travel-authorization.edit', $travelAuthorization->id) }}" class=" btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                    <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('travel-authorization/apply-travel-authorization/' . $travelAuthorization->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center text-danger">No travel authorization found</td>
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

</div>




@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush