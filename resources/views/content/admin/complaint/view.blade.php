@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Aspirasi / Aduan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">
                        Detail Aspirasi / Aduan
                    </h5>
                    <div class="add-new">
                        <button class="btn btn-primary me-1 waves-effect waves-light" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseDetail" aria-expanded="true" aria-controls="collapseDetail">
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="collapse show" id="collapseDetail" style="">
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
            </div>
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">
                        Response Aspirasi / Aduan
                    </h5>
                    <div class="add-new">
                        <button class="btn btn-primary me-1 waves-effect waves-light" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseResponse" aria-expanded="true"
                            aria-controls="collapseResponse">
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="collapse show" id="collapseResponse" style="">
                        <div class="row g-5">
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" class="form-control" id="response_at" name="response_at" autofocus
                                        placeholder="Response Date" value="{{ $complaint->response_at }}" readonly>
                                    <label for="response_at">Tanggal Response</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control h-px-100" id="response" name="response" autofocus placeholder="Response" readonly>{{ $complaint->response }}</textarea>
                                    <label for="response">Response</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
