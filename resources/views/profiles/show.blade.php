@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <avatar-form :user="{{ $profileUser }}"></avatar-form>
    </div>

    <activities :user="{{ $profileUser }}"></activities>
@endsection
