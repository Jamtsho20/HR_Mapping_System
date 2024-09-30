@extends('layouts.app')
@section('page-title', 'Create Expense')
@section('content')

<form action="{{ route(apply-expense.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expense-type">Expense Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="expense-type" name="expense_type" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($expenses as $expense)
                            <option value="{{ $expense->name }}">{{ $expense->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expense_amount">Expense Amount <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="expense_amount" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="description" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="file">Upload File</label>
                        <input type="file" class="form-control form-control-sm" name="file">
                    </div>
                </div>
            </div>
        </div>
        <!--Conveyance Form-->
        @include('expense.apply.types.conveyance')


        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Create Expense</button>
            <a href="{{ url('expense/apply') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>

    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
    $('#expense-type').on('change', function() {
        var selection = $(this).val().toLowerCase();
        $(".expense-form").hide();
        switch (selection) {
            case "conveyance":
                $("#conveyance-form").show();
                break;
                // case "general expense":
                //     $("#general-expense-form").show();
                //     break;
                // case "internet expense":
                //     $("#internet-expense-form").show();
                //     break;
                // case "parking fee":
                //     $("#parking-fee-form").show();
                //     break;
                // case "telephone expense":
                //     $("#telephone-expense-form").show();
                //     break;
                // case "toll expense":
                //     $("#toll-expense-form").show();
                //     break;
            default:
                $(".expense-form").hide();
        }
    });
</script>
@endpush