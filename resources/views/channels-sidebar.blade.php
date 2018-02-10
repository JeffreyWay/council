<div class="w-1/4 border-l border-r p-6 bg-white">
    <div class="widget">
        <h4 class="widget-heading">Channels</h4>

        <ul class="list-reset">
            @foreach ($channels as $channel)
                <li class="text-xs pb-3 flex">
                    <span class="rounded-full h-3 w-3 mr-2" style="background: {{ $channel->color }}"></span>

                    <a href="{{ route('channels', $channel) }}" class="link">
                        {{ ucwords($channel->name) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
