@extends('layouts/contentNavbarLayout')

@section('title', 'Management Aspirasi / Aduan')

@section('page-script')
    @vite('resources/assets/js/index-complaints.js')
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
                        <form action="{{ route('admin.complaints.index') }}" method="GET" id="form-filter">
                            <label>
                                <input type="search" class="form-control form-control-sm" placeholder="Cari..."
                                    id="search" name="search" value="{{ request('search') }}" />
                            </label>
                            <label>
                                <select name="status" id="status" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>
                                        In Progress</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>
                                        Resolved</option>
                                </select>
                            </label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode Aspirasi / Aduan</th>
                        <th>Nama Pengadu</th>
                        <th>Judul Aspirasi / Aduan</th>
                        <th>Status</th>
                        <th>Tanggal RTL</th>
                        <th>Tanggal Resolved</th>
                        <th>Direspon Oleh</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($complaints as $complaint)
                        <tr>
                            <td>{{ $complaint->code }}</td>
                            <td>
                                <div class="d-flex justify-content-start align-items-center user-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar me-2">
                                            @if ($complaint->member->pp_path)
                                                <img src="{{ $complaint->member->photoUrl() }}" alt="Avatar"
                                                    class="rounded-circle">
                                            @else
                                                <span
                                                    class="avatar-initial rounded-circle {{ $listBg[array_rand($listBg)] }}">
                                                    {{ Illuminate\Support\Str::substr($complaint->member->name, 0, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="emp_name text-truncate h6 mb-0">{{ $complaint->member->name }}</span>
                                        <small class="emp_post text-truncate">
                                            {{ $complaint->member->reg_number }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $complaint->title }}</td>
                            <td>
                                @if ($complaint->status === 'pending')
                                    <span class="badge rounded-pill bg-warning">Pending</span>
                                @elseif ($complaint->status === 'in_progress')
                                    <span class="badge rounded-pill bg-info">In Progress</span>
                                @elseif ($complaint->status === 'resolved')
                                    <span class="badge rounded-pill bg-success">Resolved</span>
                                @endif
                            </td>
                            <td>
                                {{ $complaint->resolved_at ? \Carbon\Carbon::parse($complaint->resolved_at)->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td>
                                {{ $complaint->updated_at ? \Carbon\Carbon::parse($complaint->updated_at)->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td>{{ $complaint->user ? $complaint->user->name : '-' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></button>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('admin.complaints.detail.pdf', $complaint->id) }}"
                                            class="dropdown-item" target="_blank">
                                            <i class="ri-file-download-line me-1"></i>
                                            Download PDF</a>
                                        <a href="{{ route('admin.complaints.detail', $complaint->id) }}"
                                            class="dropdown-item">
                                            <i class="ri-pencil-line me-1"></i>
                                            Response Aspirasi / Aduan</a>
                                        <button class="dropdown-item button-swal" data-id="{{ $complaint->id }}"
                                            data-name="{{ $complaint->title }}"><i class="ri-delete-bin-6-line me-1"></i>
                                            Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($complaints->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center">No data available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{ $complaints->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection
