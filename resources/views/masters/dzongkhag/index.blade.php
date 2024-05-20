@extends('layouts.app')
@section('page-title', 'Dzongkhag')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="dzongkhag" class="form-control" value="{{ request()->get('dzongkhag') }}" placeholder="Dzongkhag">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="#" data-toggle="modal" data-target="#create-modal" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Dzongkhag</a>
                @endif
            </div>
        </div>
    </div>
    <div class="block-content">

        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Dzongkhag</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dzongkhags as $dzongkhag)
                <tr>
                    <td>{{ $dzongkhags->firstItem() + ($loop->iteration - 1) }}</td>
                    <td>{{ $dzongkhag->dzongkhag }}</td>
                    <td class="text-center">
                        @if ($privileges->edit)
                        <a href="{{ url('master/dzongkhags/'.$dzongkhag->id) }}" data-dzongkhag="{{ $dzongkhag->dzongkhag }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/dzongkhags/'.$dzongkhag->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-danger">No dzongkhag found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
    @if ($dzongkhags->hasPages())
    <div class="card-footer">
        {{ $dzongkhags->links() }}
    </div>
    @endif
</div>
<div class="modal show" id="create-modal" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ url('master/dzongkhags') }}" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">New Dzongkhag</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label for="dzongkhag">Dzongkhag <span class="text-danger">*</span></label>
                            <input type="text" required="required" class="form-control" name="dzongkhag" value="{{ old('dzongkhag') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-check"></i> Save
                    </button>
                    <button type="button" class="btn btn-alt-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-modal" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Edit Dzongkhag</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label for="dzongkhag">Dzongkhang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="dzongkhag">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-check"></i> Update
                    </button>
                    <button type="button" class="btn btn-alt-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    $(document).ready(function() {
        $('.edit-btn').click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var dzongkhag = $(this).data('dzongkhag');
            var modal = $('#edit-modal');
            modal.find('form').attr('action', url);
            modal.find('input[name=dzongkhag]').val(dzongkhag)
            modal.modal('show');
        });
    });
</script>

@endpush