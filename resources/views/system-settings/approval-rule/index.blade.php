@extends('layouts.app')
@section('page-title', 'Delegation')
@if ($privileges->create)
@section('buttons')
<a href="{{ url('system-setting/approval-rules/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>Add new</a>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-header">
        <div class="col-sm-4">
            <h5>Approval Rules</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="card">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @foreach($heads as $head)
                @php
                // Sanitize the name for safe use in id and data-bs-target attributes
                $sanitizedName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($head->name));
                @endphp
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link {{ $loop->first ? 'active' : '' }}"
                        id="tab-{{ $sanitizedName }}"
                        data-bs-toggle="pill"
                        data-bs-target="#content-{{ $sanitizedName }}"
                        type="button"
                        role="tab"
                        aria-controls="content-{{ $sanitizedName }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $head->name }}
                    </button>
                </li>
                @endforeach
            </ul>

            <div class="tab-content" id="pills-tabContent">
                @foreach($heads as $head)
                @php
                $sanitizedName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($head->name));
                @endphp
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                    id="content-{{ $sanitizedName }}"
                    role="tabpanel"
                    aria-labelledby="tab-{{ $sanitizedName }}">
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
                                    <th>Type</th>
                                    <th>Rule Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>BT</td>
                                    <td>Bhutan</td>
                                    <td>Bhutan</td>
                                    <td>Bhutan</td>
                                    <td>Bhutan</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-center text-danger">No Data found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush