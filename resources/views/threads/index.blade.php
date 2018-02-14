@extends('layouts.app')

@section('content')
    <div class="px-10 bg-white flex-1">
        @include('breadcrumbs')

        <div class="pt-6">
            @include ('threads._list')

            {{ $threads->render() }}
        </div>
    </div>
@endsection
