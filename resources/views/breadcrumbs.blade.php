<nav class="text-sm mb-3">
    <ol class="list-reset flex text-grey-dark">
        <li><a href="{{ route('threads') }}" class="text-blue font-bold">All Threads</a></li>

        @if (Route::is('channels'))
            <li><span class="mx-2">/</span></li>
            <li>{{ ucwords($channel->name) }}</a></li>
        @endif

        @if (request()->has('popular'))
            <li><span class="mx-2">/</span></li>
            <li>Popular</a></li>
        @endif

        @if (request()->has('unanswered'))
            <li><span class="mx-2">/</span></li>
            <li>Unanswered</a></li>
        @endif

        @if (Route::is('threads.show'))
            <li><span class="mx-2">/</span></li>
            <li>
                <a href="{{ route('channels', $thread->channel) }}">
                    {{ ucwords($thread->channel->name) }}
                </a>
            </li>

            <li><span class="mx-2">/</span></li>
            <li>{{ $thread->title }}</li>
        @endif
    </ol>
</nav>
