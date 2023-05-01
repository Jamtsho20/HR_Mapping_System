@extends('layouts.app')
@section('page-title', 'Village')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="form-group row">
            <div class="col-4">
                <select class="form-control" id="dzongkhag_search" name="dzongkhag">
                    <option value="" disabled selected hidden>Select Dzongkahg</option>
                    @foreach ($dzongkhags as $dzongkhag)
                    <option  @if ($dzongkhag->id == request()->get('dzongkhag')) selected
                        @endif value=" {{ $dzongkhag->id }}">
                        {{ $dzongkhag->dzongkhag }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <select id="gewog_search" class="form-control" name="gewog">
                    <option value="" disabled selected hidden>Select Gewog</option>
                    @foreach ($gewogs as $gewog)
                    <option @if ($gewog->id == request()->get('gewog')) selected
                        @endif value=" {{ $gewog->id }}">
                        {{ $gewog->name}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <input type="text" name="village" class="form-control" value="{{ request()->get('village') }}"
                    placeholder="Village">
            </div>
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="#" data-toggle="modal" data-target="#create-modal" class="btn btn-sm btn-primary"><i
                        class="fa fa-plus"></i> New Village</a>
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
                    <th>Village</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($villages as $village )
                <tr>
                    <td>{{ $villages->firstItem() + ($loop->iteration - 1) }}</td>
                    <td>{{ $village->gewogs->dzongkhag->dzongkhag }}</td>
                    <td>{{ $village->gewogs->name }}</td>
                    <td>{{ $village->village }}</td>
                    <td class="text-center">
                        @if ($privileges->edit)
                        <a href="{{ url('master/villages/'.$village->id) }}" data-village="{{ $village->village }}"
                            data-gewog-id="{{ $village->mas_gewog_id }}"
                            class="edit-btn btn btn-sm btn-rounded btn-outline-success">
                            <i class="fa fa-edit"></i> EDIT
                        </a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                            data-url="{{ url('master/villages/'.$village->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-danger">No villages found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($villages->hasPages())
    <div class="card-footer">
        {{ $villages->links() }}
    </div>
    @endif
</div>
<div class="modal show" id="create-modal" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ url('master/villages') }}" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">New Village</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label for="dzongkhag_id">Dzongkhag </label>
                            <select class="form-control" id="dzongkhag_id" >
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->dzongkhag  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gewog_id">Gewog <span class="text-danger">*</span></label>
                            <select class="form-control" id="gewog_id" name="mas_gewog_id">
                                {{-- will be populated --}}
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="village" value="{{ old('village') }}"
                                required="required">
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
                        <h3 class="block-title">Edit Village</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>

                    <div class="block-content">
                        <div class="form-group">
                            <label for="name">Village <span class="text-danger">*</span> </label>
                            <select name="mas_gewog_id" class="form-control ">
                                @foreach ($gewogs as $gewog)
                                <option value="{{ $gewog->id }}">{{ $gewog->name  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="village">
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
                var village = $(this).data('village');
                var gewogId = $(this).data('gewog-id');
                var modal = $('#edit-modal');
                modal.find('form').attr('action', url);
                modal.find('select[name=mas_gewog_id]').val(gewogId);
                modal.find('input[name=village]').val(village);
                modal.modal('show');
            });
        });

        $('#gewog_search').select2({
            placeholder: "Search Gewog",
            allowClear: true
        });
        $('#dzongkhag_search').select2({
            placeholder: "Search Dzongkhag",
            allowClear: true
        });
    </script>
@endpush