@extends('layouts.app')
@section('page-title', 'Gewog')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="form-group row">
            <div class="col-6">
                <select class="form-control" id="dzongkhag" name="dzongkhag">
                    <option value="" disabled selected hidden>Select Dzongkhag</option>
                    @foreach ($dzongkhags as $dzongkhag)
                    <option @if ($dzongkhag->id == request()->get('dzongkhag')) selected @endif value="{{ $dzongkhag->id }}">
                        {{ $dzongkhag->dzongkhag  }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <input type="text" name="gewog" class="form-control" value="{{ request()->get('name') }}" placeholder="Gewog">
            </div>
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="#" data-toggle="modal" data-target="#create-modal" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Gewog</a>
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
                    <th>Gewog</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gewogs as $gewog )
                <tr>
                    <td>{{ $gewogs->firstItem() + ($loop->iteration - 1) }}</td>
                    <td>{{ $gewog->dzongkhag->dzongkhag }}</td>
                    <td>{{ $gewog->name }}</td>
                    <td class="text-center">
                        @if ($privileges->edit)
                        <a href="{{ url('master/gewogs/'.$gewog->id) }}" data-name="{{ $gewog->name }}" data-dzongkhag-id="{{ $gewog->mas_dzongkhag_id }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success">
                            <i class="fa fa-edit"></i> EDIT
                        </a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/gewogs/'.$gewog->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-danger">No gewogs found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($gewogs->hasPages())
    <div class="card-footer">
        {{ $gewogs->links() }}
    </div>
    @endif
</div>
<div class="modal show" id="create-modal" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ url('master/gewogs') }}" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">New Gewog</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label for="mas_dzongkhag_id">Dzongkhag <span class="text-danger">*</span></label>
                            <select class="form-control"  name="mas_dzongkhag_id" required="required">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->dzongkhag  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required="required">
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
                        <h3 class="block-title">Edit Gewog</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>

                    <div class="block-content">
                        <div class="form-group">
                            <label for="name">Dzongkhag <span class="text-danger">*</span></label>
                            <select name="mas_dzongkhag_id" class="form-control" id="dzongkhag1">
                                @foreach ($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->dzongkhag  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name">
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
            var gewog = $(this).data('name');
            var dzongkhagId = $(this).data('dzongkhag-id');
            var modal = $('#edit-modal');
            modal.find('form').attr('action', url);
            modal.find('select[name=mas_dzongkhag_id]').val(dzongkhagId);
            modal.find('input[name=name]').val(gewog);
            modal.modal('show');
        });
    });

    $('#dzongkhag').select2({
        placeholder: "Select Dzongkhag",
        allowClear: true
    });

</script>
@endpush