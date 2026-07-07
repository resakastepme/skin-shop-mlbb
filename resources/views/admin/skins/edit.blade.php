@extends('layouts.admin')

@section('title', __('app.edit_skin'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ __('app.edit_skin') }}: {{ $skin->name }}</h1>
        <a href="{{ route('admin.skins.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>{{ __('app.back') }}
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.skins.update', $skin) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('admin.skins.partials.form', ['skin' => $skin])
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                    <a href="{{ route('admin.skins.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
