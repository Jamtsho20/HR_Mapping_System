<div class="modal fade" id="proceed-modal" tabindex="-1" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm Proceed</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    Are you sure you want to proceed? The action is irreversible!
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $('.proceed-btn').click(function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        var modal = $('#proceed-modal');
        modal.find('form').attr('action', url);
        modal.modal('show');
    })
</script>
@endpush