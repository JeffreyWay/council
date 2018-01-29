@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <ul class="nav nav-pills nav-stacked">
                    <li role="presentation" class="{{ setClassIfRoute('admin.dashboard.index', 'active') }}">
                        <a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                    </li>
                    <li role="presentation" class="{{ setClassIfRoute('admin.channels.index', 'active') }}">
                        <a href="{{ route('admin.channels.index') }}">Channels</a>
                    </li>
                    <li role="presentation" class="{{ setClassIfRoute('admin.users.index', 'active') }}">
                        <a href="{{ route('admin.users.index') }}">Users</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @yield('administration-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
