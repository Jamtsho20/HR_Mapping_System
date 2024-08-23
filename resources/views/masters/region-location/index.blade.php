<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Region Location</h3>
                <form action="{{ route('region-location.create') }}" method="GET">
                    @foreach($regionLocations as $location)
                    <input type="hidden" value="{{ $location->id }}" name="regionLocationId[]">
                    @endforeach
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Region Location</button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
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
                                                        <th>Name</th>
                                                       
                                                        <th>Dzongkhag</th>
                                                        <th>Created At</th>
                                                        <th>Updated At</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tbody>
                                                    @foreach($regionLocations as $location)
                                                    <tr>
                                                        <td>{{ $location->name }}</td>
                                                        <td>{{ $location->dzongkhag->dzongkhag }}</td>
                                                        <td>{{ $location->created_at ? $location->created_at->format('Y-m-d') : '' }}</td>
                                                        <td>{{ $location->updated_at ? $location->updated_at->format('Y-m-d') : '' }}</td>
                                                        <!-- <td class="text-center">
                                                            <a href="{{ url('master/regions/'.$region->id. '/edit') }}" data-name="{{ $region->name }}" class="btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/regions/'.$region->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                        </td> -->
                                                        <td class="text-center">
                                                            <a href="{{ route('region-location.edit', $region->id) }}" data-name="{{ $region->name }}" class="btn btn-sm btn-rounded btn-outline-success">
                                                                <i class="fa fa-edit"></i> EDIT
                                                            </a>
                                                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ route('region-location.destroy', $region->id) }}">
                                                                <i class="fa fa-trash"></i> DELETE
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                </tbody>
                                            </table>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div>{{ $regionLocations->links() }}</div>
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
</div>
@include('layouts.includes.delete-modal')