@extends('layouts.app')
@section('page-title', 'Your Profile')
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ $user->name }}</h3>
            </div>
            <div class="block-content">
                <ul class="list-group list-group-unbordered mb-10">
                    <li class="list-group-item">
                        <b>Username</b> <a class="pull-right">{{ $user->username }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <a class="pull-right">{{ $user->email }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Assigned Role(s)</h3>
            </div>
            <div class="block-content p-0">
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