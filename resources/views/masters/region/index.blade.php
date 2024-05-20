@extends('layouts.app')
@section('page-title', 'Region')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="region" class="form-control" value="{{ request()->get('region') }}" placeholder="Region">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="#" data-toggle="modal" data-target="#create-modal" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Region</a>
                @endif
            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Region</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($regions as $region)
                <tr>
                    <td>{{ $regions->firstItem() + ($loop->iteration - 1) }}</td>
                    <td>{{ $region->region_name }}</td>

                    <td class="text-center">
                        @if ($privileges->edit)
                        <a href="{{ url('master/regions/'.$region->id) }}" data-name="{{ $region->region_name }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/regions/'.$region->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-danger">No Regions found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($regions->hasPages())
    <div class="card-footer">
        {{ $regions->links() }}
    </div>
    @endif
</div>
<div class="modal show" id="create-modal" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ url('master/regions') }}" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">New Region</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label for="region">Region <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="region" value="{{ old('region') }}"  required="required">
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
                        <h3 class="block-title">Edit Region</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label for="region">Region <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="region">
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
            var region = $(this).data('name');
            var modal = $('#edit-modal');
            modal.find('form').attr('action', url);
            modal.find('input[name=region]').val(region)
            modal.modal('show');
        });
    });
</script>
@endpush