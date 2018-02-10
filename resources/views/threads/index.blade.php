@extends('layouts.app')

@section('content')
    <div class="px-10 bg-white w-full">
        @include('breadcrumbs')

        <div class="pt-6">
            @include ('threads._list')

            {{ $threads->render() }}
        </div>
    </div>


{{--                 <div class="widget">
                <h4 class="mb-2">Search</h4>
            </div>
--}}
{{--                 <form method="GET" action="/threads/search">
                <div class="form-group">
                    <input type="text" placeholder="Search for something..." name="q">
                </div>

                <div class="form-group">
                    <button class="btn btn-default" type="submit">Search</button>
                </div>
            </form>
--}}
@endsection
