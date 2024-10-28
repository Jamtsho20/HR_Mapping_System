@extends('layouts.app')
@section('page-title', 'Advance Loan Application Details')
@section('buttons')
<a href="{{ route('apply.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Advance Loan List</a>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-4">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Advance No</b> <a class="pull-right">{{ $advance->advance_no }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Date</b> <a class="pull-right">{{ \Carbon\Carbon::parse($advance->date)->format('Y-m-d') }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Advance Type</b> <a class="pull-right">{{ optional($advance->advanceType)->name ?? 'N/A' }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-8">
                        <ul class="list-group list-group-unbordered">
                            <!-- Include dynamic fields based on advance type -->
                            <li class="list-group-item">
                                <a class="pull-right">
                                    @if($advance->advanceType)
                                        @if($advance->advanceType->name === 'Advance to Staff')
                                            @include('advance-loan.apply.show.advance-to-staff')
                                        @elseif($advance->advanceType->name === 'DSA Advance(Tour)')
                                            @include('advance-loan.apply.show.dsa-advance')
                                        @elseif($advance->advanceType->name === 'Electricity Imprest Advance')
                                            @include('advance-loan.apply.show.electricity-imprest')
                                        @elseif($advance->advanceType->name === 'Imprest Advance')
                                            @include('advance-loan.apply.show.general-imprest')
                                        @elseif($advance->advanceType->name === 'Gadget EMI')
                                            @include('advance-loan.apply.show.gadget-emi')
                                        @elseif($advance->advanceType->name === 'SIFA LOAN')
                                            @include('advance-loan.apply.show.sifa-loan')
                                        @elseif($advance->advanceType->name === 'Salary Advance')
                                            @include('advance-loan.apply.show.salary-advance')
                                        @endif
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <ul class="list-group list-group-unbordered">

                    <li class="list-group-item">
                        <b>Approved By</b>
                        <a class="pull-right"></a>
                    </li>
                    <li class="list-group-item">
                        <b>Rejected By</b> <a class="pull-right"></a>

                    </li>


                </ul>
            </div>

        </div>
    </div>
</div>

@endsection

@push('page_scripts')
@endpush
