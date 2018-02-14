@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ $activity->subject->favorited->path() }}">
            {{ $profileUser->username }} favorited a reply.
        </a>
    @endslot

    @slot('body')
        {!! $activity->subject->favorited->body !!}
    @endslot
@endcomponent
