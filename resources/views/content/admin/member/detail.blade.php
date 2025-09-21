@extends('layouts/contentNavbarLayout')

@section('title', 'Management Anggota | Detail')

@section('page-style')
    <style>
        .info-table td {
            vertical-align: top;
            padding: 0px;
        }

        .info-table .col-label {
            width: 35%;
            font-weight: 600;
        }

        .info-table .col-colon {
            width: 5%;
            text-align: center;
        }

        .info-table .col-value {
            width: 65%;
        }

        .pre-line {
            white-space: pre-line;
        }
    </style>
@endsection

@php
    $fields = [
        'Nomor Anggota' => $member->reg_number,
        'NIK' => $member->national_id_number,
        'TTL' => $member->birth_place . ', ' . \Carbon\Carbon::parse($member->birth_date)->translatedFormat('d F Y'),
        'Email' => $member->email,
        'Jenis Kelamin' => $member->gender === 'male' ? 'Laki-laki' : 'Perempuan',
        'Alamat' => $member->address,
        'RT/RW' => ($member->rt ?? '-') . ' / ' . ($member->rw ?? '-'),
        'Desa/Kelurahan' => $member->village,
        'Kecamatan' => $member->district,
        'Kota/Kabupaten' => $member->city,
        'Provinsi' => $member->state,
        'Kode Pos' => $member->post_code,
        'Nomor HP' => $member->phone,
        'Agama' => $member->religion,
        'Golongan Darah' => $member->blood_type,
        'Status Pernikahan' => $member->is_married ? 'Menikah' : 'Belum Menikah',
        'Hobi' => $member->hobbies,
    ];
@endphp

@section('content')
    @php
        $activeContract = $contracts->where('end_date', '>=', now()->format('Y-m-d'))->first();
    @endphp
    <div class="row">
        <!-- User Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
            <!-- User Card -->
            <div class="card mb-6">
                <div class="card-body pt-12">
                    <div class="user-avatar-section">
                        <div class=" d-flex align-items-center flex-column">
                            @if ($member->pp_path)
                                <img class="img-fluid rounded mb-4" src="{{ $member->photoUrl() }}" height="120"
                                    width="120" alt="User avatar">
                            @else
                                <img class="img-fluid rounded mb-4" src="{{ asset('assets/img/avatars/1.png') }}"
                                    height="120" width="120" alt="User avatar">
                            @endif
                            <div class="user-info text-center">
                                <h5>{{ $member->name }}</h5>
                                @if ($contracts->where('end_date', '>=', now()->format('Y-m-d'))->count() > 0)
                                    <span class="badge rounded-pill bg-label-success me-1">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-label-danger me-1">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <h5 class="pb-4 border-bottom mb-4">Data Diri</h5>
                    <div class="info-container">
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm align-top info-table">
                                <colgroup>
                                    <col class="col-label">
                                    <col class="col-colon">
                                    <col class="col-value">
                                </colgroup>
                                <tbody>
                                    @foreach ($fields as $label => $value)
                                        <tr>
                                            <td class="fw-semibold">{{ $label }}</td>
                                            <td class="text-center">:</td>
                                            <td class="{{ $label === 'Alamat' ? 'pre-line' : '' }}">{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <h5 class="pb-4 border-bottom mb-4 mt-3">Data Pekerjaan</h5>
                    <div class="info-container">
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm align-top info-table">
                                <colgroup>
                                    <col class="col-label">
                                    <col class="col-colon">
                                    <col class="col-value">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">Status</td>
                                        <td class="text-center">:</td>
                                        <td>
                                            @if ($contracts->where('end_date', '>=', now()->format('Y-m-d'))->count() > 0)
                                                <span class="badge rounded-pill bg-label-success me-1">Aktif</span>
                                            @else
                                                <span class="badge rounded-pill bg-label-danger me-1">Tidak Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Tanggal Aktif</td>
                                        <td class="text-center">:</td>
                                        <td>{{ $activeContract ? \Carbon\Carbon::parse($activeContract->start_date)->translatedFormat('d F Y') : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Tanggal Off</td>
                                        <td class="text-center">:</td>
                                        <td>{{ $activeContract ? \Carbon\Carbon::parse($activeContract->end_date)->translatedFormat('d F Y') : '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /User Card -->
        </div>
        <!--/ User Sidebar -->

        <!-- User Content -->
        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
            <!-- Invoice table -->
            <div class="card mb-4">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="head-label">
                                <h5 class="card-title mb-0">Riwayat Kontrak</h5>
                            </div>
                            <div class="add-new">
                                <a href="{{ route('admin.members.detail.pdf', $member->id) }}" target="_blank"
                                    class="btn btn-primary waves-effect waves-light">
                                    <i class="ri-add-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                                    <span class="d-none d-sm-inline-block"> Print Profile </span>
                                </a>
                                <button class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                                    data-bs-target="#modalCenter">
                                    <i class="ri-add-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                                    <span class="d-none d-sm-inline-block"> Tambah Kontrak </span>
                                </button>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Kontrak</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>Bagian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contracts as $contract)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $contract->contract_number }} @if ($contract->deleted_at)
                                                <span class="badge bg-label-danger">Terhapus</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($contract->start_date)->format('d M Y') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($contract->end_date)->format('d M Y') }}
                                        </td>
                                        <td>
                                            {{ $contract->part }}
                                        </td>
                                        <td>
                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $contract->id }}">
                                                <i class="ri-pencil-line me-1"></i>
                                                Edit</button>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($contracts->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{ $contracts->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
            <!-- /Invoice table -->
        </div>
        <!--/ User Content -->
    </div>

    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Kontrak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.contracts.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="m_member_id" value="{{ $member->id }}">
                        <div class="row g-4">
                            <div class="col-md-12 mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="contract_number"
                                        class="form-control @error('contract_number') is-invalid @enderror"
                                        name="contract_number" placeholder="Nomor Kontrak" required
                                        value="{{ old('contract_number') }}">
                                    <label for="contract_number">Nomor Kontrak</label>
                                    @error('contract_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" id="start_date"
                                        class="form-control @error('start_date') is-invalid @enderror" placeholder="In"
                                        required>
                                    <label for="start_date">In</label>
                                    @error('start_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" id="end_date"
                                        class="form-control @error('end_date') is-invalid @enderror" name="end_date"
                                        placeholder="Out" required>
                                    <label for="end_date">Out</label>
                                    @error('end_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-floating form-floating-outline">
                                    <select id="part" class="form-select @error('part') is-invalid @enderror"
                                        name="part" required>
                                        <option value="">Pilih Bagian</option>
                                        @foreach ($parts as $part)
                                            <option value="{{ $part->name }}">{{ $part->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="part">Bagian</label>
                                    @error('part')
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
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal edit --}}
    @foreach ($contracts as $contract)
        <div class="modal fade" id="modalEdit{{ $contract->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Kontrak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.contracts.update', $contract->id) }}" method="POST">
                        <div class="modal-body">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="m_member_id" value="{{ $member->id }}">
                            <div class="row g-4">
                                <div class="col-md-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="contract_number" class="form-control"
                                            name="contract_number" placeholder="Nomor Kontrak" required
                                            value="{{ $contract->contract_number }}">
                                        <label for="contract_number">Nomor Kontrak</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" id="start_date" class="form-control" name="start_date"
                                            placeholder="In" required value="{{ $contract->start_date }}">
                                        <label for="start_date">In</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" id="end_date" class="form-control" name="end_date"
                                            placeholder="Out" required value="{{ $contract->end_date }}">
                                        <label for="end_date">Out</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <select id="part" class="form-select" name="part" required>
                                            <option value="">Pilih Bagian</option>
                                            @foreach ($parts as $part)
                                                <option value="{{ $part->name }}"
                                                    {{ $part->name === $contract->part ? 'selected' : '' }}>
                                                    {{ $part->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="part">Bagian</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
