@extends('backend.layouts.main')

@section('content')
<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Create Company</h3>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">

        <form action="{{ route('perusahaan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">Save</button>
            <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Cancel</a>
        </form>

    </div>
</div>
@endsection