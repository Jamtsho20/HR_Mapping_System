@extends('layouts.app')
@section('page-title', 'Retirement Benefit Nomination Details')

@section('buttons')
<a href="{{ route('retirement-benefit-nomination.index') }}" class="btn btn-primary">
    <i class="fa fa-reply"></i> Back to List
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Personal Information Section -->
                @include('sifa.sifa-registration.forms.personalinfo')

                <label><strong>Retirement Benefit Nomination Details</strong></label>
                <br>
                <div class="table-responsive criteria">
                    <table id="retirement_benefit" class="table table-condensed table-striped table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th width="20%">Name</th>
                                <th width="20%">Relationship</th>
                                <th width="20%">CID</th>
                                <th width="20%">Percentage of Share</th>
                                <th width="20%">Attachments (CID/Birth Certificate)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nomination->details as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $detail->nominee_name }}</td>
                                <td>{{ $detail->relation_with_employee }}</td>
                                <td>{{ $detail->cid_number }}</td>
                                <td>{{ $detail->percentage_of_share }}%</td>
                                <td>
                                    @if($detail->attachment)
                                    <a href="{{ asset($detail->attachment) }}" target="_blank" class="btn btn-sm btn-primary">
                                        View Attachment
                                    </a>
                                    @else
                                    No attachment
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-danger">No nominee records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Document History</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.includes.approval-details', [
                    'approvalDetail' => $approvalDetail,
                    'applicationStatus' => $nomination->status,
                    ])

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
@endpush