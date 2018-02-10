{{-- Editing the question. --}}
<div class="" v-if="editing">
    <div class="level">
        <input type="text" class="form-control" v-model="form.title">
    </div>

    <div class="form-group">
        <wysiwyg v-model="form.body"></wysiwyg>
    </div>

    <div class="flex">
        <button class="btn btn-xs level-item" @click="editing = true" v-show="! editing">Edit</button>
        <button class="btn btn-primary btn-xs level-item" @click="update">Update</button>
        <button class="btn btn-xs level-item" @click="resetForm">Cancel</button>

        @can ('update', $thread)
            <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-link">Delete Thread</button>
            </form>
        @endcan
    </div>
</div>


{{-- Viewing the question. --}}
<div class="" v-else>
    <div class="flex">
        <img src="{{ $thread->creator->avatar_path }}"
             alt="{{ $thread->creator->username }}"
             width="36"
             height="36"
             class="mr-1 bg-blue-darker rounded-full p-2">

        <div class="flex-1 border-b pb-4 ml-4">
            <h1 class="text-blue mb-1 text-2xl font-normal">{{ $thread->title }}</h1>

            <span class="flex text-xs mb-4">
                Posted by <a href="{{ route('profile', $thread->creator) }}">
                    {{ $thread->creator->username }} ({{ $thread->creator->reputation }} XP)
                </a>
            </span>

            <highlight :content="body"></highlight>
        </div>
    </div>

{{--     <div class="" v-if="authorize('owns', thread)">
        <button class="btn btn-xs" @click="editing = true">Edit</button>
    </div>
 --}}</div>
