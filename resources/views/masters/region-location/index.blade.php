<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Region Location</h3>
                <form action="{{ route('region-location.create') }}" method="GET">
                    <input type="hidden" value="{{ $region->id }}" name="regionId">
                    <button type="button" class="add-btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add-region-location-modal">
                        <i class="fa fa-plus"></i> Add New Region Location
                    </button>
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
                                                        <th>Status</th>
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
                                                        <td>{{ $location->status ? 'Active' : 'Inactive' }}</td>
                                                        <td>{{ $location->created_at ? $location->created_at->format('Y-m-d') : '' }}</td>
                                                        <td>{{ $location->updated_at ? $location->updated_at->format('Y-m-d') : '' }}</td>
                                                        <td class="text-center">
                                                            <a href="#" class="edit-btn btn btn-sm btn-rounded btn-outline-success"
                                                                data-url="{{ url('getregionlocation/' . $location->id) }}"
                                                                data-update-url="{{ url('master/region-location/' . $location->id) }}">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a>

                                                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                data-url="{{ url('master/region-location/' . $location->id) }}">
                                                                <i class="fa fa-trash"></i> Delete
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

<!-- Add New Region Location Modal -->
<div class="modal fade" id="add-region-location-modal" tabindex="-1" aria-labelledby="addRegionLocationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #f8f9fa;">
            <div class="modal-header">
                <h5 class="modal-title" id="addRegionLocationLabel">Add New Region Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('region-location.store') }}" method="POST" id="add-region-location-form">
                    @csrf
                    <input type="hidden" name="mas_region_id" value="{{ $region->id }}">
                    <div class="mb-3">
                        <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="region" name="region" value="{{ $region->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="region_name" class="form-label">Region Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="dzongkhag" class="form-label">Dzongkhag <span class="text-danger">*</span></label>
                        <select class="form-control" name="mas_dzongkhag_id" id="dzongkhag" required>
                            <option value="">Select Dzongkhag</option>
                            @foreach($dzongkhags as $dzongkhag)
                            <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag') == $dzongkhag->id ? 'selected' : '' }}>
                                {{ $dzongkhag->dzongkhag }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <div class="form-label mt-6"></div>
                        <label class="custom-switch">
                            <!-- Hidden input to pass '0' when checkbox is unchecked -->
                            <input type="hidden" name="status[is_active]" value="0">
                            <!-- Checkbox to pass '1' when checked, and retain old value -->
                            <input type="checkbox"
                                name="status[is_active]"
                                class="custom-switch-input form-control form-control-sm"
                                value="1"
                                {{ old('status.is_active') == '1' ? 'checked' : '' }} />
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">is Active</span>
                        </label>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal-->
<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="editDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #f8f9fa;">
            <div class="modal-header">
                <h5 class="modal-title" id="editDetailLabel">Edit Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="edit-modal-form">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="region" name="region" value="{{ $region->name }}" disabled>
                        <input type="hidden" name="mas_region_id" value="{{ $region->id }}">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $region->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="dzongkhag" class="form-label">Dzongkhag <span class="text-danger">*</span></label>
                        <select class="form-control" id="dzongkhag" name="mas_dzongkhag_id">
                            <option value="" disabled selected hidden>Select Dzongkhag</option>
                            @foreach ($dzongkhags as $dzongkhag)
                            <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag', $region->mas_dzongkhag_id) == $dzongkhag->id ? 'selected' : '' }}>{{ $dzongkhag->dzongkhag }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <div class="form-label mt-6"></div>
                        <label class="custom-switch">
                            <!-- Hidden input to pass '0' when checkbox is unchecked -->
                            <input type="hidden" name="status[is_active]" value="0">
                            <!-- Checkbox to pass '1' when checked -->
                            <input type="checkbox"
                                name="status[is_active]"
                                class="custom-switch-input form-control form-control-sm"
                                value="1"
                                {{ old('status.is_active', $region->status) == 1 ? 'checked' : '' }} />
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">is Active</span>
                        </label>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Custom backdrop style -->
<style>
    .modal-backdrop {
        background-color: rgba(255, 255, 255, 0.7) !important;
    }
</style>
@include('layouts.includes.delete-modal')