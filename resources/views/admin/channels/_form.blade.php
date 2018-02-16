{{ csrf_field() }}
<div class="mb-4">
    <label for="name" class="tracking-wide uppercase text-grey-dark text-xs block pb-2">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $channel->name) }}" required>
</div>

<div class="mb-4">
    <label for="description" class="tracking-wide uppercase text-grey-dark text-xs block pb-2">Description</label>
    <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $channel->description) }}" required>
</div>

<div class="mb-4">
    <label for="archived" class="tracking-wide uppercase text-grey-dark text-xs block pb-2">Status</label>

    <select name="archived" id="archived" class="form-control">
        <option value="0" {{ old('archived', $channel->archived) ? '' : 'selected' }}>Active</option>
        <option value="1" {{ old('archived', $channel->archived) ? 'selected' : '' }}>Archived</option>
    </select>
</div>

<div class="mb-4">
    <button type="submit" class="btn bg-blue">{{ $buttonText ?? 'Add Channel' }}</button>
</div>

@if (count($errors))
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
