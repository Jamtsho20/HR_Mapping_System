@extends('layouts.app')
@section('page-title', 'View Application details')
@section('buttons')
<a href="{{ url('approval/applications') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Approval List</a>
@endsection
@section('content')

@if ($tab == 1)
@include('approval.view.leave', ['leave' => $data, 'empDetails' => $empDetails])

@elseif ($tab == 2)
@include('approval.view.expense', ['expense' => $data, 'empDetails' => $empDetails])

@elseif ($tab == 3)
@include('approval.view.advance', ['advance' => $data, 'empDetails' => $empDetails])

@elseif ($tab == 6)
@include('approval.view.transfer_claim', ['transfer' => $data, 'empDetails' => $empDetails])

@elseif ($tab == 7)
@include('approval.view.travel_authorization', ['travelAuthorization' => $data, 'empDetails' => $empDetails])

@elseif ($tab == 8)
@php
$sifaDocuments = \App\Models\SifaDocument::where('sifa_registration_id', $data->id)->first() ?? [];
$user = empDetails($data->created_by);
@endphp
@include('approval.view.sifa', ['sifaRegistration' => $data, 'user' => $user, 'sifaDocuments' => $sifaDocuments])

@elseif ($tab == 9)
@include('approval.view.dsa_claim', ['dsa' => $data, 'empDetails' => $empDetails])


@endif

@endsection
@push('page_scripts')

@endpush