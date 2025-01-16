@extends('layouts.app')
@section('page-title', 'Create Advance Loan Types')
@section('content')

<form action="{{ route('types.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Advance Types <span class="text-danger"></span></label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required="required">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="code">Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label mt-4">Status</label>
                        <label class="custom-switch">
                            <!-- Hidden input to pass '0' when checkbox is unchecked -->
                            <input type="hidden" name="status" value="0">
                            <!-- Checkbox to pass '1' when checked, and retain old value -->
                            <input type="checkbox" name="status" class="custom-switch-input form-control form-control-sm"
                                value="1" {{ old('status.is_active') == '1' ? 'checked' : '' }} />
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">is Active</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-upload"></i> Save
            </button>
            <a href="{{ url('advance-loan/types') }}" class="btn btn-danger">
                <i class="fa fa-undo"></i> CANCEL
            </a>
        </div>
    </div>
</form>

<script>
    document.getElementById('name').addEventListener('input', function() {
        let advancetype = this.value.trim();
        
        // Split the input into words
        let words = advancetype.split(' ');

        // Initialize code to an empty string
        let code = '';

        if (words.length > 0) {
            // Get the first three characters from the first word
            let firstWord = words[0].slice(0, 3).toUpperCase();
            code += firstWord;
        }

        if (words.length > 1) {
            // Get the first three characters from the second word
            let secondWord = words[1].slice(0, 3).toUpperCase();
            code += '_' + secondWord;
        }

        // Set the generated code to the 'code' input field
        document.getElementById('code').value = code;
    });
</script>

@endsection
