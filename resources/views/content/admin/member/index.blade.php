@extends('layouts/contentNavbarLayout')

@section('title', 'Management Anggota')

@section('page-script')
    @vite('resources/assets/js/index-members.js')
@endsection

@section('content')
    @php
        $listBg = ['bg-label-primary', 'bg-label-warning', 'bg-label-success'];
    @endphp
    <div class="card">
        <div class="row mx-1 my-3">
            <div class="col-md-12 col-12">
                <div class="d-flex align-items-center justify-content-md-end justify-content-center">
                    <div class="me-4">
                        <form action="{{ route('admin.members.index') }}" method="GET" id="form-filter">
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
                            <span class="d-none d-sm-inline-block"> Import Anggota </span>
                        </button>
                        <a href="{{ route('admin.members.create') }}" class="btn btn-primary waves-effect waves-light">
                            <i class="ri-add-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                            <span class="d-none d-sm-inline-block"> Tambah Anggota </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No Induk</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>JK</th>
                        <th>Status Aktif</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($members as $member)
                        <tr>
                            <td>
                                <div class="d-flex justify-content-start align-items-center user-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar me-2">
                                            @if ($member->pp_path)
                                                <img src="{{ $member->photoUrl() }}" alt="Avatar" class="rounded-circle">
                                            @else
                                                <span
                                                    class="avatar-initial rounded-circle {{ $listBg[array_rand($listBg)] }}">
                                                    {{ Illuminate\Support\Str::substr($member->name, 0, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="emp_name text-truncate h6 mb-0">{{ $member->name }}</span>
                                        <small class="emp_post text-truncate">
                                            {{ $member->reg_number }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $member->national_id_number }}
                            </td>
                            <td>
                                {{ $member->email }}
                            </td>
                            <td>
                                {{ $member->phone }}
                            </td>
                            <td>
                                {{ $member->gender === 'male' ? 'L' : 'P' }}
                            </td>
                            <td>
                                {{ $member->contracts->where('end_date', '>=', now()->format('Y-m-d'))->count() > 0 ? 'Aktif' : 'Tidak Aktif' }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('admin.members.detail', ['id' => $member->id]) }}"><i
                                                class="ri-eye-line me-1"></i>
                                            Detail</a>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.members.edit', ['id' => $member->id]) }}"><i
                                                class="ri-pencil-line me-1"></i>
                                            Edit</a>
                                        <button class="dropdown-item button-swal" data-id="{{ $member->id }}"
                                            data-name="{{ $member->name }}"><i class="ri-delete-bin-6-line me-1"></i>
                                            Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($members->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">No data available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{ $members->links('vendor.pagination.bootstrap-5') }}
    </div>

    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Import Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.members.import') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        {{-- download format --}}
                        <a href="{{ asset('assets/template-import-anggota.xlsx') }}"
                            class="btn btn-outline-primary mb-3">Download Format</a>
                        @csrf
                        <div class="row g-4">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="file" id="file" class="form-control" name="file"
                                        placeholder="File Excel" required>
                                    <label for="file">File</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
