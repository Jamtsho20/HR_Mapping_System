@extends('layouts.app')
@section('page-title', 'Showing Expense Details')
@section('buttons')
<a href="{{ url('expense/apply-expense/')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Expense
    List</a>
@endsection
@section('content')

<div class="row">
    <!-- Personal Details -->
    <div class="col-md-12">
        <div class="card">

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">

                            <li class="list-group-item">
                                <b>Employee ID</b> <a class="pull-right">{{ $expense->employee->username }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Expense Type</b> <a class="pull-right">{{ $expense->expenseType->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Date</b> <a class="pull-right">{{ $expense->date }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Expense Amount</b> <a class="pull-right">{{ $expense->expense_amount }}</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Description</b>
                                <a class="pull-right">{{ $expense->description }}</a>
                            </li>

                            <li class="list-group-item">
                                <b>File</b>
                                <a href="{{ asset($expense->file) }}" class="btn-sm btn-primary pull-right"
                                    target="_blank"><i class="fa fa-file-pdf-o text-secondary" aria-hidden="true"></i>
                                    &nbsp; attachment</a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b> <a class="pull-right"> @if($expense->status == 1)
                                    <span class="badge bg-primary">Applied</span>
                                    @elseif($expense->status == 2)
                                    <span class="badge bg-summary">Approved</span>
                                    @elseif($expense->status == 0)
                                    <span class="badge bg-warning">Cancelled</span>
                                    @elseif($expense->status == -1)
                                    <span class="badge bg-danger">Rejected</span>
                                    @else
                                    <span class="badge bg-secondary">Unknown Status</span>
                                    @endif</a>
                            </li>

                        </ul>
                    </div>


                </div>
                <!-- conveyance -->
                <br>
                @if($expense->mas_expense_type_id == 1)
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">

                            <li class="list-group-item">
                                <b>Travel Type</b>
                                <a class="pull-right">{{ $expense->travel_type}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Travel Mode</b> <a class="pull-right">{{ $expense->travel_mode}}</a>

                            </li>
                            <li class="list-group-item">
                                <b>Travel From Date</b>
                                <a class="pull-right">{{ $expense->travel_from_date}}</a>

                            </li>
                            <li class="list-group-item">
                                <b>Travel To Date</b>
                                <a class="pull-right">{{ $expense->travel_to_date}}</a>

                            </li>

                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">


                            <li class="list-group-item">
                                <b>Travel From</b>
                                <a class="pull-right">{{ $expense->travel_from}}</a>

                            </li>
                            <li class="list-group-item">
                                <b>Travel To </b>
                                <a class="pull-right">{{ $expense->to}}</a>

                            </li>
                            <li class="list-group-item">
                                <b>Travel Distance </b>
                                <a class="pull-right">{{ $expense->travel_distance}}</a>

                            </li>

                        </ul>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer">
                <ul class="list-group list-group-unbordered">

                    <li class="list-group-item">
                        <b>Approved By</b>
                        <a class="pull-right">{{ $expense->travel_type}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Rejected By</b> <a class="pull-right">{{ $expense->travel_mode}}</a>

                    </li>


                </ul>
            </div>


        </div>
    </div>
    <!-- Expense Job related -->
</div>

@endsection
@push('page_scripts')

@endpush