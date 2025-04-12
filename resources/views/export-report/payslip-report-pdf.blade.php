@extends('layouts.payslip-report-layout')
@section('content')
    @foreach ($payslips as $payslip)
        <div style="text-align: center;">
            <img style="width:600px; display: block; margin: 0 auto; border-bottom: 1px solid #000;"
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/letterhead.png'))) }}"
                alt="Letter Head">
        </div>

        <div class="payslip-container">
            <div class="text-ucase text-bold w-full text-center font-0_9">
                Pay Slip For The Month Of: {{ \Carbon\Carbon::parse($payslip->for_month)->translatedFormat('F Y') }}
            </div>
            {{-- employee details --}}
            <table class="borderless w-full font-0_9" style="padding: 10px 0 10px 0">
                <tbody>
                    <tr style="line-height: 0.9;">
                        <td style="width:25%; font-weight:bold">Employee Name :</td>
                        <td style="width:25%; text-align: left;">{{ $payslip->employee->name }}</td>
                        <td style="width:25%">Work Location :</td>
                        <td style="width:25%; text-align: left;">{{ $payslip->employee->empJob->office->name }}</td>
                    </tr>
                    <tr style="line-height: 0.9;">
                        <td>Job Title :</td>
                        <td style="text-align: left;">{{ $payslip->employee->empJob->designation->name }}</td>
                        <td>Payment Method :</td>
                        <td style="text-align: left;">{{ $payslip->employee->empJob->salary_disbursement_name }}</td>
                    </tr>
                    <tr style="line-height: 0.9;">
                        <td>Department :</td>
                        <td style="text-align: left;">{{ $payslip->employee->empJob->department->name }}</td>
                        <td>Bank Location :</td>
                        <td style="text-align: left;">{{ $payslip->employee->empJob->bank }}</td>
                    </tr>
                    <tr style="line-height: 0.9;">
                        <td style="width:25%">Grade :</td>
                        <td style="width:25%; text-align: left;">{{ $payslip->employee->empJob->gradeStep->name }}</td>
                        <td>Bank A/C :</td>
                        <td style="text-align: left;">{{ $payslip->employee->empJob->account_number }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- headings --}}
            <div class="lightborder-topbottom text-bold w-full text-center font-1_0">
                <table class="borderless w-full font-1_0">
                    <tbody>
                        <tr>
                            <td class="w-32 text-center">EARNINGS :</td>
                            <td class="text-center">DEDUCTIONS :</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- earnings --}}
            <div class="row-wrapper w-full">
                <div class="font-0_9 valign-top" style="padding-right: 50px">
                    <table class="w-full font-small col-height-mid font-0_9">
                        <tbody>
                            <tr>
                                <td class="w-half">Basic Pay: </td>
                                <td class="w-half text-right pr-10">
                                    {{ number_format($payslip->employee->empJob->basic_pay, 2) }}
                                </td>
                            </tr>
                            @foreach ($payslip->details['allowances'] as $key => $value)
                                <tr>
                                    <td class="w-half">{{ $key }}: </td>
                                    <td class="w-half" style="text-align: left; padding-left: 30px;">
                                        {{ number_format($value ?? '0', 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- deductions --}}
                <div class="font-0_9 valign-top" style="padding-right: 50px">
                    <table class="w-full font-small col-height-mid font-0_9">
                        <tbody>
                            <tr style="vertical-align: top;">
                                <td class="w-half" style="vertical-align: top;">
                                    <table class="w-full">
                                        <tbody>
                                            @php
                                                $deductions = collect($payslip->details['deductions']);
                                                $loans = $deductions->filter(
                                                    fn($value, $key) => str_contains(strtolower($key), 'loan'),
                                                );
                                                $others = $deductions->reject(
                                                    fn($value, $key) => str_contains(strtolower($key), 'loan'),
                                                );
                                                $totalDeduction = 0;
                                            @endphp
                                            @foreach ($others as $key => $value)
                                                <tr>
                                                    <td class="w-half">{{ $key }}:</td>
                                                    <td class="w-half pr-10">
                                                        {{ number_format($value ?? '0', 2) }}
                                                    </td>
                                                </tr>
                                                @php
                                                    $totalDeduction += $value ?? 0;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="font-0_9 valign-top">
                    <table class="w-full font-small col-height-mid font-0_9">
                        <tbody>
                            <tr style="vertical-align: top;">
                                <td class="w-half" style="vertical-align: top">
                                    <table class="w-full">
                                        <tbody>
                                            @foreach ($loans as $key => $value)
                                                <tr>
                                                    <td class="w-half">{{ $key }}:</td>
                                                    <td class="w-half pr-10">
                                                        {{ number_format($value ?? '0', 2) }}
                                                    </td>
                                                </tr>
                                                @php
                                                    $totalDeduction += $value ?? 0;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- totals --}}
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

            {{-- net pay --}}
            <div class="text-bold w-full text-center font-1_0">
                <table class="borderless w-full">
                    <tbody>
                        <tr>
                            <td class="w-full text-center">
                                <span> NET PAY</span> :
                                &nbsp;&nbsp;&nbsp; Nu. {{ number_format($payslip->details['net_pay'], 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="seal">
            <p>Manager<br>
                Human Resource and Administration<br>
                Department</p>
        </div>

        {{-- Page Break --}}
        <div class="page-break"></div>
    @endforeach
@endsection
