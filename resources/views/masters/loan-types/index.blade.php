@extends('layouts.app')
@section('page-title', '')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('loan-types.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Loan Types</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-12 form-group">
        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Name">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="dataTables_scroll">
                                <div class="dataTables_scrollHead"
                                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                    <div class="dataTables_scrollHeadInner"
                                        style="box-sizing: content-box; padding-right: 0px;">
                                        <table
                                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                            id="basic-datatable table-responsive">
                                            <thead>
                                                <tr role="row" class="thead-light">
                                                    <th>
                                                        Name
                                                    </th>
                                                    <th>
                                                        Code
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            @forelse($loanTypes as $loanType)
                                            <tr>
                                                <td>{{ $loanType->name }}</td>
                                                <td class="text-right">{{ $loanType->code }}</td>
                                                <td class="text-center">
                                                    @if ($privileges->edit)
                                                    <a href="{{ route('loan-types.edit', $loanType->id) }}" class="btn btn-sm btn-rounded btn-outline-success">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    @endif
                                                    @if ($privileges->delete)
                                                    <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ route('loan-types.destroy', $loanType->id) }}">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-danger">No Loan Types found</td>
                                            </tr>
                                            @endforelse
                                            </tbody>
                                        </table>

                                        <!-- Pagination -->
                                        {{ $loanTypes->links() }}
                                    </div>
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
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush