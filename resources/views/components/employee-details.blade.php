<div class="col-lg-12">
    <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
        <div class="row">
            <div class="col-md-12">
                <h6>Employee Details</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table style="width:100%;">
                    <tbody>

                        <tr>
                            <th style="width:35%;">Name <span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;
                            </th>
                            <td style="padding-left:25px;">{{ $empDetails->name }}</td>
                        </tr>
                        <tr>
                            <th style="width:35%;">Department <span class="pull-right d-none d-sm-block">:</span>
                                &nbsp;&nbsp;</th>
                            <td style="padding-left:25px;">{{ $empDetails->empJob->department->name }}</td>
                        </tr>
                        <tr>
                            <th style="width:35%;">Section <span class="pull-right d-none d-sm-block">:</span>
                                &nbsp;&nbsp;</th>
                            <td style="padding-left:25px;">
                                {{ $empDetails->empJob->section->name ?? config('global.null_value') }}</td>
                        </tr>
                        <tr>
                            <th style="width:35%;">Emp Id <span class="pull-right d-none d-sm-block">:</span>
                                &nbsp;&nbsp;</th>
                            <td style="padding-left:25px;">{{ $empDetails->username }}</td>
                        </tr>
                        <tr>
                            <th style="width:35%;">Email <span class="pull-right d-none d-sm-block">:</span>
                                &nbsp;&nbsp;</th>
                            <td style="padding-left:25px;">{{ $empDetails->email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
