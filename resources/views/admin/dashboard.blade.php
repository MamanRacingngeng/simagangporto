@extends('layouts.admin')

@section('title', 'Dashboard Admin - SIMAGANG')

@section('content')
<style>
    /* Success Banner */
    .success-banner {
        background: #ECFDF5;
        border: 1px solid #A7F3D0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        color: #065F46;
    }

    /* Metrics Grid - 2x2 Layout */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }
    
    @media (max-width: 768px) {
        .metrics-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Metric Card - Simple and Clean */
    .metric-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
        transition: all 0.3s ease;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08), 0 20px 60px rgba(15, 23, 42, 0.05);
    }

    .metric-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .metric-title {
        font-size: 14px;
        font-weight: 500;
        color: #6B7280;
        margin: 0;
    }

    .metric-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .metric-value {
        font-size: 36px;
        font-weight: 800;
        margin: 0;
        line-height: 1;
    }

    /* Color Variants */
    .metric-card.siap-diverifikasi .metric-icon {
        background: #ECFEFF;
    }
    .metric-card.siap-diverifikasi .metric-value {
        color: #06B6D4;
    }

    .metric-card.perlu-keputusan .metric-icon {
        background: #FFFBEB;
    }
    .metric-card.perlu-keputusan .metric-value {
        color: #F59E0B;
    }

    .metric-card.diterima .metric-icon {
        background: #ECFDF5;
    }
    .metric-card.diterima .metric-value {
        color: #10B981;
    }

    .metric-card.ditolak .metric-icon {
        background: #FEF2F2;
    }
    .metric-card.ditolak .metric-value {
        color: #EF4444;
    }

    /* Activity Panel */
    .activity-panel {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
        margin-bottom: 0;
    }

    .activity-panel h2 {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .activity-panel h2 svg {
        color: #2563EB;
        flex-shrink: 0;
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .activity-item {
        padding: 12px;
        background: #F9FAFB;
        border-radius: 10px;
        border-left: 3px solid #E5E7EB;
        transition: all 0.2s ease;
    }

    .activity-item:hover {
        background: #F3F4F6;
        border-left-color: #2563EB;
    }

    .activity-text {
        font-size: 14px;
        color: #374151;
        margin-bottom: 4px;
    }

    .activity-text strong {
        color: #111827;
        font-weight: 600;
    }

    .activity-time {
        font-size: 12px;
        color: #6B7280;
    }

    .status-badge-small {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 8px;
    }

    .status-badge-small.diajukan {
        background: #EFF6FF;
        color: #2563EB;
    }

    .status-badge-small.diverifikasi {
        background: #FFFBEB;
        color: #F59E0B;
    }

    .status-badge-small.diterima {
        background: #ECFDF5;
        color: #10B981;
    }

    .status-badge-small.ditolak {
        background: #FEF2F2;
        color: #EF4444;
    }

    .empty-activity {
        padding: 24px;
        text-align: center;
        color: #6B7280;
    }

    /* Charts Section */
    .charts-section-header {
        margin-top: 48px;
        margin-bottom: 24px;
    }

    .charts-section-title {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .charts-section-title::before {
        content: '';
        width: 4px;
        height: 24px;
        background: linear-gradient(180deg, #2563EB, #1D4ED8);
        border-radius: 2px;
    }

    .charts-section-subtitle {
        font-size: 14px;
        color: #6B7280;
        margin: 0;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 32px;
    }

    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
    }

    .chart-card.full-width {
        grid-column: 1 / -1;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 20px 0;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    .chart-container.pie-chart {
        height: 350px;
    }

    .chart-container.line-chart {
        height: 350px;
    }
</style>

<!-- Success Banner -->
@if(session('success'))
    <div class="success-banner">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="padding:16px;background:#FEF2F2;border-left:4px solid #EF4444;border-radius:8px;margin-bottom:24px;color:#991B1B;">
        {{ session('error') }}
    </div>
@endif

<!-- Statistics Cards - 2x2 Grid -->
<div class="metrics-grid">
    <a href="{{ route('admin.data_pendaftar', ['status' => 'Diajukan']) }}" class="metric-card siap-diverifikasi" style="text-decoration: none; color: inherit;">
        <div class="metric-header">
            <p class="metric-title">Siap Diverifikasi</p>
            <div class="metric-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#06B6D4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <h3 class="metric-value">{{ $siapDiverifikasi }}</h3>
    </a>

    <a href="{{ route('admin.data_pendaftar', ['status' => 'Diverifikasi']) }}" class="metric-card perlu-keputusan" style="text-decoration: none; color: inherit;">
        <div class="metric-header">
            <p class="metric-title">Perlu Keputusan</p>
            <div class="metric-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <h3 class="metric-value">{{ $perluKeputusan }}</h3>
    </a>

    <div class="metric-card diterima">
        <div class="metric-header">
            <p class="metric-title">Diterima</p>
            <div class="metric-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 13l4 4L19 7" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <h3 class="metric-value">{{ $diterima }}</h3>
    </div>

    <div class="metric-card ditolak">
        <div class="metric-header">
            <p class="metric-title">Ditolak</p>
            <div class="metric-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 18L18 6M6 6l12 12" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <h3 class="metric-value">{{ $ditolak }}</h3>
    </div>
</div>

<!-- Activity Log Section -->
<div class="activity-panel">
    <h2>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Aktivitas Pendaftar Terbaru
    </h2>
    <div class="activity-list">
        @forelse($activities as $activity)
            <a href="{{ route('admin.detail_pendaftar', $activity['id']) }}" class="activity-item" style="text-decoration: none; color: inherit;">
                <div class="activity-text">
                    <strong>{{ $activity['nama'] }}</strong> {{ $activity['aksi'] }}
                    @php
                        $statusClass = strtolower($activity['status'] ?? 'default');
                    @endphp
                    <span class="status-badge-small {{ $statusClass }}">
                        {{ $activity['status'] }}
                    </span>
                </div>
                <div class="activity-time">{{ $activity['diff'] }}</div>
            </a>
        @empty
            <div class="empty-activity">
                <p>Tidak ada aktivitas terbaru</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Charts Section -->
<div class="charts-section-header">
    <h2 class="charts-section-title">Analisis & Statistik Pendaftar</h2>
    <p class="charts-section-subtitle">Ringkasan visual data pendaftar magang untuk monitoring yang lebih efektif</p>
</div>

<div class="charts-grid">
    <!-- Bar Chart - Status Pendaftar -->
    <div class="chart-card">
        <h3 class="chart-title">Jumlah Pendaftar Berdasarkan Status</h3>
        <div class="chart-container">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <!-- Pie Chart - Persentase Status -->
    <div class="chart-card">
        <h3 class="chart-title">Persentase Status Pendaftar</h3>
        <div class="chart-container pie-chart">
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    <!-- Line Chart - Tren Bulanan -->
    <div class="chart-card full-width">
        <h3 class="chart-title">Tren Pendaftar Magang (12 Bulan Terakhir)</h3>
        <div class="chart-container line-chart">
            <canvas id="lineChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Chart data from backend (hidden) -->
<script type="application/json" id="chart-data">
{
    "barChart": {
        "labels": {!! json_encode($barChartData['labels']) !!},
        "data": {!! json_encode($barChartData['data']) !!},
        "colors": {!! json_encode($barChartData['colors']) !!}
    },
    "pieChart": {
        "labels": {!! json_encode($pieChartData['labels']) !!},
        "data": {!! json_encode($pieChartData['data']) !!},
        "colors": {!! json_encode($pieChartData['colors']) !!},
        "total": {!! json_encode($pieChartData['total']) !!}
    },
    "lineChart": {
        "labels": {!! json_encode($lineChartData['labels']) !!},
        "data": {!! json_encode($lineChartData['data']) !!}
    }
}
</script>

<script>
// Parse chart data from JSON script tag
const chartDataElement = document.getElementById('chart-data');
const chartData = chartDataElement ? JSON.parse(chartDataElement.textContent) : {};

document.addEventListener('DOMContentLoaded', function() {
    // Chart.js Configuration
    Chart.defaults.font.family = "'Inter', 'Poppins', system-ui, sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6B7280';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 15;

    // 1. Bar Chart - Status Pendaftar
    const barCtx = document.getElementById('barChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: chartData.barChart.labels,
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    data: chartData.barChart.data,
                    backgroundColor: chartData.barChart.colors,
                    borderColor: chartData.barChart.colors,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return 'Jumlah: ' + context.parsed.y + ' pendaftar';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: '#F3F4F6',
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // 2. Pie Chart - Persentase Status
    const pieCtx = document.getElementById('pieChart');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.pieChart.labels,
                datasets: [{
                    data: chartData.pieChart.data,
                    backgroundColor: chartData.pieChart.colors,
                    borderColor: '#FFFFFF',
                    borderWidth: 3,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    const dataset = data.datasets[0];
                                    const total = dataset.data.reduce((a, b) => a + b, 0);
                                    return data.labels.map((label, i) => {
                                        const value = dataset.data[i];
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return {
                                            text: label + ': ' + value + ' (' + percentage + '%)',
                                            fillStyle: dataset.backgroundColor[i],
                                            strokeStyle: dataset.borderColor,
                                            lineWidth: dataset.borderWidth,
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    // 3. Line Chart - Tren Bulanan
    const lineCtx = document.getElementById('lineChart');
    if (lineCtx) {
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: chartData.lineChart.labels,
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    data: chartData.lineChart.data,
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#2563EB',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#1D4ED8',
                    pointHoverBorderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            padding: 15,
                            font: {
                                size: 13,
                                weight: '600'
                            },
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Jumlah: ' + context.parsed.y + ' pendaftar';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: '#F3F4F6',
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
