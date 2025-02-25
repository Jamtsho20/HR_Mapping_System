@extends('layouts.payslip-layout')
@section('content')
    <div class="lightborder-topbottom text-ucase text-bold w-full text-center font-0_9">
        {{ \Carbon\Carbon::parse(request()->get('year', now()->format('Y-m')))->translatedFormat('F Y') }}
    </div>
    <table class="borderless w-full font-0_9">
        <tbody>
            @foreach ($payslips as $payslip)
                <tr style="line-height: 0.9;">
                    <td style="width:25%">Employee Name :</td>
                    <td style="width:25%; text-align: left;">{{ $payslip->employee->name }}

                    <td style="width:25%">Grade :</td>
                    <td style="width:25%; text-align: left;">{{ $payslip->employee->empJob->gradeStep->name }}</td>
                </tr>
                <tr style="line-height: 0.9;">
                    <td>Department :</td>
                    <td style="text-align: left;">{{ $payslip->employee->empJob->department->name }}</td>
                    <td>Bank Name :</td>
                    <td style="text-align: left;">{{ $payslip->employee->empJob->bank }}</td>
                </tr>
                <tr style="line-height: 0.9;">
                    <td>Job Title :</td>
                    <td style="text-align: left;">{{ $payslip->employee->empJob->designation->name }}</td>
                    <td>Bank A/C :</td>
                    <td style="text-align: left;">{{ $payslip->employee->empJob->account_number }}</td>
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
                        <td class="w-half text-right pr-10">{{ number_format($payslip->employee->empJob->basic_pay, 2) }}
                        </td>
                    </tr>
                    @foreach ($payslip->details['allowances'] as $key => $value)
                        <tr>
                            <td class="w-half">{{ $key }}: </td>
                            <td class="w-half" style="text-align: right; padding-left: 60px;">
                                {{ number_format($value ?? '0', 2) }}</td>
                        </tr>
                    @endforeach


                </tbody>
            </table>

        </div>

        <div class="borderonlyleft font-0_9 valign-top">
            <table class="w-full font-small col-height-mid font-0_9">
                <tbody>
                    @php
                        // Separate loan-related and other deductions
                        $deductions = collect($payslip->details['deductions']);
                        $loans = $deductions->filter(fn($value, $key) => str_contains(strtolower($key), 'loan'));
                        $others = $deductions->reject(fn($value, $key) => str_contains(strtolower($key), 'loan'));

                        // Merge loans first, then others
                        $sortedDeductions = $others->merge($loans)->chunk(ceil($deductions->count() / 2));

                        $totalDeduction = 0;
                    @endphp
                    <tr>
                        @foreach ($sortedDeductions as $column)
                            <td class="w-half">
                                <table class="w-full">
                                    @foreach ($column as $key => $value)
                                        <tr>
                                            <td class="w-half">{{ $key }}:</td>
                                            <td class="w-half" style="text-align: right; padding-left: 30px;">
                                                {{ number_format($value ?? '0', 2) }}
                                            </td>
                                        </tr>
                                        @php
                                            $totalDeduction += $value ?? 0;
                                        @endphp
                                    @endforeach
                                </table>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="lightborder-topbottom text-bold w-full text-center font-1_0">
            <table class="borderless w-full">
                <tbody>
                    <tr>
                        <td class="w-half">&nbsp; &nbsp; &nbsp; &nbsp;Gross Pay:
                            &nbsp;&nbsp;&nbsp;{{ number_format($payslip->details['gross_pay'], 2) }}</td>
                        <td class="w-half">Total Deductions: &nbsp;&nbsp;&nbsp;{{ number_format($totalDeduction, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class=" text-bold w-full text-center font-1_0">
            <table class="borderless w-full">
                <tbody>
                    <tr>
                        <td class="w-full text-center"><span style="color:#CC3399">Net Pay</span> :
                            &nbsp;&nbsp;&nbsp;{{ number_format($payslip->details['net_pay'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endforeach
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
