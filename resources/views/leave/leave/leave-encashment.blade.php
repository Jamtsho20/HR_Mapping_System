@extends('layouts.app')
@section('page-title', 'Leave Encashment')
@section('content')

<form action="" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="encashment">Total Leaves For Encashment <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="encashment" placeholder="0.00" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="leave-encashment">Leave Eligible For Encashment <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="leave-encashment" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="apply-encashment">Leave Apply For Encashment <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="apply-encashment" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="encashed-amt">Encashed Amount <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="encashed-amt" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> SUBMIT</button>
            <a href="{{ url('leave/leave-apply') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
