<div class="modal fade" id="delete-modal" tabindex="-1" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Yes</button>
                    <button type="button" class="btn btn-danger close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i> No</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('page_scripts')
<script>
    $('.delete-btn').click(function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        var modal = $('#delete-modal');
        modal.find('form').attr('action', url);
        modal.modal('show');
    })
</script>
@endpush