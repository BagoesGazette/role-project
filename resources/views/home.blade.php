@extends('layouts.app')

@push('plugin-css')
    <link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard</h3>
                <p class="text-subtitle text-muted">Dashboard</p>
            </div>
        </div>
    </div>
    @role('admin')
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon blue">
                                <i class="iconly-boldProfile"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">User</h6>
                            <h6 class="font-extrabold mb-0">{{ $user->count() }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Selamat {{ Carbon\Carbon::now()->timezone('Asia/Jakarta')->locale('id_ID')->isoFormat('A') }}, {{ Auth::user()->name }}!
                </h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">Semua sistem berjalan dengan lancar.</p>
            </div>
        </div>
    </section>
</div>
@endsection