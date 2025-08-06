<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Report</title>
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
        <h1 class="title">Requisition Report</h1>

        <div class="table-container">
            <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                <thead class="thead-light">
                    <tr role="row">
                        <th>SL</th>
                        <th>Applicant</th>
                        <th>Department</th>
                        <th>Req Type</th>
                        <th>Req No</th>
                        <th>Application Date</th>
                        <th>GRN</th>
                        <th>Item Description</th>
                        <th>UOM</th>
                        <th>Store</th>
                        <th>Quantity Requested</th>
                        <th>Quantity Received</th>
                        <th>Dzongkhag</th>
                        <th>Site</th>
                        <th>Status</th>
                        <th>Approved By</th>
                    </tr>
                </thead>
                <tbody>
                    @php $count = 1; @endphp
                    @forelse($requisitions as $req)
                        @foreach ($req->details as $detail)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $req->employee->emp_id_name }}</td>
                                <td>{{ $req->employee->empJob->department->name ?? config('global_null_value') }}</td>
                                <td>{{ $req->type->name }}</td>
                                <td>{{ $req->transaction_no }}</td>
                                <td>{{ $req->transaction_date }}</td>
                                <td>{{ $detail->grnItem->grn_no ?? config('global.null_value') }}</td>
                                <td title="{{ $detail->grnItemDetail?->item?->item_description }}">
                                    {{ \Illuminate\Support\Str::limit($detail->grnItemDetail?->item?->item_description, 75, '...') }}
                                </td>
                                <td>{{ $detail->grnItemDetail->item->uom ?? config('global.null_value') }}</td>
                                <td>{{ $detail->grnItemDetail?->store?->name }}</td>
                                <td class="text-right">{{ $detail->requested_quantity }}</td>
                                <td class="text-right">{{ $detail->received_quantity }}</td>
                                <td>{{ $detail->dzongkhag->dzongkhag ?? config('global.null_value') }}</td>
                                <td>{{ $detail->site->name ?? config('global.null_value') }}</td>
                                <td>{{ config("global.application_status.{$req->status}", 'Unknown') }}</td>
                                <td>{{ $req->histories->last()->approvedBy->emp_id_name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="19" class="text-danger text-center">No Data Found</td>
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
