@extends('layouts/contentNavbarLayout')

@section('title', 'Pengaturan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-body">
                    <h5 class="card-title">Desain Kartu Anggota</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Desain lama</h6>
                            @if ($setting->kta_file_before === null)
                                <p class="text-muted fst-italic">Belum ada desain lama</p>
                            @else
                                <img src="{{ $setting->ktaBeforeUrl() }}" alt="KTA Desain Lama" class="img-fluid border"
                                    width="323px" />
                            @endif
                            <br>
                            <br>
                            @if ($setting->kta_file_before === null)
                                <p class="text-muted fst-italic">Belum ada desain lama</p>
                            @else
                                <img src="{{ $setting->ktaBeforeUrl() }}" alt="KTA Desain Lama" class="img-fluid border"
                                    width="323px" />
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Desain baru</h6>
                            <a class="btn btn-primary mb-3" href="{{ route('admin.settings.kta.print') }}"
                                target="_blank">Print</a>
                            @include('content.admin.settings.kta')
                        </div>
                    </div>
                    <form action="{{ route('admin.settings.update-kta', $setting->id) }}" method="POST"
                        enctype="multipart/form-data" class="mt-4">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="kta_file_now"
                                    class="form-label @error('kta_file_now') is-invalid @enderror">Unggah KTA Depan
                                    Baru</label>
                                <input class="form-control" type="file" id="kta_file_now" name="kta_file_now"
                                    accept="image/*" />
                            </div>
                            @error('kta_file_now')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="kta_file_back_now"
                                    class="form-label @error('kta_file_back_now') is-invalid @enderror">Unggah KTA Belakang
                                    Baru</label>
                                <input class="form-control" type="file" id="kta_file_back_now" name="kta_file_back_now"
                                    accept="image/*" />
                            </div>
                            @error('kta_file_back_now')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="union_logo_file"
                                    class="form-label @error('union_logo_file') is-invalid @enderror">Unggah
                                    Logo</label>
                                <input class="form-control" type="file" id="union_logo_file" name="union_logo_file"
                                    accept="image/*" />
                            </div>
                            @error('union_logo_file')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="union_chairman" class="form-label">Nama Ketua Serikat</label>
                                <input type="text" class="form-control" id="union_chairman" name="union_chairman"
                                    value="{{ old('union_chairman', $setting->union_chairman) }}" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="union_reg_number" class="form-label">Nomor Registrasi Ketua Serikat</label>
                                <input type="text" class="form-control" id="union_reg_number" name="union_reg_number"
                                    value="{{ old('union_reg_number', $setting->union_reg_number) }}" required />
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
