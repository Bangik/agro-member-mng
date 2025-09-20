@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Anggota')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <form action="{{ route('admin.members.update', $member->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body pt-0">
                        <div class="row mt-1 g-5">
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                        id="name" name="name" value="{{ old('name', $member->name) }}" autofocus
                                        placeholder="Nama Anggota" />
                                    <label for="name">Nama Anggota</label>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                        id="email" name="email" value="{{ old('email', $member->email) }}" autofocus
                                        placeholder="Email" />
                                    <label for="email">Email</label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('reg_number') is-invalid @enderror" type="text"
                                        id="reg_number" name="reg_number"
                                        value="{{ old('reg_number', $member->reg_number) }}" autofocus
                                        placeholder="Nomor Induk" />
                                    <label for="reg_number">Nomor Induk</label>
                                    @error('reg_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('national_id_number') is-invalid @enderror"
                                        type="text" id="national_id_number" name="national_id_number"
                                        value="{{ old('national_id_number', $member->national_id_number) }}" autofocus
                                        placeholder="350921..." />
                                    <label for="national_id_number">NIK</label>
                                    @error('national_id_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('birth_place') is-invalid @enderror" type="text"
                                        id="birth_place" name="birth_place"
                                        value="{{ old('birth_place', $member->birth_place) }}" autofocus
                                        placeholder="Tempat Lahir" />
                                    <label for="birth_place">Tempat Lahir</label>
                                    @error('birth_place')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('birth_date') is-invalid @enderror" type="date"
                                        id="birth_date" name="birth_date"
                                        value="{{ old('birth_date', $member->birth_date) }}" autofocus
                                        placeholder="Tanggal Lahir" />
                                    <label for="birth_date">Tanggal Lahir</label>
                                    @error('birth_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                        name="gender">
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <option value="male"
                                            {{ old('gender', $member->gender) === 'male' ? 'selected' : '' }}>Laki-laki
                                        </option>
                                        <option value="female"
                                            {{ old('gender', $member->gender) === 'female' ? 'selected' : '' }}>Perempuan
                                        </option>
                                    </select>
                                    <label for="gender">Jenis Kelamin</label>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('address') is-invalid @enderror" type="text"
                                        id="address" name="address" value="{{ old('address', $member->address) }}"
                                        autofocus placeholder="Alamat" />
                                    <label for="address">Alamat</label>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('rt') is-invalid @enderror" type="text"
                                        id="rt" name="rt" value="{{ old('rt', $member->rt) }}" autofocus
                                        placeholder="RT" />
                                    <label for="rt">RT</label>
                                    @error('rt')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('rw') is-invalid @enderror" type="text"
                                        id="rw" name="rw" value="{{ old('rw', $member->rw) }}" autofocus
                                        placeholder="RW" />
                                    <label for="rw">RW</label>
                                    @error('rw')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('village') is-invalid @enderror" type="text"
                                        id="village" name="village" value="{{ old('village', $member->village) }}"
                                        autofocus placeholder="Kelurahan/Desa" />
                                    <label for="village">Kelurahan/Desa</label>
                                    @error('village')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('district') is-invalid @enderror" type="text"
                                        id="district" name="district" value="{{ old('district', $member->district) }}"
                                        autofocus placeholder="Kecamatan" />
                                    <label for="district">Kecamatan</label>
                                    @error('district')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('city') is-invalid @enderror" type="text"
                                        id="city" name="city" value="{{ old('city', $member->city) }}" autofocus
                                        placeholder="Kabupaten/Kota" />
                                    <label for="city">Kabupaten/Kota</label>
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('state') is-invalid @enderror" type="text"
                                        id="state" name="state" value="{{ old('state', $member->state) }}"
                                        autofocus placeholder="Provinsi" />
                                    <label for="state">Provinsi</label>
                                    @error('state')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('post_code') is-invalid @enderror" type="text"
                                        id="post_code" name="post_code"
                                        value="{{ old('post_code', $member->post_code) }}" autofocus
                                        placeholder="Kode Pos" />
                                    <label for="post_code">Kode Pos</label>
                                    @error('post_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('phone') is-invalid @enderror" type="text"
                                        id="phone" name="phone" value="{{ old('phone', $member->phone) }}"
                                        autofocus placeholder="Nomor Telepon" />
                                    <label for="phone">Nomor Telepon</label>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('religion') is-invalid @enderror" type="text"
                                        id="religion" name="religion" value="{{ old('religion', $member->religion) }}"
                                        autofocus placeholder="Agama" />
                                    <label for="religion">Agama</label>
                                    @error('religion')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select @error('blood_type') is-invalid @enderror" id="blood_type"
                                        name="blood_type">
                                        <option value="" disabled selected>Pilih Golongan Darah</option>
                                        <option value="A"
                                            {{ old('blood_type', $member->blood_type) === 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B"
                                            {{ old('blood_type', $member->blood_type) === 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="AB"
                                            {{ old('blood_type', $member->blood_type) === 'AB' ? 'selected' : '' }}>AB
                                        </option>
                                        <option value="O"
                                            {{ old('blood_type', $member->blood_type) === 'O' ? 'selected' : '' }}>O
                                        </option>
                                    </select>
                                    <label for="blood_type">Golongan Darah</label>
                                    @error('blood_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select @error('is_married') is-invalid @enderror" id="is_married"
                                        name="is_married">
                                        <option value="" disabled selected>Pilih Status Pernikahan</option>
                                        <option value="1"
                                            {{ old('is_married', $member->is_married) === 1 ? 'selected' : '' }}>Menikah
                                        </option>
                                        <option value="0"
                                            {{ old('is_married', $member->is_married) === 0 ? 'selected' : '' }}>Belum
                                            Menikah
                                        </option>
                                    </select>
                                    <label for="is_married">Status Pernikahan</label>
                                    @error('is_married')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('hobbies') is-invalid @enderror" type="text"
                                        id="hobbies" name="hobbies" value="{{ old('hobbies', $member->hobbies) }}"
                                        autofocus placeholder="Hobi" />
                                    <label for="hobbies">Hobi</label>
                                    @error('hobbies')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control @error('password') is-invalid @enderror"
                                                type="password" id="password" name="password" autofocus
                                                placeholder="Password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password" />
                                            <label for="password">Password</label>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="ri-eye-off-line ri-20px"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                type="password" id="password_confirmation" name="password_confirmation"
                                                autofocus placeholder="Konfirmasi Password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password_confirmation" />
                                            <label for="password_confirmation">Konfirmasi Password</label>
                                            @error('password_confirmation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="ri-eye-off-line ri-20px"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating form-floating-outline mt-5">
                            <input class="form-control @error('photo') is-invalid @enderror" type="file"
                                id="photo" name="photo" value="{{ old('photo') }}" autofocus
                                placeholder="Foto Profil" />
                            <label for="photo">Foto Profil</label>
                            @error('photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-6">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
