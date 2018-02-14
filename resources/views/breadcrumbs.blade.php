<nav class="text-xs py-3 pt-6 text-grey rounded rounded-b-none">
    <ol class="list-reset flex">
        <li>
            @if (Route::is('threads') && empty(Request::query()))
                All Threads
            @else
                <a href="{{ route('threads') }}" class="text-blue font-bold">All Threads</a>
            @endif
        </li>

        @if (Route::is('search.show'))
            <li><span class="mx-2">&#10095;</span></li>
            <li>Search</li>
        @endif

        @if (Route::is('channels'))
            <li><span class="mx-2">&#10095;</span></li>
            <li>{{ ucwords($channel->name) }}</li>
        @endif

        @if (request()->has('popular'))
            <li><span class="mx-2">&#10095;</span></li>
            <li>Popular</li>
        @endif

        @if (request()->has('unanswered'))
            <li><span class="mx-2">&#10095;</span></li>
            <li>Unanswered</li>
        @endif

        @if (Route::is('threads.show'))
            <li><span class="mx-2">&#10095;</span></li>
            <li>
                <a href="{{ route('channels', $thread->channel) }}" class="text-blue font-bold">
                    {{ ucwords($thread->channel->name) }}
                </a>
            </li>

            <li><span class="mx-2">&#10095;</span></li>
            <li>{{ $thread->title }}</li>
        @endif
    </ol>
</nav>
