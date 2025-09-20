@extends('layouts/contentNavbarLayout')

@section('title', 'Management Anggota')

@section('page-script')
    @vite('resources/assets/js/index-members.js')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Biar aman antar user, bedakan storage per user
            const STORAGE_KEY = 'members:selected:{{ auth()->id() }}';

            const selectAll = document.getElementById('select-all');
            const clearBtn = document.getElementById('clearSelected');
            const summaryEl = document.getElementById('selectedSummary');
            const btnExportKta = document.getElementById('btnExportKtaPdf');
            const hiddenInput = document.getElementById('member_ids_json');
            const form = document.getElementById('bulkExportForm');

            function loadSet() {
                try {
                    const raw = localStorage.getItem(STORAGE_KEY);
                    const arr = raw ? JSON.parse(raw) : [];
                    return new Set(arr);
                } catch (e) {
                    return new Set();
                }
            }

            function saveSet(set) {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(set)));
            }

            function visibleCheckboxes() {
                return Array.from(document.querySelectorAll('.row-check'));
            }

            function refreshUI() {
                const set = loadSet();
                // tandai yang visible
                visibleCheckboxes().forEach(cb => {
                    cb.checked = set.has(cb.value);
                });
                // update select-all (untuk halaman ini)
                const vis = visibleCheckboxes();
                const allChecked = vis.length > 0 && vis.every(cb => cb.checked);
                if (selectAll) selectAll.checked = allChecked;

                // update summary & tombol
                const total = set.size;
                summaryEl.textContent = `${total} anggota dipilih`;
                // btnExport.disabled = total === 0;
            }

            // Init (mark selected on this page)
            refreshUI();

            // Toggle per-row
            document.addEventListener('change', (e) => {
                if (!e.target.classList?.contains('row-check')) return;

                const set = loadSet();
                const id = e.target.value;
                if (e.target.checked) set.add(id);
                else set.delete(id);
                saveSet(set);
                refreshUI();
            });

            // Select-all (untuk halaman ini saja)
            selectAll?.addEventListener('change', () => {
                const set = loadSet();
                visibleCheckboxes().forEach(cb => {
                    cb.checked = selectAll.checked;
                    if (selectAll.checked) set.add(cb.value);
                    else set.delete(cb.value);
                });
                saveSet(set);
                refreshUI();
            });

            // Bersihkan semua pilihan (lintas halaman)
            clearBtn?.addEventListener('click', () => {
                localStorage.removeItem(STORAGE_KEY);
                refreshUI();
            });

            // Saat submit export → isi hidden input dari localStorage
            form?.addEventListener('submit', () => {
                const set = loadSet();
                hiddenInput.value = JSON.stringify(Array.from(set));
            });

            // Export KTA PDF
            btnExportKta.addEventListener('click', () => {
                const ids = Array.from(loadSet());
                if (!ids.length) return;

                hiddenInput.value = JSON.stringify(ids);
                form.action = "{{ route('admin.members.export.kta.pdf') }}";
                form.submit();
            });
        });
    </script>
@endsection

@section('content')
    @php
        $listBg = ['bg-label-primary', 'bg-label-warning', 'bg-label-success'];
    @endphp
    <div class="card">
        <div class="row mx-1 my-3">
            <div class="col-md-12 col-12">
                <div class="d-flex align-items-center justify-content-md-end justify-content-center">
                    <div class="head-label">
                        <div id="selectedSummary" class="small text-muted me-3">0 anggota dipilih</div>
                    </div>
                    <div class="me-4">
                        <form action="{{ route('admin.members.index') }}" method="GET" id="form-filter">
                            <label>
                                <input type="search" class="form-control form-control-sm"
                                    placeholder="Cari Berdasarkan Nama" id="search" name="search"
                                    value="{{ request('search') }}" />
                            </label>
                            {{-- filter by status --}}
                            <label>
                                <select name="status" id="status" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak
                                        Aktif</option>
                                </select>
                            </label>
                            {{-- filter by trashed --}}
                            <label>
                                <select name="only_trashed" id="only_trashed" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                    <option value="all" {{ request('only_trashed') == 'all' ? 'selected' : '' }}>Semua
                                    </option>
                                    <option value="yes" {{ request('only_trashed') == 'yes' ? 'selected' : '' }}>Hanya
                                        Terhapus
                                    </option>
                                    <option value="no" {{ request('only_trashed') == 'no' ? 'selected' : '' }}>Hanya
                                        Aktif
                                    </option>
                                </select>
                            </label>
                        </form>
                    </div>
                    <div class="add-new">
                        <button class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#modalCenter">
                            <i class="ri-add-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                            <span class="d-none d-sm-inline-block"> Import </span>
                        </button>
                        {{-- export button --}}
                        <button class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#modalExport">
                            <i class="ri-file-download-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                            <span class="d-none d-sm-inline-block"> Export </span>
                        </button>
                        <a href="{{ route('admin.members.create') }}" class="btn btn-primary waves-effect waves-light">
                            <i class="ri-add-line me-0 me-sm-1 d-inline-block d-sm-none"></i>
                            <span class="d-none d-sm-inline-block"> Tambah </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width:36px;">
                            <input type="checkbox" id="select-all">
                        </th>
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
                                <input type="checkbox" class="row-check" data-id="{{ $member->id }}"
                                    value="{{ $member->id }}">
                            </td>
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
                                        <span class="emp_name text-truncate h6 mb-0">{{ $member->name }} @if ($member->trashed())
                                                <span class="badge bg-label-danger">Terhapus</span>
                                            @endif
                                        </span>
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
                                            href="{{ route('admin.members.detail.pdf', ['id' => $member->id]) }}"><i
                                                class="ri-printer-line me-1"></i>
                                            Print Profile</a>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.members.detail.kta', ['id' => $member->id]) }}"><i
                                                class="ri-printer-line me-1"></i>
                                            Print KTA</a>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.members.edit', ['id' => $member->id]) }}"><i
                                                class="ri-pencil-line me-1"></i>
                                            Edit</a>
                                        @if ($member->trashed())
                                            <form action="{{ route('admin.members.restore', ['id' => $member->id]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item"><i
                                                        class="ri-restart-line me-1"></i>
                                                    Restore</button>
                                            </form>
                                        @else
                                            @if (auth()->user()->role === 'superadmin')
                                                <button class="dropdown-item button-swal" data-id="{{ $member->id }}"
                                                    data-name="{{ $member->name }}"><i
                                                        class="ri-delete-bin-6-line me-1"></i>
                                                    Delete</button>
                                            @endif
                                        @endif
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

    <div class="modal fade" id="modalExport" tabindex="-1" aria-hidden="true">
        <form action="{{ route('admin.members.export.kta.pdf') }}" method="POST" id="bulkExportForm">
            <input type="hidden" name="member_ids_json" id="member_ids_json">
            @csrf
        </form>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExportTitle">Export Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Pilih apa yang akan diexport:
                    <br>
                    {{-- button export kontrak member --}}
                    <a href="{{ route('admin.contracts.export') }}" class="btn btn-primary mt-3">
                        Export Kontrak
                    </a>

                    <button type="button" class="btn btn-primary mt-3" id="btnExportKtaPdf">
                        Export KTA (PDF)
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
