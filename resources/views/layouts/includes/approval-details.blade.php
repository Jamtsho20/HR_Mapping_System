<table style="width:100%;" class="simple-table">
    <tbody>
        @if ($applicationStatus == 3)
            <tr>
                <th style="width:35%;">Approved By <span class="pull-right d-none d-sm-block">:</span>
                    &nbsp;&nbsp;</th>
                <td style="padding-left:25px;">
                    @foreach ($approvalDetail as $log)
                        @if ($log->status == 3)
                            {{ $log->approver ? $log->approver->name : 'N/A' }}
                        @endif
                    @endforeach
                </td>
            </tr>
        @elseif ($applicationStatus == -1)
            <tr>
                <th style="width:35%;">Rejected By <span class="pull-right d-none d-sm-block">:</span>
                    &nbsp;&nbsp;</th>
                <td style="padding-left:25px;">
                    @foreach ($approvalDetail as $log)
                        @if ($log->status == -1)
                            {{ $log->approver ? $log->approver->name : 'N/A' }}
                        @endif
                    @endforeach
                    {{ $rejectionRemarks ?? config('global.null_value') }}
                </td>
            </tr>
        @else
            <tr>
                <th style="width:35%;">Status <span class="pull-right d-none d-sm-block">:</span>
                    &nbsp;&nbsp;</th>
                <td style="padding-left:25px;">
                    In-Progress
                </td>
            </tr>

        @endif
    </tbody>
</table>
