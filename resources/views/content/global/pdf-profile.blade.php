@php
    function v($val, $fallback = '—')
    {
        return filled($val) ? $val : $fallback;
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Data Diri Anggota - {{ v($member->name ?? ($member['name'] ?? null)) }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            font-family: "Arial", sans-serif;
            color: #111;
        }

        @page {
            size: A4;
            margin: 150px 50px 90px 50px;
        }

        body {
            margin: 0;
            font-size: 12px;
            line-height: 1.35;
        }

        .header {
            position: fixed;
            width: 100%;
            top: -130px;
            left: 0px;
            right: 0px;
        }

        .subbrand {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
        }

        .contact {
            font-size: 11px;
            color: #333;
            margin-top: 2px;
        }

        .rule {
            border: 0;
            border-top: 2px solid #000;
            margin: 6px 0 12px;
        }

        .title {
            text-align: center;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            margin-top: -30px;
        }

        .section-title {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            padding: 6px 8px;
            border: 1px solid #000;
            border-left: 0;
            border-right: 0;
            margin-top: 10px;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
        }

        .grid td,
        .grid th {
            padding: 2px 15px;
            vertical-align: top;
        }

        .grid th {
            text-align: left;
            width: 25%;
            font-weight: 700;
        }

        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .contracts {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0px;
        }

        .contracts th,
        .contracts td {
            border: 1px solid #000;
            padding: 2px 8px;
        }

        .contracts th {
            text-align: center;
            background: #fafafa;
        }

        .footer {
            margin-top: 22px;
            display: flex;
            justify-content: flex-start;
        }

        .sign {
            width: 260px;
            text-align: center;
        }

        .sign .place-date {
            margin-bottom: 54px;
        }

        .sign .name {
            margin-top: 34px;
            text-decoration: underline;
        }

        .meta {
            margin-top: 18px;
            font-size: 10px;
            color: #333;
            display: flex;
            justify-content: space-between;
        }

        .code {
            font-size: 10px;
            color: #333;
            text-align: right;
            margin-top: 4px;
        }

        .w-50 {
            width: 50%;
        }

        .nowrap {
            white-space: nowrap;
        }

        .footer-bg {
            position: fixed;
            left: -50px;
            right: 0;
            bottom: -90px;
            width: 120%;
            height: 70px;
            z-index: 0;
        }

        .page-footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -100px;
            height: 70px;
        }

        .icon-email {
            width: 25px;
            height: 25px;
            display: inline-block;
            vertical-align: -20px;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .page .footer {
            margin-top: 18px;
        }

        .grid .photo-cell {
            width: 3.2cm;
        }

        /* sedikit longgar agar border tidak kepotong */
        .id-photo {
            width: 3cm;
            height: 4cm;
            object-fit: cover;
            display: block;
            margin-left: auto;
            /* pastikan nempel kanan */
        }
    </style>
</head>

<body>
    <div class="header">
        <div style="float: left; margin-bottom:0px;">
            <img src="{{ public_path('assets/svg/logo.svg') }}" style="width: 100px; height: 100px">
        </div>
        <div style="float: left; padding-left:20px; margin-top:10px;">
            <p style="text-align: left; margin-bottom: 1px;line-height: 5px;font-size:25px;">
                SERIKAT PEKERJA AGROINDUSTRI
            </p>
            <div style="text-align:left; margin:0; line-height:1.2; font-size:18px;">
                <span style="display:inline-block; vertical-align:middle;">
                    PT MITRATANI DUA TUJUH
                </span>
                <span
                    style="display:inline-block; vertical-align:middle;
                         height:0.13cm; width:150px; margin-left:10px;">
                    <img src="{{ public_path('assets/img/elements/garis.png') }}" height="5px">
                </span>
            </div>
        </div>
        <span
            style="float: right; font-size: 14px; margin-top:19px; line-height:15px; border: 1px solid #000; padding: 2px 5px; align-self: center;">F001
            |
            Rev
            00</span>
    </div>

    <div class="title">Data Diri Anggota<br />Serikat Pekerja Agroindustri PT Mitratani Dua Tujuh</div>

    <div class="section-title">Data Diri</div>
    <table class="grid">
        <tr>
            <th>Nomor Induk</th>
            <td>{{ v($member->reg_number ?? ($member['reg_number'] ?? null)) }}</td>
            <td class="photo-cell" rowspan="8">
                <img class="id-photo"
                    src="{{ $member->pp_file ? public_path('storage/member/' . $member->pp_file) : public_path('assets/img/avatars/1.png') }}"
                    alt="Foto 3x4" />
            </td>
        </tr>
        <tr>
            <th>NIK</th>
            <td>{{ v($member->national_id_number ?? ($member['national_id_number'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Nama Anggota</th>
            <td>{{ v($member->name ?? ($member['name'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Tempat, Tanggal Lahir</th>
            <td>
                {{ v($member->birth_place ?? ($member['birth_place'] ?? null)) }},
                {{ isset($member->birth_date)
                    ? \Carbon\Carbon::parse($member->birth_date)->translatedFormat('d F Y')
                    : (isset($member['birth_date'])
                        ? \Carbon\Carbon::parse($member['birth_date'])->translatedFormat('d F Y')
                        : '—') }}
            </td>
        </tr>
        <tr>
            <th>Jenis Kelamin</th>
            <td>{{ v($member->gender ? ($member->gender === 'male' ? 'Laki-laki' : 'Perempuan') : $member['gender'] ?? null) }}
            </td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>{{ v($member->address ?? ($member['address'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>RT / RW</th>
            <td>{{ v($member->rt ?? ($member['rt'] ?? null)) }} / {{ v($member->rw ?? ($member['rw'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Desa/Kelurahan</th>
            <td>{{ v($member->village ?? ($member['village'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Kecamatan</th>
            <td>{{ v($member->district ?? ($member['district'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Kota/Kabupaten</th>
            <td>{{ v($member->city ?? ($member['city'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Provinsi</th>
            <td>{{ v($member->state ?? ($member['state'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Kode Pos</th>
            <td>{{ v($member->post_code ?? ($member['post_code'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>No Handphone</th>
            <td>{{ v($member->phone ?? ($member['phone'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ v($member->email ?? ($member['email'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Agama</th>
            <td>{{ v($member->religion ?? ($member['religion'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Golongan Darah</th>
            <td>{{ v($member->blood_type ?? ($member['blood_type'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Status Menikah</th>
            <td>{{ v($member->is_married ?? ($member['is_mariried'] ?? null)) }}</td>
        </tr>
        <tr>
            <th>Hobi</th>
            <td>{{ v($member->hobbies ?? ($member['hobbies'] ?? null)) }}</td>
        </tr>
    </table>

    <div class="section-title">Data Kontrak</div>
    <table class="grid">
        <tr>
            <th>Divisi / Bagian</th>
            <td>{{ v($employment->part->name ?? null) }}</td>
        </tr>
        <tr>
            <th>Status Kerja</th>
            <td>{{ v($contracts->where('end_date', '>=', now()->format('Y-m-d'))->first() ? 'Aktif' : 'Tidak Aktif') }}
            </td>
        </tr>
        <tr>
            <th>Tanggal Aktif</th>
            <td>
                {{ isset($employment->start_date)
                    ? \Carbon\Carbon::parse($employment->start_date)->translatedFormat('d F Y')
                    : (isset($employment['start_date'])
                        ? \Carbon\Carbon::parse($employment['start_date'])->translatedFormat('d F Y')
                        : '—') }}
            </td>
        </tr>
        <tr>
            <th>Tanggal Off</th>
            <td>
                {{ isset($employment->end_date)
                    ? \Carbon\Carbon::parse($employment->end_date)->translatedFormat('d F Y')
                    : (isset($employment['end_date'])
                        ? \Carbon\Carbon::parse($employment['end_date'])->translatedFormat('d F Y')
                        : '—') }}
            </td>
        </tr>
        <tr>
            <th>Kontrak Ke</th>
            <td>{{ v($contracts->count()) }}</td>
        </tr>
    </table>

    @php
        $contracts = collect($contracts ?? []);
        $firstPageRows = $contracts->take(5);
        $resetChunks = $contracts->skip(5)->chunk(40);
    @endphp


    <div
        style="font-weight: 700;text-transform: uppercase;
      font-size: 12px;
      padding: 6px 8px;
      margin-top: 10px; ">
        Histori Kontrak</div>
    <div class="page">
        <table class="contracts">
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th>No Kontrak</th>
                    <th class="nowrap" style="width:22%;">Tanggal Aktif</th>
                    <th class="nowrap" style="width:22%;">Tanggal Off</th>
                    <th style="width:24%;">Bagian</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($firstPageRows ?? []) as $i => $c)
                    <tr>
                        <td style="text-align:center;">{{ $i + 1 }}</td>
                        <td>{{ v($c['contract_number'] ?? ($c->contract_number ?? null)) }}</td>
                        <td class="nowrap">
                            {{ isset($c['start_date']) || isset($c->start_date)
                                ? \Carbon\Carbon::parse($c['start_date'] ?? $c->start_date)->translatedFormat('d F Y')
                                : '—' }}
                        </td>
                        <td class="nowrap">
                            {{ isset($c['end_date']) || isset($c->end_date)
                                ? \Carbon\Carbon::parse($c['end_date'] ?? $c->end_date)->translatedFormat('d F Y')
                                : '—' }}
                        </td>
                        <td>{{ v($c['part']['name'] ?? ($c->part->name ?? null)) }}</td>
                    </tr>
                @empty
                    @for ($r = 1; $r <= 4; $r++)
                        <tr>
                            <td style="text-align:center;">{{ $r }}</td>
                            <td>—</td>
                            <td>—</td>
                            <td>—</td>
                            <td>—</td>
                        </tr>
                    @endfor
                @endforelse
            </tbody>
        </table>


        <table style="width:100%; margin-top:22px; border-collapse:collapse;">
            <tr>
                <td></td>
                <td style="width:260px; text-align:center;">
                    <div class="place-date">
                        {{ v($signature_place ?? 'Jember') }},
                        {{ isset($printed_at) ? \Carbon\Carbon::parse($printed_at)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                    </div>
                    <div style="margin-top:50px; text-decoration:underline; font-weight: bold;" class="name">
                        {{ v($chairman_name ?? '________________________') }}
                    </div>
                    <div>Nomor Anggota: {{ v($chairman_reg_number ?? '—') }}</div>
                </td>
            </tr>
        </table>

        <img src="{{ public_path('assets/img/elements/footer.png') }}" class="footer-bg" />

        <div class="page-footer">
            <div style="text-align:left; margin:0; line-height:1.2;">
                <span style="display:inline-block; vertical-align:middle;">Jl. Brawijaya No.83, Mangli, Kec.
                    Kaliwates,
                    Kabupaten Jember<br>
                    Nomor : KEP-01/319/KW12.KD6/Pend.Org/SPA/1999</span>
                <span style="margin-left:20px;">
                    <img src="{{ public_path('assets/img/icons/email.svg') }}" alt="" class="icon-email">
                    <span style="display: inline-block; vertical-align: -4px;">: mail.spamdt@gmail.com</span>
                </span>
            </div>
        </div>
    </div>

    @foreach ($resetChunks as $index => $chunk)
        <div class="page">
            <table class="contracts">
                <thead>
                    <tr>
                        <th style="width:5%;">No</th>
                        <th>No Kontrak</th>
                        <th class="nowrap" style="width:22%;">Tanggal Aktif</th>
                        <th class="nowrap" style="width:22%;">Tanggal Off</th>
                        <th style="width:24%;">Bagian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($chunk ?? []) as $i => $c)
                        <tr>
                            <td style="text-align:center;">{{ $i + 1 }}</td>
                            <td>{{ v($c['contract_number'] ?? ($c->contract_number ?? null)) }}</td>
                            <td class="nowrap">
                                {{ isset($c['active_at']) || isset($c->active_at)
                                    ? \Carbon\Carbon::parse($c['active_at'] ?? $c->active_at)->translatedFormat('d F Y')
                                    : '—' }}
                            </td>
                            <td class="nowrap">
                                {{ isset($c['off_at']) || isset($c->off_at)
                                    ? \Carbon\Carbon::parse($c['off_at'] ?? $c->off_at)->translatedFormat('d F Y')
                                    : '—' }}
                            </td>
                            <td>{{ v($c['division'] ?? ($c->division ?? null)) }}</td>
                        </tr>
                    @empty
                        @for ($r = 1; $r <= 4; $r++)
                            <tr>
                                <td style="text-align:center;">{{ $r }}</td>
                                <td>—</td>
                                <td>—</td>
                                <td>—</td>
                                <td>—</td>
                            </tr>
                        @endfor
                    @endforelse
                </tbody>
            </table>

            <img src="{{ public_path('assets/img/elements/footer.png') }}" class="footer-bg" />

            <div class="page-footer">
                <div style="text-align:left; margin:0; line-height:1.2;">
                    <span style="display:inline-block; vertical-align:middle;">Jl. Brawijaya No.83, Mangli, Kec.
                        Kaliwates,
                        Kabupaten Jember<br>
                        Nomor : KEP-01/319/KW12.KD6/Pend.Org/SPA/1999</span>
                    <span style="margin-left:20px;">
                        <img src="{{ public_path('assets/img/icons/email.svg') }}" alt="" class="icon-email">
                        <span style="display: inline-block; vertical-align: -4px;">: mail.spamdt@gmail.com</span>
                    </span>
                </div>
            </div>
        </div>
    @endforeach
</body>

</html>
