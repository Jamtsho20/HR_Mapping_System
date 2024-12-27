<table style="width:100%;" class="simple-table">
    <tbody>
        <tr>
            <th style="width:35%;">Approved By <span class="pull-right d-none d-sm-block">:</span>
                &nbsp;&nbsp;</th>
            <td style="padding-left:25px;">
                @foreach($approvalDetail as $log)
                    @if($log->status == 3)
                        {{ $log->approver ? $log->approver->name : 'N/A' }}
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th style="width:35%;">Rejected By <span class="pull-right d-none d-sm-block">:</span>
                &nbsp;&nbsp;</th>
            <td style="padding-left:25px;">
                @foreach($approvalDetail as $log)
                    @if($log->status == -1)
                        {{ $log->approver ? $log->approver->name : 'N/A' }}
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
