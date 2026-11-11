@extends('backend.layouts.main')

@section('content')
<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Data Companies</h3>
    <a href="{{ route('perusahaan.create') }}" class="btn btn-primary">+ Create Company</a>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $key => $company)
                <tr>
                    <td>{{ $companies->firstItem() + $key }}</td>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->address }}</td>
                    <td>
                        <a href="{{ route('perusahaan.edit', $company->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('perusahaan.destroy', $company->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada data perusahaan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $companies->links() }}
        </div>

    </div>
</div>
@endsection