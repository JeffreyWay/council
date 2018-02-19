@extends('layouts.app')

@section('sidebar')
    <aside class="bg-grey-lightest p-6 pr-10 border-l border-r w-64">
        <div class="widget">
            <h4 class="widget-heading">Manage</h4>

            <ul class="list-reset text-sm">
                <li class="pb-3">
                    <a href="{{ route('admin.dashboard.index') }}" class="{{ Route::is('admin.dashboard.index') ? 'text-blue font-bold' : '' }}">Dashboard</a>
                </li>

                <li class="pb-3">
                    <a href="{{ route('admin.channels.index') }}" class="{{ Route::is('admin.channels.index') ? 'text-blue font-bold' : '' }}">Channels</a>
                </li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="py-6">
        @yield('administration-content')
    </div>
@endsection
