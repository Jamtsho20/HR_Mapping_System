@extends('layouts.app')
@section('page-title', 'Training Evaluations')

@section('buttons')
@if ($privileges->create)
<a href="{{ route('training-application.training-evaluations.create')}}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> New Evaluation Question
</a>
@endif
@endsection

@section('content')
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-md-4 form-group">
        <select name="training_list_id" class="form-control">
            <option value="">-- Select Training --</option>
            @foreach($trainingLists as $training)
            <option value="{{ $training->id }}" {{ request('training_list_id') == $training->id ? 'selected' : '' }}>
                {{ $training->title }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-group">
        <select name="evaluation_type_id" class="form-control">
            <option value="">-- Select Evaluation Type --</option>
            @foreach($evaluationTypes as $type)
            <option value="{{ $type->id }}" {{ request('evaluation_type_id') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-group">
        <input type="text" name="question" class="form-control" value="{{ request()->get('question') }}" placeholder="Search Question">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table text-nowrap border-bottom">
                        <thead class="thead-light">
                            <tr>
                                <th>Sl. No</th>
                                <th>Training</th>
                                <th>Evaluation Type</th>
                                <th>Question</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evaluations as $evaluation)
                            @if(is_null($evaluation->parent_id)) {{-- Show only parent (main title) rows --}}
                            <tr>
                                <td>{{ $evaluations->firstItem() + $loop->index }}</td>
                                <td>{{ $evaluation->trainingList->title ?? config('global.null_value') }}</td>
                                <td>{{ $evaluation->evaluationType->name ?? config('global.null_value') }}</td>

                                <!-- Main Question Title with + button -->
                                <td>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <strong>{{ $evaluation->question }}</strong>
                                        @if($evaluation->children && $evaluation->children->count() > 0)
                                        <button class="btn btn-sm btn-outline-primary toggle-subq"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#subq-{{ $evaluation->id }}">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        @endif
                                    </div>

                                    <!-- Collapsible Sub-Questions -->
                                    @if($evaluation->children && $evaluation->children->count() > 0)
                                    <div class="collapse mt-2" id="subq-{{ $evaluation->id }}">
                                        <ul class="mb-0 ps-4 border-start border-2 border-primary">
                                            @foreach($evaluation->children->sortBy('sequence') as $sub)
                                            <li>
                                                <span class="text-muted">(Q.{{ $sub->sequence }})</span>
                                                {{ $sub->question }}
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </td>


                                <!-- Created By -->
                                <td>{{ $evaluation->creator->name ?? '-' }}</td>

                                <!-- Action -->
                                <td class="text-center">
                                    @if ($privileges->edit)
                                    <a href="{{ route('training-application.training-evaluations.edit', $evaluation->id) }}"
                                        class="btn btn-sm btn-outline-success mb-1">
                                        <i class="fa fa-edit"></i> EDIT
                                    </a>
                                    @endif
                                    @if ($privileges->delete)
                                    <a href="#" class="delete-btn btn btn-sm btn-outline-danger"
                                        data-url="{{ route('training-application.training-evaluations.destroy', $evaluation->id) }}">
                                        <i class="fa fa-trash"></i> DELETE
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-danger">No Training Evaluations found</td>
                            </tr>
                            @endforelse
                        </tbody>


                    </table>
                    {{ $evaluations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-subq').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                // Delay icon toggle slightly so Bootstrap collapse animation starts first
                setTimeout(() => {
                    if (this.classList.contains('collapsed')) {
                        icon.classList.remove('fa-minus');
                        icon.classList.add('fa-plus');
                    } else {
                        icon.classList.remove('fa-plus');
                        icon.classList.add('fa-minus');
                    }
                }, 150);
            });
        });
    });
</script>
@endpush