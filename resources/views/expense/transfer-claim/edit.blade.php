@extends('layouts.app')
@section('page-title', 'Transfer Claim Update')
@section('content')

<form action="{{ route('transfer-claim.update',$transfer->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employeeid">Employee ID </label>
                        <input type="text" class="form-control" name="employee" value="{{$empIdName}}" disabled />

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Designation</label>
                        <input type="text" class="form-control" name="designation" value="" placeholder="{{isset( auth()->user()->empJob->designation->name ) ? auth()->user()->empJob->designation->name:'NA'}}" disabled>

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
                        <input type="text" id="transferclaim" class="form-control form-control-sm" name="transfer_claim" value="{{$transfer->type->name}}" disabled>

                        <input type="hidden" value="{{$transfer->transfer_claim}}" name="transfer_claim">
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        @if($transfer->type->id==2)
                        <tr>
                            <label for="distanceTravelled">Distance Travelled <span class="text-danger">*</span></label>
                            <input type="number" id="distanceTravelled" class="form-control form-control-sm" name="distance_travelled" value="{{$transfer->distance_travelled }}">
                        </tr>
                        @endif
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Current Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="current_location" value="{{old('current_location',$transfer->current_location)}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">New Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="new_location" value="{{old('new_location',$transfer->new_location)}}">
                    </div>
                </div>
                <div class="col-md-6" id="distanceField" style="display: none;">
                    <div class="form-group">
                        <label for="distance">Distance (KM) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="distance_travelled" value="{{old('distance_travelled',$transfer->distance_travelled)}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Amount Claimed <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="amount" value="{{old('amount',$transfer->amount)}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Attachment</label>
                        <input type="file" class="form-control" name="attachment">
                        @php
                        $attachments = json_decode($transfer->attachment); // Decode the JSON
                        @endphp

                        @if(!empty($attachments))
                        @foreach($attachments as $attachment)
                        <br>
                        <a href="{{ asset($attachment) }}" class="btn-sm btn-primary" target="_blank">
                            <i class="fa fa-file-pdf-o text-secondary" aria-hidden="true"></i>
                            &nbsp; Attachment
                        </a>
                        @endforeach
                        @else
                        <span>No attachments available.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('expense/apply-expense'),
            'cancelName' => 'CANCEL'
            ])

        </div>
    </div>


</form>
<script>
    document.getElementById('transferclaim').addEventListener('change', function() {
        var selectedValue = this.value;
        var distanceField = document.getElementById('distanceField');

        if (selectedValue === 'Carriage Charge') {
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
