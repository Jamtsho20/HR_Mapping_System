<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CWIP Report</title>
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
    <h1 class="title">CWIP Report</h1>
    <div class="table-container">
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    SL no
                </th>
                <th>
                    Asset Class Code
                </th>
                <th>
                    Asset Class Name
                </th>
                <th>
                    Requisition No.
                </th>
                <th>
                    GRN
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
                    Goods Received Date
                </th>
                <th>
                    Cost
                </th>
                <th>
                    Issued From
                </th>
                <th>
                    Employee Code
                </th>
                <th>
                    Employee Name
                </th>
                <th>
                    Dzongkhag
                </th>
                <th>
                    Project Code
                </th>
                <th>
                    Project Name
                </th>

            </tr>
        </thead>
        <tbody>
            @php $count = 1; @endphp
            @forelse($receivedSerials as $serial)
                    <tr>
                        <td>{{ $count++ }}</td> {{-- Parent index --}}
                        <td>{{ $serial->requisitionDetail?->grnItemDetail->item->item_group_id ?? config('global.null_value')  }}</td>
                        <td>
                            {{ config('global.asset_class')[$serial->requisitionDetail?->grnItemDetail->item->item_group_id]
                            ?? $serial->requisitionDetail?->grnItemDetail->item->item_group_id
                            ?? config('global.null_value') }}
                        </td>
                        <td>{{ $serial->requisitionDetail?->requisition->transaction_no ?? config('global.null_value')  }}</td>
                        <td>{{ $serial->requisitionDetail?->grnItemDetail->grn->grn_no ?? config('global.null_value')  }}</td>
                        <td>{{ $serial->requisitionDetail?->grnItemDetail->item->item_no .'-'.$serial->asset_serial_no ?? config('global.null_value')  }}</td>
                        <td title="{{ $serial->asset_description }}">
                            {{ \Illuminate\Support\Str::limit($serial->asset_description, 50, '...') }}
                        </td>
                        <td>{{ $serial->requisitionDetail?->grnItemDetail->item->uom ?? config('global.null_value')  }}
                        </td>
                        <td class="text-right">{{$serial->quantity ?? 1}}</td>
                        <td>{{ $serial->requisitionDetail?->received_at ?? config('global.null_value')  }}</td>
                        <td>{{ $serial->amount ?? config('global.null_value')  }}</td>
                        <td>{{ $serial->requisitionDetail?->grnItemDetail->store->code ?? config('global.null_value')  }}</td>
                        <td>{{ $serial->requisitionDetail?->requisition->employee->username ?? config('global.null_value')  }}</td>
                        <td>{{ $serial->requisitionDetail?->requisition->employee->name ?? config('global.null_value')  }}</td>
                        <td>{{ $serial->requisitionDetail?->dzongkhag->dzongkhag ?? config('global.null_value')  }}</td>
                        <td>{{ $serial->requisitionDetail?->site->code ?? config('global.null_value')  }}</td>
                        <td>{{ $serial->requisitionDetail?->site->name ?? config('global.null_value')  }}</td>
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
