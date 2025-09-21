@extends('layouts/contentNavbarLayout')

@section('title', 'Management Admin')

@section('page-script')
    @vite('resources/assets/js/index-admin.js')
@endsection

@section('content')
    <div class="card">
        <div class="row g-2 align-items-center my-3 mx-1">
            {{-- Filter --}}
            <div class="col-12 col-md">
                <form action="{{ route('admin.admin.index') }}" method="GET" id="form-filter">
                    <div class="row g-2 justify-content-md-end">
                        {{-- Search --}}
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="input-group input-group-sm">
                                <input type="search" class="form-control" id="search" name="search"
                                    placeholder="Cari berdasarkan nama" value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Status (Semua/Terhapus/Aktif) --}}
                        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                            <select id="only_trashed" name="only_trashed" class="form-select form-select-sm"
                                onchange="document.getElementById('form-filter').submit()">
                                <option value="all" {{ request('only_trashed') == 'all' ? 'selected' : '' }}>Semua
                                </option>
                                <option value="yes" {{ request('only_trashed') == 'yes' ? 'selected' : '' }}>Hanya
                                    Terhapus
                                </option>
                                <option value="no" {{ request('only_trashed') == 'no' ? 'selected' : '' }}>Hanya Aktif
                                </option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tombol Tambah --}}
            <div class="col-12 col-md-auto text-md-end">
                <button class="btn btn-primary w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#modalCenter">
                    <i class="ri-add-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                    <span class="d-none d-sm-inline-block">Tambah Admin</span>
                    <span class="d-inline d-sm-none">Tambah</span>
                </button>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($admins as $admin)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <span class="emp_name text-truncate h6 mb-0">
                                    {{ $admin->name }} @if ($admin->trashed())
                                        <span class="badge bg-label-danger">Terhapus</span>
                                    @endif
                                </span>
                            </td>
                            <td>
                                {{ $admin->email }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $admin->id }}">
                                            <i class="ri-pencil-line me-1"></i>
                                            Edit</button>
                                        @if ($admin->trashed())
                                            <form action="{{ route('admin.admin.restore', ['id' => $admin->id]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item"><i
                                                        class="ri-restart-line me-1"></i>
                                                    Restore</button>
                                            </form>
                                        @else
                                            @if (auth()->user()->id !== $admin->id && auth()->user()->role === 'superadmin')
                                                <button class="dropdown-item button-swal" data-id="{{ $admin->id }}"
                                                    data-name="{{ $admin->name }}"><i
                                                        class="ri-delete-bin-6-line me-1"></i>
                                                    Delete</button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($admins->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{ $admins->links('vendor.pagination.bootstrap-5') }}
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.admin.store') }}" method="POST">
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

                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="email" id="email" class="form-control" name="email"
                                        placeholder="Masukkan Email" required>
                                    <label for="email">Email</label>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select id="role" class="form-select" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="superadmin">Super Admin</option>
                                </select>
                                <label for="role">Role</label>
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
    @foreach ($admins as $admin)
        <div class="modal fade" id="modalEdit{{ $admin->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.admin.update', $admin->id) }}" method="POST">
                        <div class="modal-body">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="name" class="form-control" name="name"
                                            placeholder="Masukkan Nama" required value="{{ $admin->name }}">
                                        <label for="name">Nama</label>
                                    </div>
                                </div>
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="email" id="email" class="form-control" name="email"
                                            placeholder="Masukkan Email" required value="{{ $admin->email }}">
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <select id="role" class="form-select" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ $admin->role == 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="superadmin" {{ $admin->role == 'superadmin' ? 'selected' : '' }}>
                                            Super Admin</option>
                                    </select>
                                    <label for="role">Role</label>
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
