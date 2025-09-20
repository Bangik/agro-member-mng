@extends('layouts/contentNavbarLayout')

@section('title', 'Riwayat Aspirasi / Aduan')

@section('page-script')
    @vite('resources/assets/js/index-complaints-member.js')
@endsection

@section('content')
    <div class="card">
        <div class="row mx-1 my-3">
            <div class="col-md-12 col-12">
                <div class="d-flex align-items-center justify-content-md-end justify-content-center">
                    <div class="me-4">
                        <form action="{{ route('member.complaints.index') }}" method="GET" id="form-filter">
                            <label>
                                <input type="search" class="form-control form-control-sm" placeholder="Cari..."
                                    id="search" name="search" value="{{ request('search') }}" />
                            </label>
                        </form>
                    </div>
                    <div class="add-new">
                        <button class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#modalCenter">
                            <i class="ri-add-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                            <span class="d-none d-sm-inline-block"> Buat Aspirasi / Aduan </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode Aspirasi / Aduan</th>
                        <th>Judul Aspirasi / Aduan</th>
                        <th>Status</th>
                        <th>Tanggal RTL</th>
                        <th>Direspon Oleh</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($complaints as $complaint)
                        <tr>
                            <td>
                                @php
                                    $parts = explode('/', $complaint->code);
                                    $seq = $parts[0] ?? '';
                                    $month = isset($parts[2]) ? str_pad($parts[2], 2, '0', STR_PAD_LEFT) : '';
                                    $year = isset($parts[3]) ? substr($parts[3], -2) : '';
                                @endphp
                                {{ $seq }}.{{ $month }}.{{ $year }}
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
                                {{ $complaint->response_at ? \Carbon\Carbon::parse($complaint->response_at)->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td>{{ $complaint->user ? $complaint->user->name : '-' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></button>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('member.complaints.detail.pdf', $complaint->id) }}"
                                            class="dropdown-item" target="_blank">
                                            <i class="ri-file-download-line me-1"></i>
                                            Download PDF</a>
                                        <a href="{{ route('member.complaints.detail', $complaint->id) }}"
                                            class="dropdown-item">
                                            <i class="ri-eye-line me-1"></i>
                                            Detail</a>
                                        @if ($complaint->status === 'pending')
                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $complaint->id }}">
                                                <i class="ri-pencil-line me-1"></i>
                                                Edit</button>
                                            <button class="dropdown-item button-swal" data-id="{{ $complaint->id }}"
                                                data-name="{{ $complaint->title }}"><i
                                                    class="ri-delete-bin-6-line me-1"></i>
                                                Delete</button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($complaints->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center">No data available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{ $complaints->links('vendor.pagination.bootstrap-5') }}
    </div>


    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Buat Aspirasi / Aduan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('member.complaints.store') }}" method="POST" id="form-create-complaint">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" autofocus placeholder="Judul Aspirasi / Aduan"
                                        value="{{ old('title') }}">
                                    <label for="title">Judul Aspirasi / Aduan</label>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control h-px-100 @error('complaint') is-invalid @enderror" id="complaint" name="complaint"
                                        autofocus placeholder="Deskripsi">{{ old('complaint') }}</textarea>
                                    <label for="complaint">Deskripsi</label>
                                    @error('complaint')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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

    @foreach ($complaints as $complaint)
        <div class="modal fade" id="modalEdit{{ $complaint->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Aspirasi / Aduan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('member.complaints.update', $complaint->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" autofocus placeholder="Judul Aspirasi / Aduan"
                                            value="{{ old('title', $complaint->title) }}">
                                        <label for="title">Judul Aspirasi / Aduan</label>
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control h-px-100 @error('complaint') is-invalid @enderror" id="complaint" name="complaint"
                                            autofocus placeholder="Deskripsi">{{ old('complaint', $complaint->complaint) }}</textarea>
                                        <label for="complaint">Deskripsi</label>
                                        @error('complaint')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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
