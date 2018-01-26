@extends('admin.layout.app')

@section('administration-content')
    <ul class="list-inline">
        <li class="key-item">
            <span class="glyphicon glyphicon-star text-primary"></span><span class="key-item-label">Administrator</span>
        </li>
        <li class="key-item">
            <span class="glyphicon glyphicon-comment text-primary"></span><span class="key-item-label">View Threads</span>
        </li>
    </ul>
    <table class="table user-list">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Reputation</th>
            <th>Confirmed</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td>
                    {{$user->name}}
                    @if($user->isAdmin)
                        <span class="glyphicon glyphicon-star text-primary" title="{{ $user->name }} is an administrator"></span>
                    @endif
                </td>
                <td>{{$user->email}}</td>
                <td>{{$user->reputation}}</td>
                <td>
                    @if($user->confirmed)
                        <span class="glyphicon glyphicon-ok text-success"></span>
                    @else
                        <form action="{{ route('admin.confirm-user.update') }}" method="POST">
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <button type="submit" class="btn btn-xs btn-success">
                                    <span class="glyphicon glyphicon-ok"></span>
                                    Confirm
                                </button>
                            </div>
                        </form>
                    @endif
                </td>
                <td>
                    <form class="form-inline" action="{{ route('admin.suspend-user.update') }}" method="POST">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="form-group">
                            {{ csrf_field() }}
                            @if($user->active)
                                <span class="text-success">Active</span>
                                <button type="submit" class="btn btn-sm btn-danger">Suspend</button>
                            @else
                                <span class="text-danger">Suspended</span>
                                <button type="submit" class="btn btn-sm btn-success">Activate</button>
                            @endif
                        </div>
                    </form>
                </td>
                <td>
                    <a href="{{route('threads', ['by' => $user->name])}}" target="_blank" title="View Threads"><span class="glyphicon glyphicon-comment"></span></a>
                </td>
            </tr>
        @empty
            <tr>
                <td>Nothing here.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
