@forelse ($threads as $thread)
    <div class="mb-6 pb-4 flex">
        <div class="mr-4">
            <img src="{{ $thread->creator->avatar_path }}"
                     alt="{{ $thread->creator->username }}"
                     class="w-8">
        </div>

        <div class="flex-1 border-b border-blue-lightest">
            <h3 class="text-xl font-normal mb-1">
                <a href="{{ $thread->path() }}" class="text-blue">
                    @if ($thread->pinned)
                        <span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>
                    @endif

                    @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                        <strong>
                            {{ $thread->title }}
                        </strong>
                    @else
                        {{ $thread->title }}
                    @endif
                </a>
            </h3>

            <p class="text-xs text-grey-darkest mb-4">
                Posted By: <a href="{{ route('profile', $thread->creator) }}" class="text-blue">{{ $thread->creator->username }}</a>
            </p>

            <thread-view :thread="{{ $thread }}" inline-template class="mb-4">
                <highlight :content="body"></highlight>
            </thread-view>

            <div class="flex items-center text-xs mb-6">
                <a class="btn py-1 px-2 mr-2" href="/threads/{{ $thread->channel->slug }}">{{ $thread->channel->name}}</a>

                <span class="mr-2">{{ $thread->visits }} Views</span>

                <a href="{{ $thread->path() }}" class="mr-2">
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                </a>

                <a class="btn ml-auto is-outlined text-grey-darkest py-2" href="{{ $thread->path() }}">read more</a>
            </div>
        </div>
    </div>
@empty
    <p>There are no relevant results at this time.</p>
@endforelse
