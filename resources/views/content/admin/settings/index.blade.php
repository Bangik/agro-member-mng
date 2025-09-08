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
                                <img src="{{ $setting->ktaBeforeUrl() }}" alt="KTA Desain Lama" class="img-fluid border" />
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
                                <label for="kta_file_now" class="form-label">Unggah Desain Baru</label>
                                <input class="form-control" type="file" id="kta_file_now" name="kta_file_now"
                                    accept="image/*" />
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
