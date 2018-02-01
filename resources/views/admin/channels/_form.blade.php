{{ csrf_field() }}
<div class="form-group">
    <label for="name">Name:</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $channel->name) }}" required>
</div>

<div class="form-group">
    <label for="description">Description:</label>
    <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $channel->description) }}" required>
</div>

<div class="form-group">
    <label for="archived">Status:</label>

    <select name="archived" id="archived" class="form-control">
        <option value="0" {{ $channel->archived ? '' : 'selected' }}>Active</option>
        <option value="1" {{ $channel->archived ? 'selected' : '' }}>Archived</option>
    </select>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $buttonText ?? 'Add Channel' }}</button>
</div>

@if (count($errors))
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif