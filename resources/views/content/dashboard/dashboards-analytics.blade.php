@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')

    <script>
        // demografi JK anggota with pie chart
        document.addEventListener("DOMContentLoaded", function() {
            var optionsJK = {
                chart: {
                    type: 'pie',
                    height: 350
                },
                series: [{{ $maleCount }}, {{ $femaleCount }}],
                labels: ['Laki-laki', 'Perempuan'],
                colors: ['#116039', '#8dc440'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            // column chart for parts and countPartInEveryContract
            var optionsParts = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Total',
                    data: @json($rows) // [{x:"Bolt", y:12}, {x:"Nut", y:0}, ...]
                }],
                xaxis: {
                    type: 'category'
                },
                colors: ['#45ab48']
            };

            var chart = new ApexCharts(document.querySelector("#demojk"), optionsJK);
            var chartParts = new ApexCharts(document.querySelector("#parts-chart"), optionsParts);
            chart.render();
            chartParts.render();
        });
    </script>
@endsection

@section('content')
    <div class="row gy-6">
        <!-- Transactions -->
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Overview Aspirasi / Aduan</h5>
                    </div>
                    <p class="small mb-0"><span class="h6 mb-0">Total {{ $totalComplaintsThisMonth }}</span> Aspirasi / Aduan
                        bulan ini
                    </p>
                </div>
                <div class="card-body">
                    <div class="row g-6">
                        <div class="col-md-4 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-warning rounded shadow-xs">
                                        <i class="ri-pie-chart-2-line ri-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('admin.complaints.index') . '?status=pending' }}"
                                        class="mb-0 text-warning">Belum
                                        Ditindaklanjuti</a>
                                    <h5 class="mb-0">{{ $complaintPending }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-info rounded shadow-xs">
                                        <i class="ri-group-line ri-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('admin.complaints.index') . '?status=in_progress' }}"
                                        class="mb-0 text-info">Proses</a>
                                    <h5 class="mb-0">{{ $complaintInProgress }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-success rounded shadow-xs">
                                        <i class="ri-check-line ri-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('admin.complaints.index') . '?status=resolved' }}"
                                        class="mb-0">Sudah Ditindaklanjuti</a>
                                    <h5 class="mb-0">{{ $complaintResolved }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Transactions -->

        <!-- Data Tables -->
        <div class="col-12">
            <div class="card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th class="text-truncate">Aktif</th>
                                <th class="text-truncate">Proses</th>
                                <th class="text-truncate">Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($complaints->where('status', 'pending') as $complaint)
                                            <li> <a href="{{ route('admin.complaints.detail', $complaint->id) }}"
                                                    target="_blank">{{ $complaint->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <td>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($complaints->where('status', 'in_progress') as $complaint)
                                            <li> <a href="{{ route('admin.complaints.detail', $complaint->id) }}"
                                                    target="_blank">{{ $complaint->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <td>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($complaints->where('status', 'resolved') as $complaint)
                                            <li> <a href="{{ route('admin.complaints.detail', $complaint->id) }}"
                                                    target="_blank">{{ $complaint->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <!--/ Data Tables -->

        <!-- Weekly Overview Chart -->
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-1">Demografi JK Anggota</h5>
                    </div>
                </div>
                <div class="card-body pt-lg-2">
                    <div id="demojk"></div>
                </div>
            </div>
        </div>
        <!--/ Weekly Overview Chart -->

        <div class="col-xl-8 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-1">Jumlah Anggota per Bagian</h5>
                    </div>
                </div>
                <div class="card-body pt-lg-2">
                    <div id="parts-chart"></div>
                </div>
            </div>
        </div>

    </div>
@endsection
