<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Return Report</title>
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
    <h1 class="title">Asset Return Report</h1>
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
                    Return No
                </th>
                <th>
                    Application Date
                </th>
                <th>
                    Asset No
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
                    From Employee
                </th>
                <th>
                    To Store
                </th>
                <th>
                    Capitalization Date
                </th>
                <th>
                    Reason of return
                </th>
                <th>
                    Return Acknowledgement
                </th>
                <th>
                    Status
                </th>
                <th>
                    Approved By
                </th>


            </tr>
        </thead>
        <tbody>
            @php $count = 1; @endphp
            @forelse($assetReturnApplication as $return)
                @foreach ($return->details as $detail)
                    <tr>
                        <td>{{ $count++ }}</td> {{-- Parent index --}}
                        <td>{{ $return->employee->emp_id_name }}</td>
                        <td>{{ $return->employee->empJob->department->name ?? config('global.null_value')  }}</td>
                        <td>{{ $return->transaction_no }}</td>
                        <td>{{ $return->transaction_date }}</td>

                        {{-- Detail-specific data --}}
                        <td>{{ $detail->receivedSerial?->requisitionDetail?->grnItemDetail?->item?->item_no .'-'. $detail->receivedSerial?->asset_serial_no }}</td>
                        {{-- <td>{{ $detail->receivedSerial->asset_description }}</td> --}}
                        <td title="{{ $detail->receivedSerial?->asset_description }}">
                            {{ \Illuminate\Support\Str::limit($detail->receivedSerial?->asset_description, 50, '...') }}
                        </td>

                        <td>{{ $detail->receivedSerial?->requisitionDetail?->grnItemDetail?->item?->uom ?? '-' }}
                        </td>
                        <td class="text-right">{{$detail->receivedSerial?->quantity ?? 1}}</td>
                        <td class="text-right">{{ $detail->receivedSerial?->amount }}</td>
                        <td class="text-right">{{ $return->employee->name ?? config('global.null_value')  }}</td>
                        <td class="text-right">{{ $detail->store->name ?? config('global.null_value') }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail->receivedSerial->commissionDetail->date_placed_in_service)->format('d-M-Y') ?? config('global.null_value')  }}
                        </td>
                        <td>{{ $detail->remark ?? '-' }}</td>
                        <td>{{ $return->received_acknowledged ? 'Acknowledged' : 'Not Acknowledged'}}</td>
                        {{-- Parent-level status & approver repeated per row --}}
                        <td>{{ config("global.application_status.{$return->status}", 'Unknown') }}
                        </td>
                        <td>{{ $return->histories->last()->approvedBy->emp_id_name ?? '-' }}</td>
                        {{-- <td>
                            @if ($privileges->view)
                                <a href="{{ url('asset-report/asset-return-report/' . $comm->id) }}"
                                    class="btn btn-sm btn-outline-secondary"><i
                                        class="fa fa-list"></i> Detail</a>
                            @endif
                        </td> --}}
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
