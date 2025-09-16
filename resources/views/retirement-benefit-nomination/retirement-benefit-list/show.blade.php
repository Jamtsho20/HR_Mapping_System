@extends('layouts.app')
@section('page-title', 'Retirement Benefit Nomination Details')

@section('buttons')
<a href="{{ route('retirement-benefit-list.index') }}" class="btn btn-primary">
    <i class="fa fa-reply"></i> Back to List
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Personal Information Section -->
                @include('retirement-benefit-nomination.retirement-benefit-list.retirementlist',['employee' => $nomination->employee])

                <div class="mt-4">
                    <label class="h5"><strong>Retirement Benefit Nomination Details</strong></label>
                    <div class="table-responsive mt-2">
                        <table id="retirement_benefit" class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th width="20%">Name</th>
                                    <th width="20%">Relationship</th>
                                    <th width="20%">CID</th>
                                    <th width="20%">Percentage of Share</th>
                                    <th width="20%">Attachments</th>
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
                                    <td class="text-center">
                                        @if($detail->attachment)
                                            <a href="{{ asset($detail->attachment) }}" target="_blank" class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                        @else
                                            <span class="text-muted">No attachment</span>
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

            <!-- Remark Section -->
            <div class="card-body">
                <form action="{{ route('retirement-benefit-list.sendRetirementRemark', $nomination->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="remark"><strong>Remarks</strong></label>
                        <textarea name="remark" id="remark" rows="4" class="form-control" placeholder="Write your remarks here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">
                        <i class="fa fa-envelope"></i> Send Mail
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
@endpush
