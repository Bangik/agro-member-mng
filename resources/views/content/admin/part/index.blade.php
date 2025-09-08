@extends('layouts/contentNavbarLayout')

@section('title', 'Management Bagian')

@section('page-script')
    @vite('resources/assets/js/index-parts.js')
@endsection

@section('content')
    <div class="card">
        <div class="row mx-1 my-3">
            <div class="col-md-12 col-12">
                <div class="d-flex align-items-center justify-content-md-end justify-content-center">
                    <div class="me-4">
                        <form action="{{ route('admin.parts.index') }}" method="GET" id="form-filter">
                            <label>
                                <input type="search" class="form-control form-control-sm" placeholder="Cari Berdasarkan Nama"
                                    id="search" name="search" value="{{ request('search') }}" />
                            </label>
                        </form>
                    </div>
                    <div class="add-new">
                        <button class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#modalCenter">
                            <i class="ri-add-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                            <span class="d-none d-sm-inline-block"> Tambah Bagian </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($parts as $part)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $part->name }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $part->id }}">
                                            <i class="ri-pencil-line me-1"></i>
                                            Edit</button>
                                        <button class="dropdown-item button-swal" data-id="{{ $part->id }}"
                                            data-name="{{ $part->name }}"><i class="ri-delete-bin-6-line me-1"></i>
                                            Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($parts->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center">No data available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{ $parts->links('vendor.pagination.bootstrap-5') }}
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Bagian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.parts.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row g-4">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="name" class="form-control" name="name"
                                        placeholder="Masukkan Nama" required>
                                    <label for="name">Nama</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal edit --}}
    @foreach ($parts as $part)
        <div class="modal fade" id="modalEdit{{ $part->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Bagian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.parts.update', ['id' => $part->id]) }}" method="POST">
                        <div class="modal-body">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="name" class="form-control" name="name"
                                            placeholder="Masukkan Nama" required value="{{ $part->name }}">
                                        <label for="name">Nama</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
