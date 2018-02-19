@extends('layouts.app')

@section('content')
    <ais-index
        app-id="{{ config('scout.algolia.id') }}"
        api-key="{{ config('scout.algolia.key') }}"
        index-name="threads"
        query="{{ request('q') }}"
    >
        @include('breadcrumbs')

        <div class="flex py-6">
            <div class="mr-10">
                <div class="widget bg-grey-lightest border p-4">
                    <h4 class="widget-heading">Search</h4>

                    <ais-search-box>
                        <ais-input placeholder="Find a thread..." :autofocus="true" class="w-full"></ais-input>
                    </ais-search-box>
                </div>

                <div class="widget bg-grey-lightest border p-4">
                    <h4 class="widget-heading">
                        Filter By Channel
                    </h4>

                    <div class="panel-body">
                        <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
                    </div>
                </div>
            </div>

            <div class="w-3/4">
                <ais-results>
                    <template slot-scope="{ result }">
                        <li class="list-reset pb-3">
                            <a :href="result.path" class="text-blue link">
                                <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                            </a>
                        </li>
                    </template>
                </ais-results>
            </div>
        </div>
    </ais-index>
@endsection
