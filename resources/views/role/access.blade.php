@extends('layouts.app')

@push('plugin-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Role</h3>
                <p class="text-subtitle text-muted">Role</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role</a></li>
                        <li class="breadcrumb-item active">Access</li>
                    </ol>
                </nav>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="title">
                        Form Access
                    </h4>
                </div>
                <form action="{{ route('role.send-access') }}" method="post" autocomplete="off"> @csrf
                    <input type="hidden" name="id" value="{{ $detail->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $detail->name) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Access</label>
                            <select class="select-multiple form-control" name="permission[]" multiple="multiple">
                                @foreach ($permission as $row)
                                    @if ($row->id == in_array($row->id, $rolePermissions))
                                        <option value="{{ $row->id }}" selected>{{ $row->name }}</option>
                                    @else
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-end">
                            <a href="{{ route('role.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection

@push('plugin-js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
@push('custom-js')
    <script>
        $(document).ready(function() {
            $('.select-multiple').select2();
        });
    </script>
@endpush