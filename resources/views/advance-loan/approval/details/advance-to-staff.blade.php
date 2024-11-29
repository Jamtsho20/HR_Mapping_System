
<table class="table table-condensed table-bordered table-striped table-sm mt-4">
    <thead>
        <tr>
            <th width="3%" class="text-center">#</th>
            <th>Budget Code</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Dzongkhag</th>
            <th>Site Location</th>
            <th>Advance Required</th>
            <th colspan="2">Purpose</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($advance->advanceDetails as $detail)
        <tr>
            <td class="text-center">
                {{$detail->id}}
            </td>
            <td>
                {{$detail->budgetCode->code}}
            </td>
            <td>
                {{$detail->from_date}}
            </td>
            <td>
                {{$detail->to_date}}
            </td>
            <td>
                {{$detail->dzongkhag->dzongkhag}}
            </td>
            <td>
                {{$detail->site_location}}
            </td>
            <td>
                {{$detail->amount_required}}
            </td>
            <td colspan="2">{{$detail->purpose??'-'}} </td>
        </tr>
        @endforeach

    </tbody>
</table>