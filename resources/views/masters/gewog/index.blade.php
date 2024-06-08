@extends('layouts.app')
@section('page-title', 'Gewog')
@if ($privileges->create)
@section('buttons')
<a href="{{route('gewogs.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Gewog</a>
@endsection
@endif
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="form-group">
            <div class="row">
                <div class="col-4">
                    <select class="form-control" id="dzongkhag" name="dzongkhag">
                        <option value="" disabled selected hidden>Select Dzongkhag</option>
                        @foreach ($dzongkhags as $dzongkhag)
                        <option @if ($dzongkhag->id == request()->get('dzongkhag')) selected @endif value="{{ $dzongkhag->id }}">
                            {{ $dzongkhag->dzongkhag  }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <input type="text" name="gewog" class="form-control" value="{{ request()->get('name') }}" placeholder="Gewog">
                </div>
                @endcomponent
            </div>
        </div>


    </div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="dataTables_length" id="responsive-datatable_length"
                                        data-select2-id="responsive-datatable_length">
                                            <label data-select2-id="26">
                                                Show
                                                    <select class="select2">
                                                        <option value="10">10</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                entries
                                            </label>
                                    </div>
                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table
                                                class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                id="basic-datatable table-responsive">
                                                <thead>
                                                    <tr role="row">
                                                        <th>
                                                            #
                                                        </th>
                                                        <th>
                                                            DZONGKHAG
                                                        </th>
                                                        <th>
                                                           GEWOG
                                                        </th>                                                      
                                                        <th>
                                                            ACTION
                                                        </th>
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
                                                                <a href="{{ url('master/gewogs/'.$gewog->id .'/edit') }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success">
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
                                        </div>
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
                    </div>    
                </div>
            </div>  
        </div>
    </div>
        @if ($gewogs->hasPages())
        <div class="card-footer">
            {{ $gewogs->links() }}
        </div>
        @endif
</div>


    @include('layouts.includes.delete-modal')
    @endsection
    @push('page_scripts')
    <!-- <script>
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

</script> -->
    @endpush