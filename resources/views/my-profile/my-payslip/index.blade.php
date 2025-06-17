@extends('layouts.app')
@section('page-title', 'My PaySlips')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">PaySlips</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                        <tr>
                            <th>Year</th>
                            <th>Month</th>
                            <th>File</th>
                        </tr>
                        @forelse ($payslips as $payslip)
                        <tr>
                            <td>{{ $payslip['year'] }}</td>
                            <td>{{ $payslip['month'] }}</td>
                            <td><a href="{{ url('/payslips/view/' . urlencode($payslip['filename'])) }}" target="_blank">{{ $payslip['filename'] }}</a></td>
                        </tr>
                        @empty
                        <tr>
                            <td span="3" class="text-danger text-center">No payslips found</td>
                        </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">