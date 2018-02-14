@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div>
            @include('breadcrumbs')

            <div class="py-6 leading-normal">
                @include ('threads._question')

                <replies @added="repliesCount++" @removed="repliesCount--"></replies>
            </div>
        </div>
    </thread-view>
@endsection
