<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>KTA Selected</title>
    <style>
        @page {
            size: A4;
            margin: 8mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
        }

        /* contoh ukuran & style dasar — sesuaikan dengan yang sekarang kamu pakai */
        .kta {
            break-inside: avoid;
        }

        .page-break {
            page-break-after: always;
        }

        .kta__photo_img {
            width: 100%;
            height: auto;
            display: block;
        }
    </style>
</head>

<body>
    @foreach ($members as $member)
        @include('content.admin.settings.kta', ['member' => $member, 'setting' => $setting])

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
