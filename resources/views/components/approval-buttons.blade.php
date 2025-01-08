@include('layouts.includes.loader')
<div class="block-content">
    <div class="block-options">
    @if ($privileges->edit)
        <div class="col-sm-6 pt-0 pb-5">
            <input class="btn-sm btn-success buttonsubmit" type="button" id="btn_approved" data-value="approve"
                data-route="{{ route('approverejectbulk') }}" value="Approve">
            <input class="btn-sm btn-danger buttonsubmit" type="button" id="btn_reject" data-value="reject"
                data-route="{{ route('approverejectbulk') }}" value="Reject">
        </div>
    @endif
</div>
</div>

@include('layouts.includes.reject-modal')

