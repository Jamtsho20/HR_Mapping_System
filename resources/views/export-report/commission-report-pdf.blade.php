<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20mm 15mm 15mm 15mm;
        }

        html, body {
            height: 100%;
            margin:0;
            margin-top: 5mm;
            padding: 0;
            font-size: 10px;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .page-container {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
            margin: 5mm 15mm; /* Side margins */
        }

        .img-container {
            display: block;
            margin: 0 auto;
            width: 60%;
        }

        .title {
            text-align: center;
            padding: 10px;
        }

        .table-container {
            flex-grow: 1;
            overflow: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 4px;
            text-align: left;
            font-size: 8px;
            word-break: normal;
            white-space: normal; /* allow wrapping */
        }

        th {
            background-color: #f2f2f2;
            text-transform: capitalize;
        }

        footer {
            flex-shrink: 0;
            margin-top: 5px;
        }
    </style>

</head>

<body>
    <div class="img-container">
        @include('layouts.includes.letter-head')
    </div>
    <div class="page-container">
    @include('layouts.includes.generated-on-header', ['fromDate' => $fromDate, 'toDate' => $toDate])
        <hr>
    <h1 class="title">Commission Report</h1>
    <div class="table-container">
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    SL no
                </th>
                <th>
                    Applicant
                </th>
                <th>
                    Department
                </th>
                <th>
                    Comm No
                </th>
                <th>
                    Application Date
                </th>
                <th>
                    Serial No
                </th>
                <th>
                    Item Description
                </th>
                <th>
                    UOM
                </th>
                <th>
                    QTY
                </th>
                <th>
                    Amount (Nu.)
                </th>
                <th>
                    Dzongkhag
                </th>
                <th>
                    Site
                </th>
                <th>
                    Capitalization Date
                </th>
                <th>
                    Status
                </th>
                <th>
                    Approved By
                </th>
                {{-- <th>
                                                    Action
                                                </th> --}}


            </tr>
        </thead>
        <tbody>
            @php $count = 1; @endphp
            @forelse($commissions as $comm)
                @foreach ($comm->details as $detail)
                    <tr>
                        <td>{{ $count++ }}</td> {{-- Parent index --}}
                        <td>{{ $comm->employee->emp_id_name }}</td>
                        <td>{{ $comm->employee->empJob->department->name ?? config('global_null_value') }}</td>
                        <td>{{ $comm->transaction_no }}</td>
                        <td>{{ $comm->transaction_date }}</td>

                        <td>{{ $detail->receivedSerial?->requisitionDetail?->grnItemDetail?->item?->item_no .'-'. $detail->receivedSerial?->asset_serial_no }}</td>
                        {{-- <td>{{ $detail->receivedSerial->asset_description }}</td> --}}
                        <td title="{{ $detail->receivedSerial->asset_description }}">
                            {{ \Illuminate\Support\Str::limit($detail->receivedSerial?->asset_description, 75, '...') }}
                        </td>

                        <td>{{ $detail->receivedSerial?->requisitionDetail?->grnItemDetail?->item?->uom ?? '-' }}
                        </td>
                        <td class="text-right">{{$detail->receivedSerial?->quantity ?? 1 }}</td>
                        <td class="text-right">{{ $detail->receivedSerial?->amount }}</td>
                        <td>{{ $detail->dzongkhag?->dzongkhag }}</td>

                        <td>{{ $detail->site->name ?? '-' }}</td>

                        <td>{{ \Carbon\Carbon::parse($detail->date_placed_in_service)->format('d-M-Y') }}
                                                </td>
                        {{-- Parent-level status & approver repeated per row --}}
                        <td>{{ config("global.application_status.{$comm->status}", 'Unknown') }}
                        </td>
                        <td>{{ $comm->approvedBy->emp_id_name ?? '-' }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="16" class="text-danger text-center">No Data Found</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    </div>
    <hr>
     <footer>
            @include('layouts.includes.asset-report-footer')
        </footer>
    </div>
</body>

</html>
