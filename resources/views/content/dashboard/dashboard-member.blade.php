@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Member')

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
                        <ul class="list-unstyled mb-6">
                            <li class="mb-2">
                                <span class="h6">Nomor Anggota:</span>
                                <span>{{ $member->reg_number }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">NIK:</span>
                                <span>{{ $member->national_id_number }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Tempat, Tanggal Lahir:</span>
                                <span>{{ $member->birth_place }},
                                    {{ \Carbon\Carbon::parse($member->birth_date)->translatedFormat('d F Y') }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Email:</span>
                                <span>{{ $member->email }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Jenis Kelamin:</span>
                                <span>{{ $member->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Alamat:</span>
                                <span>{{ $member->address }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">RT/RW:</span>
                                <span>{{ $member->rt }} / {{ $member->rw }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Desa/Kelurahan:</span>
                                <span>{{ $member->village }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Kecamatan:</span>
                                <span>{{ $member->district }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Kota/Kabupaten:</span>
                                <span>{{ $member->city }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Provinsi:</span>
                                <span>{{ $member->state }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Kode Pos:</span>
                                <span>{{ $member->post_code }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Nomor HP:</span>
                                <span>{{ $member->phone }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Agama:</span>
                                <span>{{ $member->religion }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Golongan Darah:</span>
                                <span>{{ $member->blood_type }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Status Pernikahan:</span>
                                <span>{{ $member->is_married ? 'Menikah' : 'Belum Menikah' }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Hobi:</span>
                                <span>{{ $member->hobbies }}</span>
                            </li>
                        </ul>
                    </div>
                    <h5 class="pb-4 border-bottom mb-4">Data Pekerjaan</h5>
                    <div class="info-container">
                        <ul class="list-unstyled mb-6">
                            <li class="mb-2">
                                <span class="h6">Status:</span>
                                @if ($contracts->where('end_date', '>=', now()->format('Y-m-d'))->count() > 0)
                                    <span class="badge rounded-pill bg-label-success me-1">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-label-danger me-1">Tidak Aktif</span>
                                @endif
                            </li>
                            <li class="mb-2">
                                <span class="h6">Tanggal Aktif:</span>
                                <span>{{ $activeContract ? \Carbon\Carbon::parse($activeContract->start_date)->translatedFormat('d F Y') : '-' }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="h6">Tanggal Tidak Aktif:</span>
                                <span>{{ $activeContract ? \Carbon\Carbon::parse($activeContract->end_date)->translatedFormat('d F Y') : '-' }}</span>
                            </li>
                        </ul>
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
                        </div>
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
                                            {{ $contract->part->name }}
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
@endsection
