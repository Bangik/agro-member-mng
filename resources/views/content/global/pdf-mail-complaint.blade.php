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
            style="float: right; font-size: 14px; margin-top:19px; line-height:15px; border: 1px solid #000; padding: 2px 5px; align-self: center;">F002
            |
            Rev
            00</span>
    </div>

    <div class="title">FORM ADUAN ANGGOTA<br />Serikat Pekerja Agroindustri PT Mitratani Dua Tujuh</div>
    <hr>
    <div style="text-align: center;">Nomor : 001/AD-SPA/08/2025</div>

    <div>
        <p>
            Kepada Yth,<br>
            Ketua Serikat Pekerja Agroindustri<br>
            PT Mitratani Dua Tujuh<br>
            Di Tempat
        </p>
    </div>
    <br>
    <div>
        Dengan Hormat,
    </div>
    <br>
    <div>
        Saya yang bertandatangan dibawah ini menyampaikan aduan sebagai berikut:
    </div>
    <div style="margin-top:10px; margin-bottom:10px; padding:10px 8px; text-align:justify;">
        {{ $complaint->title }}
        <br>
        {{ e($complaint->complaint) }}
    </div>
    <div>
        Demikian aduan ini saya buat dengan sebenar-benarnya, atas perhatian dan kerjasamanya disampaikan terima kasih.
    </div>

    <table style="width:100%; margin-top:22px; border-collapse:collapse;">
        <tr>
            <td></td>
            <td style="width:260px; text-align:center;">
                <div class="place-date">
                    {{ v($signature_place ?? 'Jember') }},
                    {{ isset($printed_at) ? \Carbon\Carbon::parse($printed_at)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                </div>
                <div style="margin-top:50px; text-decoration:underline; font-weight: bold;" class="name">
                    {{ v($complaint->member->name ?? '________________________') }}
                </div>
                <div>Nomor Anggota: {{ v($complaint->member->reg_number ?? '—') }}
                </div>
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
</body>

</html>
