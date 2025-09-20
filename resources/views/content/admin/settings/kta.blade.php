<style>
    /* Ukuran KTA fisik: 85.6mm × 53.98mm */
    .kta {
        position: relative;
        width: 85.6mm;
        height: 53.98mm;
        background: url('{{ $setting->ktaNowUrl() }}') no-repeat center center / cover;
        border: 1px solid #ddd;
        /* biar kelihatan saat preview */
        border-radius: 2mm;
        /* opsional, sesuaikan desain */
        overflow: hidden;
    }

    .kta-back {
        position: relative;
        width: 85.6mm;
        height: 53.98mm;
        background: url('{{ $setting->ktaBackNowUrl() }}') no-repeat center center / cover;
        border: 1px solid #ddd;
        /* biar kelihatan saat preview */
        border-radius: 2mm;
        /* opsional, sesuaikan desain */
        overflow: hidden;
    }

    .kta__field {
        position: absolute;
        color: #111;
        /* sesuaikan kontras dgn template */
        font-family: "Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
        line-height: 1.1;
        /* text-shadow: 0 0 1mm rgba(255,255,255,.6);  ; jika background gelap */
        white-space: nowrap;
        /* atau hapus kalau butuh wrapping */
    }

    .kta__photo {
        position: absolute;
        top: 4mm;
        left: 43mm;
        width: 11mm;
        height: 13mm;
        /* object-fit: cover; */
        border-radius: 1mm;
    }

    .kta__photo_img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 1mm;
    }

    /* Contoh koordinat—SILAKAN SESUAIKAN dengan template Anda */
    .kta__name {
        top: 15mm;
        left: 55mm;
        font-size: 2mm;
        font-weight: 700;
        max-width: 55mm;
    }

    .kta__part {
        top: 17mm;
        left: 55mm;
        font-size: 2mm;
        letter-spacing: .2mm;
    }

    .kta__number {
        top: 22mm;
        left: 55mm;
        font-size: 2mm;
        letter-spacing: .2mm;
    }

    .kta__phone_number {
        top: 30mm;
        left: 55mm;
        font-size: 2mm;
        font-weight: 500;
        letter-spacing: .2mm;
    }

    .kta__address {
        top: 25mm;
        left: 55mm;
        font-weight: 500;
        font-size: 2mm;
    }

    .kta__start_date {
        top: 36mm;
        left: 55mm;
        font-weight: 500;
        font-size: 2mm;
    }

    .kta__end_date {
        top: 41mm;
        left: 55mm;
        font-weight: 500;
        font-size: 2mm;
    }

    .kta__qr {
        top: 10mm;
        right: 8mm;
        width: 16mm;
        height: 16mm;
    }

    /* Layout grid untuk preview banyak kartu di layar */
    .kta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(90mm, 1fr));
        gap: 10mm;
    }

    /* Styling print */
    @media print {
        @page {
            size: A4;
            /* atau langsung size: 85.6mm 53.98mm; untuk 1 kartu per halaman */
            margin: 8mm;
            /* sesuaikan margin printer */
            padding: 0;
        }

        body {
            margin: 0;
        }

        .no-print {
            display: none !important;
        }

        .kta {
            break-inside: avoid;
        }
    }
</style>

<div class="kta">
    <div class="kta__photo">
        <img src="{{ $member ? $member->photoUrl() : asset('assets/img/avatars/1.png') }}" alt=""
            class="kta__photo_img">
    </div>
    <div class="kta__field kta__name">{{ $member ? $member->name : 'Nama' }}</div>
    <div class="kta__field kta__part">{{ $member ? $member->contracts->first()->part : 'Bagian/Divisi' }}</div>
    <div class="kta__field kta__number">{{ $member ? $member->reg_number : 'Nomor Anggota' }}</div>
    <div class="kta__field kta__address">{{ $member ? $member->address : 'Alamat' }}</div>
    <div class="kta__field kta__phone_number">{{ $member ? $member->phone : 'Nomor HP' }}</div>
    <div class="kta__field kta__start_date">{{ $member ? $member->contracts->first()->start_date : 'Tanggal Mulai' }}
    </div>
    <div class="kta__field kta__end_date">{{ $member ? $member->contracts->first()->end_date : 'Tanggal Berakhir' }}
    </div>
</div>
<br>
<div class="kta-back">
</div>

<script>
    @if (\Illuminate\Support\Str::contains(url()->current(), 'print'))
        window.onload = function() {
            window.print();
        }
    @endif
</script>
