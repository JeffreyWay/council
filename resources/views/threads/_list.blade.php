@forelse ($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
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
                    </h4>

                    <h5>
                        Posted By: <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->username }}</a>
                    </h5>
                </div>

                <a href="{{ $thread->path() }}">
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                </a>
            </div>
        </div>

        <div class="panel-body">
            <thread-view :thread="{{ $thread }}" inline-template>
                <highlight :content="body"></highlight>
            </thread-view>
        </div>

        <div class="panel-footer">
            <div class="level">
                <div class="flex">
                    {{ $thread->visits }} Visits            
                </div>
                <a href="/threads/{{ $thread->channel->slug }}"><span class="label label-primary">{{ $thread->channel->name}}</span></a>            
            </div>
        </div>
    </div>
@empty
    <p>There are no relevant results at this time.</p>
@endforelse
