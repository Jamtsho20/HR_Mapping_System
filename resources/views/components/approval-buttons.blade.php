@include('layouts.includes.loader')
<div class="col-lg-12">

    @if ($privileges->edit)
        <div class="col-lg-6 pb-5" style="padding-left:0">
            <input class="btn-sm btn-success buttonsubmit" type="button" id="btn_approved" data-value="approve"
                data-route="{{ route('approverejectbulk') }}" value="Approve">
            <input class="btn-sm btn-danger buttonsubmit" type="button" id="btn_reject" data-value="reject"
                data-route="{{ route('approverejectbulk') }}" value="Reject">
        </div>
    @endif

</div>

@include('layouts.includes.reject-modal')
