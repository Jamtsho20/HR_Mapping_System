@extends('layouts.app')
@section('page-title', 'Sifa Registration')
@section('content')

<form action="" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom-0"><div class="card-title">Sifa Registration</div></div>
                    <div class="card-body">
                        <div id="wizard2" role="application" class="wizard clearfix">
                            <div class="steps clearfix">
                                <ul role="tablist">
                                    <li role="tab" class="first current" aria-disabled="false" aria-selected="true">
                                        <a id="wizard2-t-0" href="#wizard2-h-0" aria-controls="wizard2-p-0">
                                            <span class="current-info audible">current step: </span><span class="number">1</span> <span class="title">Employee Details</span>
                                        </a>
                                    </li>
                                    <li role="tab" class="disabled" aria-disabled="true">
                                        <a id="wizard2-t-1" href="#wizard2-h-1" aria-controls="wizard2-p-1"><span class="number">2</span> <span class="title">Sifa Nomination</span></a>
                                    </li>
                                    <li role="tab" class="disabled last" aria-disabled="true">
                                        <a id="wizard2-t-2" href="#wizard2-h-2" aria-controls="wizard2-p-2"><span class="number">3</span> <span class="title">Sifa Dependent</span></a>
                                    </li>
                                    <li role="tab" class="disabled last" aria-disabled="true">
                                        <a id="wizard2-t-2" href="#wizard2-h-2" aria-controls="wizard2-p-2"><span class="number">4</span> <span class="title">Sifa Document</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div style="padding: 20px;">
                                <h3 id="wizard1-h-0" tabindex="-1" class="title current">Employee Details</h3>
                                    <div id="wizard1-p-0" role="tabpanel" aria-labelledby="wizard1-h-0" class="body current" aria-hidden="false">
                                        <div class="control-group form-group"><label class="form-label">Name</label> <input type="text" class="form-control required" placeholder="Name"></div>
                                        <div class="control-group form-group"><label class="form-label">Email</label> <input type="email" class="form-control required" placeholder="Email Address"></div>
                                        <div class="control-group form-group"><label class="form-label">Phone Number</label> <input type="number" class="form-control required" placeholder="Number"></div>
                                        <div class="control-group form-group mb-0"><label class="form-label">Address</label> <input type="text" class="form-control required" placeholder="Address"></div>
                            
                            </div>
                        </div>

</form>

@endsection