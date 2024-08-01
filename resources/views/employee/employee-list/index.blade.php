@extends('layouts.app')
@section('page-title', 'Employee List')
@section('content')
@if ($privileges->create)
    @section('buttons')
    <a href="{{ route('employee-list.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>Add New Employeement</a>
    @endsection
@endif
<div class="block-header block-header-default">
     @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="emplist" class="form-control" value="{{ request()->get('emplist') }}"
                placeholder="Search">
        </div>
    @endcomponent
    
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employee List</h3>
                </div>
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
                                                                SL no
                                                            </th>
                                                            <th>
                                                                Name
                                                            </th>
                                                            <th>
                                                                DOJ
                                                            </th>
                                                            <th>
                                                                Department
                                                            </th>
                                                            <th>
                                                                Region
                                                            </th>
                                                            <th>
                                                                Email
                                                            </th>
                                                            <th>
                                                                Status
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Adrian</td>
                                                            <td>Terry</td>
                                                            <td>Casual</td>
                                                            <td>2013/04/21</td>
                                                            <td>$543,769</td>
                                                            <td>0.5</td>
                                                            <td>0.5</td>
                                                            <td class="text-center">
                                                                @if ($privileges->edit)
                                                                    <a href="" data-short_name="" data-name="" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i
                                                                            class="fa fa-edit"></i>
                                                                        EDIT</a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                    <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url=""><i class="fa fa-trash"></i>
                                                                        DELETE</a>
                                                                @endif
                                                            </td>
                                                            
                                                        </tr>
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
</div>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
    //Carriage Charge
    $('#leave-type').on('change', function () {
        var selection = $(this).val()
        switch (selection) {
            case "Earned Leave":
                $("#first").hide();
                $("#second").hide();
                $("#to_first").hide();
                $("#to_second").hide();
                break;
            default:
                $("#first").show();
                $("#second").show()

        }
    });
</script>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush