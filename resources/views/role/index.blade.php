@extends('layouts.app')

@push('plugin-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
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
                        <li class="breadcrumb-item active">Role</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title text-right">Daftar Role</h4>
                    @if(auth()->user()->can('role-create'))
                        <a href="{{ route('role.create') }}"  class="btn btn-primary">Tambah Data</a>
                    @endif
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover datatable">
                    <thead>
                        <th style="width: 25%">No</th>
                        <th style="width: 45%">Nama</th>
                        <th style="width: 30%">Aksi</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

@push('plugin-js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
@endpush

@push('custom-js')
<script>
    $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        info: true,
        filter: true,
        ajax:  {
            url : "{{ route('role.index') }}",
        },
        columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'name', name: 'name'},
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],
        language: {
            search: "Cari Nama Role:"
        }
    });
    function Delete(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function (isConfirm) {
            if (isConfirm.isConfirmed == true) {
                //ajax delete
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: "{{ route('role.index') }}/"+id,
                    data: {
                        id : id
                    },
                    type: 'DELETE',
                    success: function (response) {
                        console.log(response);
                        if (response.status === "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil dihapus'
                            }).then(function() {
                                window.location = "{{ url("role") }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Data gagal dihapus',
                                text: 'Something went wrong!',
                                footer: '<a href="">Why do I have this issue?</a>'
                            }).then(function() {
                                location.reload();
                            });
                        }
                    }
                });
            } else if(isConfirm == false) {
                console.log('cancel')
            }
        });
    }
</script>
@endpush