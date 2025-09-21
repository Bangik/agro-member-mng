@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Member')


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
                <div class="card-header py-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md">
                            <h5 class="card-title mb-0 text-center text-md-start">Riwayat Kontrak</h5>
                        </div>

                        <div class="col-12 col-md-auto">
                            <div
                                class="d-flex flex-column flex-sm-row flex-md-nowrap justify-content-center justify-content-md-end gap-2">
                                <a href="{{ route('member.profile.kta') }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="ri-printer-line me-1 d-none d-sm-inline"></i>
                                    <span class="d-none d-sm-inline">Print KTA</span>
                                    <span class="d-inline d-sm-none">Print KTA</span>
                                </a>

                                <a href="{{ route('member.profile.pdf') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-printer-line me-1 d-none d-sm-inline"></i>
                                    <span class="d-none d-sm-inline">Print Profile</span>
                                    <span class="d-inline d-sm-none">Print Profile</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        {{-- <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="head-label">
                                <h5 class="card-title mb-0">Riwayat Kontrak</h5>
                            </div>
                            <div class="add-new">
                                <a href="{{ route('member.profile.kta') }}" class="btn btn-primary waves-effect waves-light"
                                    target="_blank">
                                    <i class="ri-add-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                                    <span class="d-none d-sm-inline-block"> Print KTA </span>
                                </a>
                                <a href="{{ route('member.profile.pdf') }}" class="btn btn-primary waves-effect waves-light"
                                    target="_blank">
                                    <i class="ri-add-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                                    <span class="d-none d-sm-inline-block"> Print Profile </span>
                                </a>
                            </div>
                        </div> --}}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Kontrak</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>Bagian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contracts as $contract)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $contract->contract_number }}
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
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Overview Aspirasi / Aduan</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-6">
                        <div class="col-md-4 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-warning rounded shadow-xs">
                                        <i class="ri-pie-chart-2-line ri-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('admin.complaints.index') . '?status=pending' }}"
                                        class="mb-0 text-warning">Belum
                                        Ditindaklanjuti</a>
                                    <h5 class="mb-0">{{ $complaintPending }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-info rounded shadow-xs">
                                        <i class="ri-group-line ri-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('admin.complaints.index') . '?status=in_progress' }}"
                                        class="mb-0 text-info">Proses</a>
                                    <h5 class="mb-0">{{ $complaintInProgress }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-success rounded shadow-xs">
                                        <i class="ri-check-line ri-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('admin.complaints.index') . '?status=resolved' }}"
                                        class="mb-0">Sudah Ditindaklanjuti</a>
                                    <h5 class="mb-0">{{ $complaintResolved }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ User Content -->
    </div>
@endsection
