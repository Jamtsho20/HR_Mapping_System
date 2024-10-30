@extends('layouts.app')
@section('page-title', 'Transfer Claim')
@section('content')

<form action="{{ route('transfer-claim.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employeeid">Employee ID </label>
                        <input type="text" class="form-control" name="employee" value="{{ auth()->user()->emplusernameoyee_id }}"
                            placeholder="{{ auth()->user()->username }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Designation</label>
                        <input type="text" class="form-control" name="designation" value="" placeholder="{{isset( auth()->user()->empJob->designation->name )? auth()->user()->empJob->designation->name:'NA'}}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Department</label>
                        <input type="text" class="form-control" name="department" value="" placeholder="{{isset( auth()->user()->empJob->department->name )? auth()->user()->empJob->department->name:'NA'}}" disabled>
                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="form-group">
                        <label for="">Basic Pay</label>
                        <input type="text" class="form-control" name="basicpay" value="" placeholder="{{isset( auth()->user()->empJob->basic_pay )? auth()->user()->empJob->basic_pay:'NA'}}" disabled>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="transferclaim">Transfer Claim <span class="text-danger">*</span></label>
                        <select name="type" id="transferclaim" class="form-control form-control-sm" required>
                            <option value="" disabled selected>Select an option</option>s
                            @foreach(config('global.transfer_claim') as $key =>$value)
                            <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Current Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="currentlocation" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">New Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="newlocation" value="">
                    </div>
                </div>
                <div class="col-md-6" id="distanceField" style="display: none;">
                    <div class="form-group">
                        <label for="distance">Distance (KM) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="newlocation" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Amount Claimed <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="amtclaimed" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Attachment</label>
                        <input type="file" class="form-control" name="attachment" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        @include('layouts.includes.buttons', [
        'buttonName' => 'SUBMIT',
        'cancelUrl' => url('expense/transfer-claim'),
        'cancelName' => 'CANCEL'
        ])

    </div>
   
</form>
<script>
    document.getElementById('transferclaim').addEventListener('change', function() {
        var selectedValue = this.value;
        var distanceField = document.getElementById('distanceField');

        if (selectedValue === '2') {
            distanceField.style.display = 'block';
        } else {
            distanceField.style.display = 'none';
        }
    });
</script>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush