@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="flex">
            <aside class="w-1/4 bg-grey-lighter">
                <div class="widget border-b-0">
                    <button class="btn is-green w-full">Add New Thread</button>
                </div>

                <div class="widget">
                    <h4 class="mb-2 pb-2 text-xs uppercase text-blue-darkest">Browse</h4>

                    <ul class="list-reset text-sm">
                        <li class="pb-2">
                            <a href="/threads" class="flex items-center">
                                @include ('svgs.icons.all-threads', ['class' => 'mr-2'])
                                All Threads
                            </a>
                        </li>

                        @if (auth()->check())
                            <li class="pb-2">
                                <a href="/threads?by={{ auth()->user()->username }}">My Threads</a></li>
                            </li>
                        @endif

                        <li class="pb-2">
                            <a href="/threads?popular=1" class="flex items-center">
                                @include ('svgs.icons.star', ['class' => 'mr-2'])
                                Popular Threads
                            </a>
                        </li>

                        <li>
                            <a href="/threads?unanswered=1" class="flex items-center">
                                @include ('svgs.icons.question', ['class' => 'mr-2'])
                                Unanswered Threads
                            </a>
                        </li>
                    </ul>
                </div>

                @if (count($trending))
                    <div class="widget">
                        <h4 class="mb-2 pb-2 text-xs uppercase text-blue-darkest">Trending</h4>

                        <ul class="list-reset">
                            @foreach ($trending as $thread)
                                <li class="pb-2 text-sm">
                                    <a href="{{ url($thread->path) }}">
                                        {{ $thread->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>

            <div class="mx-8 w-full">
                @include('breadcrumbs')

                <div class="bg-white py-6 px-8 border rounded">
                    @include ('threads._list')

                    {{ $threads->render() }}
                </div>
            </div>

            <div>
                <div class="widget">
                    <h4 class="mb-2 pb-2 text-xs uppercase text-blue-darkest">Channels</h4>

                    <ul class="list-reset">
                        @foreach ($channels as $channel)
                            <li class="text-xs pb-3 flex">
                                <span class="rounded-full h-3 w-3 mr-2" style="background: {{ $channel->color }}"></span>

                                <a href="{{ route('channels', $channel) }}">
                                    {{ ucwords($channel->name) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
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
            </div>
        </div>
    </div>
@endsection
