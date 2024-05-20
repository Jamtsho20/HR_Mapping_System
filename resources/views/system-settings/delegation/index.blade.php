@extends('layouts.app')
@section('page-title', 'Delegation')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
  
        <div class="col-8 form-group">            
        </div>     
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="#" data-toggle="modal" data-target="#create-modal" class="btn btn-sm btn-primary"><i
                        class="fa fa-plus"></i> New Delegation</a>
                @endif
            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th >Type</th>
                    <th >Delegate To</th>
                    <th >From Date</th>
                    <th >Start Date</th>
                    <th >Status</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="6" class="text-center text-danger">No Hierarchy found</td>
                </tr>

            </tbody>
        </table>
    </div>

    <div class="card-footer">

    </div>

</div>
<div class="modal show" id="create-modal" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Add Delegation</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="form-group col-4">
                                <label for="type">Type<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="type"
                                    value="{{ old('type') }}" required="required">
                            </div>
                           

                            <div class="form-group col-4">
                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                <input type="text" class="js-datepicker form-control js-datepicker"
                                    id="example-datepicker1" name="example-datepicker1" data-week-start="1"
                                    data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy"
                                    placeholder="mm/dd/yy">
                            </div>

                            <div class="form-group col-4">
                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                <input type="text" class="js-datepicker form-control js-datepicker"
                                    id="example-datepicker1" name="example-datepicker1" data-week-start="1"
                                    data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy"
                                    placeholder="mm/dd/yy">
                            </div>

                            <div class="form-group col-4">
                                <label for="">Delegate To <span class="text-danger">*</span></label>
                                <select class="form-control" name="delegate_to">
                                    <option value="" disabled selected hidden>Select </option>                                                             
                                </select>
                            </div>

                            <div class="form-group col-4">
                                <label for="">Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="status">
                                    <option value="" disabled selected hidden>Select Status</option>
                                    <option value="">Active</option>
                                    <option value="">Inactive</option>                                 
                                </select>
                            </div>
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

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush