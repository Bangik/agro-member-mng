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
                                    value="{{ $complaint->resolved_at ? \Carbon\Carbon::parse($complaint->resolved_at)->translatedFormat('d F Y') : '-' }}"
                                    readonly />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kode Aspirasi / Aduan</label>
                                <input type="text" class="form-control" value="{{ $complaint->code }}" readonly />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Pengadu</label>
                                <input type="text" class="form-control" value="{{ $complaint->member->name }}"
                                    readonly />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Judul Aspirasi / Aduan</label>
                                <input type="text" class="form-control" value="{{ $complaint->title }}" readonly />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" rows="5" readonly>{{ $complaint->complaint }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-6">
                <form action="{{ route('admin.complaints.update', $complaint->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <h5 class="card-title">Form Response Aspirasi / Aduan</h5>
                        <div class="row g-5">
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" class="form-control @error('resolved_at') is-invalid @enderror"
                                        id="resolved_at" name="resolved_at" autofocus placeholder="Response Date"
                                        value="{{ old('resolved_at', $complaint->resolved_at) }}">
                                    <label for="resolved_at">Tanggal Response</label>
                                    @error('resolved_at')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control h-px-100 @error('response') is-invalid @enderror" id="response" name="response" autofocus
                                        placeholder="Response">{{ old('response', $complaint->response) }}</textarea>
                                    <label for="response">Response</label>
                                    @error('response')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-6">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
