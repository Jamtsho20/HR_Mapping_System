@extends('layouts.app')
@section('page-title', 'Create New Condition Fields')
@section('content')

<form action="{{ route('condition-fields.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mas_approval_head_id">Approval Head <span class="text-danger">*</span></label>
                        <select class="form-control" name="mas_approval_head_id">
                            <option value="">-- Select Head--</option>
                            @foreach($heads as $head)
                            <option value="{{ $head->id }}" {{ old('mas_approval_head_id') == $head->id ? 'selected' : '' }}>
                                {{ $head->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Field Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="label">Field Label<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="label" value="{{ old('label') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>Has Employee Field </label>
                    <div>
                        <!-- Hidden input to ensure 0 is passed if checkbox is unchecked -->
                        <input type="hidden" name="has_employee_field" value="0">
                        <input type="checkbox" id="chkIsInformationOnly" value="1" {{ old('leave_policy.has_employee_field', 1) ? 'checked' : '' }} name="has_employee_field" />
                    </div>
                </div>
                
            </div>




            <br><br>
            <div class="card-footer">
                @include('layouts.includes.buttons', [
                'buttonName' => 'SAVE',
                'cancelUrl' => url('system-setting/approving-authorities') ,
                'cancelName' => 'CANCEL'
                ])

            </div>
        </div>
    </div>
</form>




@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush
