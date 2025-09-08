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

    /* Contoh koordinat—SILAKAN SESUAIKAN dengan template Anda */
    .kta__name {
        top: 18mm;
        left: 25mm;
        font-size: 3.6mm;
        font-weight: 700;
        max-width: 55mm;
    }

    .kta__number {
        top: 23mm;
        left: 25mm;
        font-size: 3.2mm;
        letter-spacing: .2mm;
    }

    .kta__nik {
        top: 28mm;
        left: 25mm;
        font-size: 3mm;
    }

    .kta__ttl {
        top: 33mm;
        left: 25mm;
        font-size: 3mm;
    }

    .kta__qr {
        top: 10mm;
        right: 8mm;
        width: 16mm;
        height: 16mm;
    }

    .kta__photo {
        top: 10mm;
        left: 8mm;
        width: 18mm;
        height: 24mm;
        object-fit: cover;
        border-radius: 1mm;
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
    <div class="kta__field kta__name">Nama anggota</div>
    <div class="kta__field kta__number">No. Anggota: 123</div>
    <div class="kta__field kta__nik">NIK: 35092112345789</div>
    <div class="kta__field kta__ttl">
        TTL: Jember,
        {{ \Carbon\Carbon::parse(now())->format('d-m-Y') ?? '-' }}
    </div>
</div>
{{--
<script>
    window.onload = function() {
        window.print();
    }
</script> --}}
