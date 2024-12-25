@extends('layouts.payslip-layout')
@section('content')
    <div class="lightborder-topbottom text-ucase text-bold w-full text-center font-0_9">
        {{ date_format(date_create($paySlip->for_month), 'F, Y') }}
    </div>
    <table class="borderless w-full font-0_9">
        <tbody>
            <tr style="line-height: 0.9;">
                <td style="width:25%">Employee Name :</td>
                <td style="width:25%; text-align: left;">{{ $employee->first_name }} {{ $employee->middle_name }}
                    {{ $employee->last_name }}</td>
                <td style="width:25%">Grade :</td>
                <td style="width:25%; text-align: left;">{{ $employee->empJob->gradeStep->name }}</td>
            </tr>
            <tr style="line-height: 0.9;">
                <td>Department :</td>
                <td style="text-align: left;">{{ $employee->empJob->department->name }}</td>
                <td>Bank Name :</td>
                <td style="text-align: left;">{{ $employee->empJob->bank }}</td>
            </tr>
            <tr style="line-height: 0.9;">
                <td>Job Title :</td>
                <td style="text-align: left;">{{ $employee->empJob->designation->name }}</td>
                <td>Bank A/C :</td>
                <td style="text-align: left;">{{ $employee->empJob->account_number }}</td>
            </tr>
        </tbody>
    </table>
    <div class="lightborder-topbottom text-bold w-full text-center font-1_0">
        <table class="borderless w-full font-1_0">
            <tbody>
                <tr>
                    <td class="w-32 blue-text text-center">Earnings</td>
                    <td class="blue-text text-center">Deductions</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row-wrapper w-full">
        {{-- <div class="borderonlyright font-0_9 valign-top"> --}}
        <div class="font-0_9 valign-top">
            <table class="w-full font-small col-height-mid font-0_9">
                <tbody>
                    <tr>
                        <td class="w-half">Basic Pay: </td>
                        <td class="w-half text-right pr-10">{{ number_format($paySlip->basic_pay, 2) }}</td>
                    </tr>
                    @foreach ($allowances as $allowance)
                        @php
                            $column = str_replace(' ', '_', $allowance->name);
                            $displayName = strlen($allowance->name) > 10 ? $allowance->code : $allowance->name;
                        @endphp
                        <tr>
                            <td class="w-half">{{ $displayName }}: </td>
                            <td class="w-half" style="text-align: right; padding-left: 50px;">{{ number_format($paySlip->$column, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @php
            $deductionsTotal = 0;
        @endphp
        <div class="borderonlyleft font-0_9 valign-top">
            <table class="w-full font-small col-height-mid font-0_9">
                <tbody>
                    @foreach ($deductions as $deduction)
                        @php
                            $column = str_replace(' ', '_', $deduction->name);
                            $displayName = strlen($deduction->name) > 10 ? $deduction->code : $deduction->name;
                        @endphp
                        <tr>
                            <td class="w-half">{{ $displayName }}: </td>
                            <td class="w-half" style="text-align: right; padding-left: 50px;">{{ number_format($paySlip->$column, 2) }}</td>
                            @php
                                $deductionsTotal += $paySlip->$column ?? 0;
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="borderless font-0_9 valign-top">
            <table class="w-full font-small col-height-mid font-0_9">
                <tbody>
                    @foreach ($deductions1 as $deduction)
                        @php
                            $column = str_replace(' ', '_', $deduction->name);
                            $displayName = strlen($deduction->name) > 10 ? $deduction->code : $deduction->name;
                        @endphp
                        <tr>
                            <td class="w-half">{{ $displayName }}: </td>
                            <td class="w-half" style="text-align: right; padding-left: 120px;">{{ number_format($paySlip->$column, 2) }}</td>
                            @php
                                $deductionsTotal += $paySlip->$column ?? 0;
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="lightborder-topbottom text-bold w-full text-center font-1_0">
        <table class="borderless w-full">
            <tbody>
                <tr>
                    <td class="w-half">&nbsp; &nbsp; &nbsp; &nbsp;Gross Pay:
                        &nbsp;&nbsp;&nbsp;{{ number_format($paySlip->gross_pay, 2) }}</td>
                    <td class="w-half">Total Deductions: &nbsp;&nbsp;&nbsp;{{ number_format($deductionsTotal, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class=" text-bold w-full text-center font-1_0">
        <table class="borderless w-full">
            <tbody>
                <tr>
                    <td class="w-full text-center"><span style="color:#CC3399">Net Pay</span> :
                        &nbsp;&nbsp;&nbsp;{{ number_format($paySlip->net_pay, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="lightborder-topbottom w-full text-center">
        <table class="borderless w-full font-small" style="margin: 0; padding: 0;">
            <tbody>
                <tr>
                    <td style="width: 100%; padding: 0; margin: 0;">
                        <small style="display: block; width: 100%;">
                            This payslip is system generated. In case of any discrepancies in the payslip,
                            kindly report at erp.engineer.mis@tashicell.com. Thank You.
                        </small>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
