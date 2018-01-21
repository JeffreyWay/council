@if (count($trending))
    <div class="panel panel-default">
        <div class="panel-heading">
            Trending Threads
        </div>

        <div class="panel-body">
            <ul class="list-group">
                @foreach ($trending as $thread)
                    <li class="list-group-item">
                        <a href="{{ url($thread->path) }}">
                            {{ $thread->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif