@extends('admin.layout.app')

@section('administration-content')
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Reputation</th>
            <th>Confirmed</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->reputation}}</td>
                <td>
                    @if($user->confirmed)
                        <span class="glyphicon glyphicon-ok"></span>
                    @else
                        <form action="{{ route('admin.confirm-user.update') }}" method="POST">
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <span class="glyphicon glyphicon-remove"></span>
                                <button type="submit" class="btn btn-xs btn-primary">Confirm</button>
                            </div>
                        </form>
                    @endif
                </td>
                <td><a href="{{route('threads', ['by' => $user->name])}}" target="_blank">View Threads</a></td>
            </tr>
        @empty
            <tr>
                <td>Nothing here.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
