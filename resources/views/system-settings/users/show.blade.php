@extends('layouts.app')
@section('page-title', 'Showing User Details')
@section('buttons')
    <a href="{{ url('system-setting/users')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to User List</a>
@endsection
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $user->name }}</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Username</b> <a class="pull-right">{{ $user->username }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <a class="pull-right">{{ $user->email }}</a>
                    </li>
                </ul>
            </div>
            @if ($canUpdate === 1)
                <div class="card-footer">
                    <a href="{{ url('system-setting/users/' . $user->id . '/edit') }}" class="btn btn-outline-primary btn-block btn-sm"><b><i class="fa fa-edit"></i> Edit Record</b></a>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Assigned Role(s)</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-sm">
                    <thead class="thead-light">
                        <th class="text-center">#</th>
                        <th>Role(s)</th>
                    </thead>
                    <tbody>
                        @foreach ($user->roles as $role)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="">{{ $role->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection