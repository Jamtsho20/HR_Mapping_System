@extends('layouts.app')
@section('page-title', 'Sifa Registration')
@section('content')

<style>
    .form-border {
        border: 2px solid #dee2e6;
        border-radius: 5px;
        padding: 20px;
    }
</style>
</head>
<body>
    <div class="container mt-5">
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="dataTables_length" id="responsive-datatable_length" data-select2-id="responsive-datatable_length">
                                            <div class="form-group form-border">
                                                <form method="GET" action="{{ route('sifa-registration.create') }}">
                                                    <label for="sifa_registration">Membership for SIFA is purely voluntary. Do you wish to register as a member of SIFA ? </label>
                                                    <br><br>
                                                    <div class="form-check">
                                                        <input type="radio" id="yes" name="sifa_registration" value="1" class="form-check-input">
                                                        <label for="yes" class="form-check-label">Yes (If you wish to register as a member, you cannot withdraw your membership<br> from SIFA for the entire duration of your service with the company)</label>
                                                    </div>
                                                    <br>
                                                    <div class="form-check">
                                                        <input type="radio" id="no" name="sifa_registration" value="0" class="form-check-input">
                                                        <label for="no" class="form-check-label">No (If you do not wish to register as a member this time, you cannot become a member<br> for the entire duration of your service with the company)</label>
                                                    </div>
                                                    <br>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </form>
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
</body>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush
