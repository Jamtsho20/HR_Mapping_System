@extends('layouts.app')

@section('page-title', 'Training')

@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('my-training.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> Add New Training
</a>
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
                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                            <thead class="thead-light">
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Training Title</th>
                                    <!-- <th>Is Self Funded</th> -->
                                    <th>Applied On</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            
                        </table>

                      

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