@extends('layouts.app')
@section('page-title', 'Cancellation')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="leave_type" class="form-control" value="{{ request()->get('leave_type') }}"
                placeholder="Search">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                <a href="#" data-toggle="modal" data-target="#create-encashment" class="btn btn-sm btn-primary"> Leave Balance</a>
            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>From Date</th>
                    <th>To date</th>
                    <th>No of Days</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
              
                <tr>
                    <td>1</td>
                    <td>Kinga</td>
                    <td>Casual</td>
                    <td>02/08/2022</td>
                    <td>02/08/2022</td>
                    <td>0.5</td> 
                    <td><span class="badge badge-success">Approved</span></td>
                    <td class="text-center">
                        @if ($privileges->edit)
                        <a href="" data-short_name="" data-name="" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url=""><i class="fa fa-trash"></i> DELETE</a>
                        @endif
                    </td>
                </tr>
             
                <tr>
                    <td colspan="8" class="text-center text-danger">No Data found</td>
                </tr>
            
            </tbody>
        </table>
    </div>

</div>



@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush