@extends('admin.layout.app')

@section('administration-content')
    <form method="POST" action="{{ route('admin.channels.update', ['channel' => $channel->slug]) }}">
        {{ method_field('PATCH') }}
        @include ('admin.channels._form')
    </form>
@endsection
