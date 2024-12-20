@extends('layouts.app')
@section('page-title', 'Leave Encashment')
@section('content')

<form action="{{ url('leave/leave-encashment') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            @if(session('alert'))
                <div  style="color: red; ">
                    {{ session('alert') }}
                </div>
            @endif
        @if(!empty($message))
        <div style="color: red; ">
                    *{{ $message }}
                </div>
        @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="encashment">Total Leaves For Encashment <span class="text-danger"></span></label>
                        <input type="text" class="form-control" name="earned_leave_balance" placeholder="0.00" value="{{ $earnedLeaveBalance}}" readonly required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="leave-encashment">Leave Eligible For Encashment <span class="text-danger"></span></label>
                        <input type="text" class="form-control" name="required_leave_balance" value="{{ $requiredBalance }}" readonly required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="apply-encashment">Leave Apply For Encashment <span class="text-danger"></span></label>
                        <input type="text" class="form-control" name="leave_applied_for_encashment" value="{{$earnedLeaveEncahsment}}" readonly required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="encashed-amt">Encashed Amount <span class="text-danger"></span></label>
                        <input type="text" class="form-control" name="encashment_amount" value="{{ $encashedAmount }}" readonly required>
                    </div>
                </div>


            </div>
        </div>
        <div class="card-footer">

        @if($applyFlag && empty($message))
                <button type="submit" class="btn btn-primary">

            @else
                <button type="button" class="btn btn-secondary" disabled>
            @endif
            <i class="fa fa-upload"></i> SUBMIT
            </button>
            <a href="{{ url('leave/leave-apply') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>

        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
