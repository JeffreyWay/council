@if (auth()->check())
    <modal name="new-thread" height="auto" transition="slide">
        <form method="POST" action="/threads" class="p-6 py-8">
            {{ csrf_field() }}

            <div class="flex mb-6 -mx-4">
                <div class="flex-1 px-4">
                    <label for="title" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Title</label>
                    <input type="text" class="w-full p-2 leading-normal" id="title" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="flex-1 px-4">
                    <label for="channel_id" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Channel</label>

                    <select name="channel_id" id="channel_id" class="block appearance-none w-full bg-white rounded-none border border-grey-light text-grey-darker py-2 px-4 leading-normal pr-8" required>
                        <option value="">Choose One...</option>

                        @foreach ($channels as $channel)
                            <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                {{ $channel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <wysiwyg name="body"></wysiwyg>
            </div>

            <div class="mb-6">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
            </div>

            <div class="flex justify-end">
                <a href="#" class="btn mr-4" @click="$modal.hide('new-thread')">Cancel</a>
                <button type="submit" class="btn is-green">Publish</button>
            </div>

            @if (count($errors))
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </form>
    </modal>
@endif
