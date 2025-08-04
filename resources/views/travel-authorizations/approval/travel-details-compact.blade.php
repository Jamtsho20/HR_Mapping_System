<div class="container-fluid">
    <table style="width:100%;" class="simple-table">
        <tbody>
            <tr>
                <th style="width:35%;">Travel Authorization Number<span class="pull-right d-none d-sm-block">:</span></th>
                <td style="padding-left:25px;">{{ $travelAuthorization->transaction_no }}</td>
            </tr>
            <tr>
                <th>Date<span class="pull-right d-none d-sm-block">:</span></th>
                <td style="padding-left:25px;">{{ \Carbon\Carbon::parse($travelAuthorization->date)->format('d-M-Y') }}</td>
            </tr>
            <tr>
                <th>Travel Type<span class="pull-right d-none d-sm-block">:</span></th>
                <td style="padding-left:25px;">{{ $travelAuthorization->travelType->name }}</td>
            </tr>
            <tr>
                <th>Estimated Expense Amount<span class="pull-right d-none d-sm-block">:</span></th>
                <td style="padding-left:25px;">{{ formatAmount($travelAuthorization->estimated_travel_expenses) }}</td>
            </tr>
            <tr>
                <th>Total Number of Day(s)<span class="pull-right d-none d-sm-block">:</span></th>
                <td style="padding-left:25px;">{{ $travelAuthorization->total_days ?? '-' }} day(s)</td>
            </tr>
        </tbody>
    </table>

    <div class="table-responsive mt-4">
        <table class="table table-condensed table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Number of Days</th>
                    <th>From Location</th>
                    <th>To Location</th>
                    <th>Mode of Travel</th>
                    <th colspan="2">Purpose</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($travelAuthorization->details as $detail)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($detail->from_date)->format('d-M-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail->to_date)->format('d-M-Y') }}</td>
                        <td>{{ $detail->number_of_days ?? '-' }}</td>
                        <td>{{ $detail->from_location }}</td>
                        <td>{{ $detail->to_location }}</td>
                        <td>{{ config('global.travel_modes')[$detail->mode_of_travel] ?? 'Unknown' }}</td>
                        <td colspan="2">{{ $detail->purpose }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
