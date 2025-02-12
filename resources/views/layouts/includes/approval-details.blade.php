<table style="width:100%;" class="simple-table">
    <tbody>
        @if ($applicationStatus == 3)
            <tr>
                <td>
                    @foreach ($approvalDetail as $log)
                        @if ($log->status == 3)
                            <strong><i class="fa fa-check text-success"></i> Approved By
                                {{ $log->approver->title }}
                                {{ $log->approver ? $log->approver->name : 'N/A' }}
                                on
                                {{ $log->created_at->format('d-m-y') }}</strong>
                        @endif

                    @endforeach
                </td>
            </tr>
        @elseif ($applicationStatus == -1)
            <tr>
                <td>
                    @foreach ($approvalDetail as $log)
                        @if ($log->status == -1)
                            <strong><i class="fa fa-close text-danger"></i> Rejected By
                                {{ $log->approver->title }}
                                {{ $log->approver ? $log->approver->name : 'N/A' }}
                                on
                                {{ $log->created_at->format('d-m-y') }}</strong>
                        @endif

                    @endforeach

                </td>
            </tr>
            <tr>
                <td style="padding-left:16px;"><strong>Remarks:</strong>
                    {{ $rejectionRemarks ?? config('global.null_value') }}</td>
            </tr>
        @elseif($applicationStatus == 2)
            <tr>

                <td>
                    @foreach ($approvalDetail as $log)
                        @if ($log->status == 2)
                            <strong><i class="fa fa-check text-info"></i> Verified By
                                {{ $log->approver->title }}
                                {{ $log->approver ? $log->approver->name : 'N/A' }}
                                on
                                {{ $log->created_at->format('d-m-y') }}</strong>

                        @endif
                    @endforeach
                </td>
            </tr>
        @else
            <tr>
                <th style="width:35%;">Status <span class="pull-right d-none d-sm-block">:</span>
                    &nbsp;&nbsp;</th>
                <td style="padding-left:25px;">
                    Submitted for Approval
                </td>
            </tr>

        @endif
        

    </tbody>
</table>
