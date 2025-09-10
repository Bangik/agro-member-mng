@extends('layouts/contentNavbarLayout')

@section('title', 'Response Aspirasi / Aduan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-body">
                    <h5 class="card-title">Detail Aspirasi / Aduan</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Aspirasi / Aduan</label>
                                <input type="text" class="form-control"
                                    value="{{ $complaint->created_at->translatedFormat('d F Y') }}" readonly />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Response</label>
                                <input type="text" class="form-control"
                                    value="{{ $complaint->response_at ? \Carbon\Carbon::parse($complaint->response_at)->translatedFormat('d F Y') : '-' }}"
                                    readonly />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kode Aspirasi / Aduan</label>
                                <input type="text" class="form-control" value="{{ $complaint->code }}" readonly />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Judul Aspirasi / Aduan</label>
                                <input type="text" class="form-control" value="{{ $complaint->title }}" readonly />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" rows="5" readonly>{{ $complaint->complaint }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Response</label>
                                <textarea class="form-control" rows="5" readonly>{{ $complaint->response }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control"
                                    value="{{ $complaint->status == 'pending' ? 'Pending' : ($complaint->status == 'in_progress' ? 'In Progress' : 'Resolved') }}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('member.complaints.update.status', $complaint->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="resolved">
                        <button type="submit" class="btn btn-primary mt-6"
                            {{ $complaint->status == 'resolved' ? 'disabled' : '' }}>Selesaikan Aspirasi / Aduan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
