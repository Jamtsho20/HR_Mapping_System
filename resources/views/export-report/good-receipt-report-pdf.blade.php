<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Good Receipt Report</title>
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
    <h1 class="title">Good Receipt Report</h1>
    <div class="table-container">
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    SL no
                </th>
                <th>
                    Goods Received By
                </th>
                <th>
                    Department
                </th>
                <th>
                    Req No
                </th>
                <th>
                    GIN
                </th>
                <th>
                    Goods Issued From (Store)
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
                    Dzongkhag
                </th>
                <th>
                    Site
                </th>
                {{-- <th>
                    Capitalization Date
                </th>
                <th>
                    Is Received
                </th> --}}
                <th>
                    Remark
                </th>

            </tr>
        </thead>
        <tbody>
            @php $count = 1; @endphp
            @forelse($receivedSerials as $serials  )
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $serials->requisitionDetail->requisition->employee->emp_id_name ?? config('global.null_value')   }}</td>
                        <td>{{ $serials->requisitionDetail->requisition->employee->empJob->department->name ?? config('global.null_value')  }}</td>                                                {{-- Detail-specific data --}}

                        <td>{{ $serials?->requisitionDetail?->requisition?->transaction_no ?? config('global.null_value')  }}</td>
                        <td>{{ $serials?->requisitionDetail?->requisition?->good_issue_doc_no ?? config('global.null_value')  }}</td>
                        <td>{{ $serials?->requisitionDetail?->grnItemDetail?->store?->name ?? config('global.null_value')  }}</td>
                        <td>{{ $serials?->requisitionDetail?->grnItemDetail?->item?->item_no .'-'. $serials?->asset_serial_no  ?? config('global.null_value')  }}</td>
                        <td title="{{ $serials?->asset_description }}">
                            {{ \Illuminate\Support\Str::limit($serials?->asset_description, 75, '...') }}
                        </td>

                        <td>{{ $serials?->requisitionDetail?->grnItemDetail?->item?->uom ?? config('global.null_value')  }}
                        </td>
                        <td class="text-right">{{$serials?->quantity ?? 1}}</td>
                        <td class="text-right">{{ $serials?->amount ?? config('global.null_value')  }}</td>
                        <td class="text-right">{{ $serials->requisitionDetail?->dzongkhag->dzongkhag ?? config('global.null_value')  }}</td>
                        <td class="text-right">{{ $serials->requisitionDetail?->site->name ?? config('global.null_value')  }}</td>
                        {{-- <td class="text-right">{{ $serials->commissionDetail?->date_placed_in_service ?? config('global.null_value')  }}</td>
                        <td class="text-right">{{ $serials->is_received ? 'Received' : 'Not Received' ?? config('global.null_value')  }}</td> --}}
                        <td class="text-right">{{ $serials->remark ?? config('global.null_value')  }}</td>

                    </tr>

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
