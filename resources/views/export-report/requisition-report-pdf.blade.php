<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Report</title>
    <style>
        body {
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .img-container {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 60%;


        }

        .title {
            text-align: center;
            padding: 10px 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-transform: capitalize;
        }
    </style>

</head>

<body>
    <div class="img-container">
        @include('layouts.includes.letter-head')
    </div>
    <hr>
    <h1 class="title">Requisition Report</h1>
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    SL no
                </th>
                <th>
                    Employee
                </th>
                <th>
                    Department
                </th>
                <th>
                    Req Type
                </th>
                <th>
                    Req No
                </th>
                <th>
                    Req Date
                </th>
                <th>
                    GRN
                </th>
                <th>
                    Item Description
                </th>
                <th>
                    UOM
                </th>
                <th>
                    Store
                </th>
                <th>
                    Stock Status
                </th>
                <th>
                    Quantity Requested
                </th>
                <th>
                    Quantity Received
                </th>
                <th>
                    Dzongkhag
                </th>
                <th>
                    Site
                </th>
                <th>
                    Remark
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
            @forelse($requisitions as $req)
                @foreach ($req->details as $detail)
                    <tr>
                        <td>{{ $count++ }}</td> {{-- Parent index --}}
                        <td>{{ $req->employee->emp_id_name }}</td>
                        <td>{{ $req->employee->empJob->department->name ?? config('global_null_value') }}</td>
                        <td>{{ $req->type->name }}</td>
                        <td>{{ $req->transaction_no }}</td>
                        <td>{{ $req->transaction_date }}</td>

                        {{-- Detail-specific data --}}
                        <td>{{ $detail->grnItem->grn_no ?? config('global.null_value') }}
                        </td>
                        <td title="{{ $detail->grnItemDetail?->item?->item_description }}">
                            {{ \Illuminate\Support\Str::limit($detail->grnItemDetail?->item?->item_description, 25, '...') }}
                        </td>
                        <td>{{ $detail->grnItemDetail->item->uom ?? config('global.null_value') }}
                        </td>
                        <td>{{ $detail->grnItemDetail?->store?->name }}</td>
                        <td class="text-right">{{ $detail->current_stock }}</td>
                        <td class="text-right">{{ $detail->requested_quantity }}</td>
                        <td class="text-right">{{ $detail->received_quantity }}</td>
                        <td>{{ $detail->dzongkhag->dzongkhag ?? config('global.null_value') }}
                        </td>
                        <td>{{ $detail->site->name ?? config('global.null_value') }}</td>
                        <td>{{ $detail->remark ?? config('global.null_value') }}</td>

                        {{-- Parent-level status & approver repeated per row --}}

                        <td>{{ config("global.application_status.{$req->status}", 'Unknown') }}
                        </td>
                        <td>{{ $req->approvedBy->emp_id_name ?? '-' }}</td>
                        {{-- <td>
                                                            @if ($privileges->view)
                                                                <a href="{{ url('asset-report/commission-report/' . $comm->id) }}"
                                                                    class="btn btn-sm btn-outline-secondary"><i
                                                                        class="fa fa-list"></i> Detail</a>
                                                            @endif
                                                        </td> --}}
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="17" class="text-danger text-center">No Data Found</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
