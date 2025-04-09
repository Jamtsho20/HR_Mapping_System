<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Report</title>
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
    <h1 class="title">Commission Report</h1>
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    SL no
                </th>
                <th>
                    Employee Name
                </th>
                <th>
                    Comm No
                </th>
                <th>
                    Comm Date
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
                    Date Placed In Service
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
            @forelse($commissions as $comm)
                @foreach ($comm->details as $detail)
                    <tr>
                        <td>{{ $count++ }}</td> {{-- Parent index --}}
                        <td>{{ $comm->employee->emp_id_name }}</td>
                        <td>{{ $comm->transaction_no }}</td>
                        <td>{{ $comm->transaction_date }}</td>

                        {{-- Detail-specific data --}}
                        <td>{{ $detail->receivedSerial->asset_serial_no }}</td>
                        {{-- <td>{{ $detail->receivedSerial->asset_description }}</td> --}}
                        <td title="{{ $detail->receivedSerial->asset_description }}">
                            {{ \Illuminate\Support\Str::limit($detail->receivedSerial->asset_description, 25, '...') }}
                        </td>

                        <td>{{ $detail->receivedSerial->requisitionDetail->grnItemDetail->item->uom ?? '-' }}
                        </td>
                        <td class="text-right">1</td>
                        <td class="text-right">{{ $detail->receivedSerial->amount }}</td>
                        <td>{{ $detail->dzongkhag->dzongkhag }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail->date_placed_in_service)->format('d-M-Y') }}
                        </td>
                        <td>{{ $detail->site->name ?? '-' }}</td>
                        <td>{{ $detail->remark ?? '-' }}</td>

                        {{-- Parent-level status & approver repeated per row --}}
                        <td>{{ config("global.application_status.{$comm->status}", 'Unknown') }}
                        </td>
                        <td>{{ $comm->approvedBy->emp_id_name ?? '-' }}</td>
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
                    <td colspan="16" class="text-danger text-center">No Data Found</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
