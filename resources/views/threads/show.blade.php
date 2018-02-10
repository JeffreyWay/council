@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container mx-auto">
            <div class="flex">
                {{-- <div class="w-1/4 mr-6">
                    <p class="mb-4">
                        This thread was published {{ $thread->created_at->diffForHumans() }} by
                        <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->username }}</a>, and currently
                        has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
                    </p>

                    <p>
                        <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

                        <button :class="classes(locked)"
                                v-if="authorize('isAdmin')"
                                @click="toggleLock"
                                v-text="locked ? 'Unlock' : 'Lock'"></button>

                        <button :class="classes(pinned)"
                                v-if="authorize('isAdmin')"
                                @click="togglePin"
                                v-text="pinned ? 'Unpin' : 'Pin'"></button>
                    </p>
                </div> --}}

                <div class="px-10 w-full border-l">
                    @include('breadcrumbs')

                    <div class="py-6">
                        @include ('threads._question')

                        <replies @added="repliesCount++" @removed="repliesCount--"></replies>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
