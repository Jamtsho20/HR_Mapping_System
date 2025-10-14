@extends('layouts.app')
@section('page-title', 'Retirement Benefit Nomination')
@section('content')

<form action="{{ route('retirement-benefit-nomination.store') }}" method="POST" class="button-control" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <input type="hidden" name="employee_id" value="{{ auth()->id() }}">
            <input type="hidden" name="status" id="status" value="1">
            @include('sifa.sifa-registration.forms.personalinfo')
            <hr>
            <label for=""><strong>Retirement Benefit Nomination </strong></label><small>(s)<i> (I hereby nominate the person(s) mentioned below to have the conferred rights to claim my retirement benefits upon my demise, as per the percentage of shares prescribed)</i></small>
            <br><br>
            <div class="table-responsive criteria">
                <table id="retirement_benefit" class="table table-condensed table-striped table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th width="20%">Name</th>
                            <th width="20%">Relationship</th>
                            <th width="20%">CID</th>
                            <th width="20%">Percentage of Share</th>
                            <th width="20%">Attachments(CID/Birth Certificate)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (old('retirement_benefit') == '')
                        <tr>
                            <td class="text-center">
                                <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                            </td>
                            <td>
                                <input type="text" name="retirement_benefit[AAAAA][nominee_name]" class="form-control form-control-sm resetKeyForNew">
                            </td>
                            <td>
                                <input type="text" name="retirement_benefit[AAAAA][relation_with_employee]" class="form-control form-control-sm resetKeyForNew">
                            </td>
                            <td>
                                <input type="text" name="retirement_benefit[AAAAA][cid_number]" class="form-control form-control-sm resetKeyForNew">
                            </td>
                            <td>
                                <input type="number" name="retirement_benefit[AAAAA][percentage_of_share]" class="form-control form-control-sm resetKeyForNew">
                            </td>
                            <td>
                                <input type="file" name="retirement_benefit[AAAAA][attachment]" class="form-control form-control-sm resetKeyForNew">
                            </td>

                        </tr>
                        @else
                        @foreach (old('retirement_benefit') as $key => $value)
                        <tr>
                            <td class="text-center">
                                <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                            </td>
                            <td>
                                <input type="text" name="retirement_benefit[AAAAA{{ $key }}][nominee_name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('retirement_benefit[AAAAA'.$key.'][name]', $value['nominee_name'] ?? '') }}">
                            </td>
                            <td>
                                <input type="text" name="retirement_benefit[AAAAA{{ $key }}][relation_with_employee]" class="form-control form-control-sm resetKeyForNew" value="{{ old('retirement_benefit[AAAAA'.$key.'][relation_with_employee]', $value['relation_with_employee'] ?? '') }}">
                            </td>
                            <td>
                                <input type="text" name="retirement_benefit[AAAAA{{ $key }}][cid_number]" class="form-control form-control-sm resetKeyForNew" value="{{ old('retirement_benefit[AAAAA'.$key.'][cid_number]', $value['cid_number'] ?? '') }}">
                            </td>
                            <td>
                                <input type="number" name="retirement_benefit[AAAAA{{ $key }}][percentage_of_share]" class="form-control form-control-sm resetKeyForNew" value="{{ old('retirement_benefit[AAAAA'.$key.'][percentage_of_share]', $value['percentage_of_share'] ?? '') }}">
                            </td>
                            <td>
                                <input type="file" name="retirement_benefit[AAAAA{{ $key }}][attachment]" class="form-control form-control-sm resetKeyForNew">
                            </td>

                        </tr>
                        @endforeach
                        @endif
                        <tr class="notremovefornew">
                            <td colspan="5"></td>
                            <td class="text-right">
                                <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 12px"><i class="fa fa-plus"></i> Add New Row</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="form-group d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')

@endsection

@section('scripts')