@extends('layouts.app')

@push('head')
<!-- Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endpush

@push('scripts')
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {
    var data = google.visualization.arrayToDataTable([
        ['Type', 'Size (MB)'],
        ['Private', {{ $stats['private_photos_size'] / 1024 / 1024 }}],
        ['Public', {{ $stats['public_photos_size'] / 1024 / 1024 }}],
        ['Organization', {{ $stats['org_photos_size'] / 1024 / 1024 }}]
    ]);

    var options = {
        pieHole: 0.7,
        colors: ['#dc3545', '#28a745', '#ffc107'],
        legend: 'none',
        chartArea: {width: '100%', height: '100%'},
        height: 225
    };

    var chart = new google.visualization.PieChart(document.getElementById('photoTypeChart'));
    chart.draw(data, options);

    // Personal Albums Storage Chart
    var albumsData = google.visualization.arrayToDataTable([
        ['Album', 'Size (MB)', { role: 'style' }],
        @foreach($stats['albums'] as $index => $album)
            ['{{ $album['name'] }}', {{ round($album['total_size'] / 1024 / 1024, 2) }}, '{{ ['#0d6efd', '#20c997', '#ffc107', '#dc3545', '#6610f2', '#198754', '#fd7e14', '#0dcaf0', '#6f42c1'][$index % 9] }}'],
        @endforeach
    ]);

    var albumsOptions = {
        legend: 'none',
        height: 300,
        chartArea: { left: 60, top: 20, width: '80%', height: '80%' },
        vAxis: { minValue: 0, format: 'short' },
        hAxis: { 
            textStyle: { fontSize: 10 },
            slantedText: true,
            slantedTextAngle: 45
        }
    };

    var albumsChart = new google.visualization.ColumnChart(document.getElementById('albumsStorageChart'));
    albumsChart.draw(albumsData, albumsOptions);

    // Organizations Storage Stacked Bar Chart (each album + Unorganized as series)
    // Build header with dynamic album series across all orgs, include Unorganized
    var orgHeader = ['Organization', 'Unorganized'];
    var albumSeries = {};
    @foreach($stats['organizations'] as $org)
        @if(!empty($org['albums_breakdown']))
            @foreach($org['albums_breakdown'] as $alb)
                albumSeries['{{ $alb['name'] }}'] = true;
            @endforeach
        @endif
    @endforeach
    for (var key in albumSeries) { orgHeader.push(key); }

    var orgRows = [];
    orgRows.push(orgHeader);
    @foreach($stats['organizations'] as $org)
        (function(){
            var row = ['{{ $org['name'] }}'];
            var values = {};
            @if(!empty($org['albums_breakdown']))
                @foreach($org['albums_breakdown'] as $alb)
                    values['{{ $alb['name'] }}'] = {{ round(($alb['total_size'] ?? 0) / 1024 / 1024, 2) }};
                @endforeach
            @endif
            // First push Unorganized value
            row.push({{ round((($org['unorganized_size'] ?? 0) / 1024 / 1024), 2) }});
            // Then push album series values in header order (skipping index 0 and 1 which are Organization, Unorganized)
            for (var i = 2; i < orgHeader.length; i++) {
                var label = orgHeader[i];
                row.push(values[label] ? values[label] : 0);
            }
            orgRows.push(row);
        })();
    @endforeach

    if (orgRows.length > 1) {
        var orgData = google.visualization.arrayToDataTable(orgRows);
        var orgOptions = {
            isStacked: true,
            legend: { position: 'top', alignment: 'end' },
            // Palette: Unorganized first (gray), then albums colors
            colors: ['#6c757d','#0d6efd','#20c997','#ffc107','#dc3545','#6610f2','#198754','#fd7e14','#0dcaf0','#6f42c1'],
            height: Math.min(500, 140 + (orgRows.length * 32)),
            chartArea: { left: 160, top: 60, width: '60%', height: '70%' },
            hAxis: { minValue: 0, format: 'short' }
        };
        var orgChart = new google.visualization.BarChart(document.getElementById('orgStorageChart'));
        orgChart.draw(orgData, orgOptions);
        var orgContainer = document.getElementById('orgStorageChartContainer');
        if (orgContainer) { orgContainer.style.display = 'block'; }
    } else {
        var orgContainer = document.getElementById('orgStorageChartContainer');
        if (orgContainer) { orgContainer.style.display = 'none'; }
    }

    // All Albums Usage across Organizations (flat bar chart)
    var allAlbumsRows = [['Album', 'Size (MB)']];
    @foreach($stats['organizations'] as $org)
        @if(!empty($org['albums_breakdown']))
            @foreach($org['albums_breakdown'] as $alb)
                allAlbumsRows.push(['{{ $org['name'] }} — {{ $alb['name'] }}', {{ round(($alb['total_size'] ?? 0) / 1024 / 1024, 2) }}]);
            @endforeach
        @endif
    @endforeach

    var allAlbumsContainer = document.getElementById('orgAllAlbumsChartContainer');
    if (allAlbumsRows.length > 1) {
        var allAlbumsData = google.visualization.arrayToDataTable(allAlbumsRows);
        var allAlbumsOptions = {
            legend: { position: 'none' },
            colors: ['#20c997'],
            height: Math.min(500, 60 + (allAlbumsRows.length * 24)),
            chartArea: { left: 180, top: 20, width: '60%', height: '80%' },
            hAxis: { minValue: 0, format: 'short' }
        };
        var allAlbumsChart = new google.visualization.BarChart(document.getElementById('orgAllAlbumsChart'));
        allAlbumsChart.draw(allAlbumsData, allAlbumsOptions);
        if (allAlbumsContainer) { allAlbumsContainer.style.display = 'block'; }
    } else {
        if (allAlbumsContainer) { allAlbumsContainer.style.display = 'none'; }
    }
}

// Redraw chart when window is resized
window.addEventListener('resize', drawCharts);
</script>
@endpush

@section('content')
<style>
.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
    text-align: center;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Removed icon from page title */

.page-subtitle {
    color: #6c757d;
    font-size: 0.95rem;
    margin-top: 0.25rem;
    margin-bottom: 0;
}

.stats-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--card-color);
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.stats-card.primary::before { --card-color: #007bff; }
.stats-card.success::before { --card-color: #28a745; }
.stats-card.warning::before { --card-color: #ffc107; }
.stats-card.info::before { --card-color: #17a2b8; }

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 1rem;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #2c3e50;
}

.stats-label {
    color: #6c757d;
    font-weight: 500;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.content-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.equal-height-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.equal-height-card .content-card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.content-card-header {
    background: #f8f9fa;
    padding: 1.25rem;
    border-bottom: 1px solid #e9ecef;
}

.content-card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
}

.photo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.photo-item {
    aspect-ratio: 1;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.photo-item:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.photo-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.album-item {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.album-item:last-child {
    border-bottom: none;
}

.album-item:hover {
    background: #f8f9fa;
}

.album-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-right: 1rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.storage-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.storage-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.storage-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.storage-info {
    flex: 1;
}

.storage-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.storage-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.table-sm {
    font-size: 0.875rem;
}

.table-sm th {
    font-weight: 600;
    color: #495057;
    border-top: none;
}

.table-sm td {
    color: #6c757d;
}

.storage-legend {
    padding: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.legend-item:last-child {
    margin-bottom: 0;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
}

.legend-info {
    flex: 1;
}

.legend-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.legend-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}


.upload-btn {
    display: inline-block;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    transition: all 0.2s ease;
    border: none;
    font-weight: 500;
}

.upload-btn:hover {
    background: #0056b3;
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}
</style>

<div class="container dashboard-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            Welcome back, {{ auth()->user()->name }}!
        </h1>
        <p class="page-subtitle">Here's what's happening with your photo collection today</p>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card primary">
                <div class="stats-icon" style="background: linear-gradient(135deg, #007bff, #66b3ff); color: white;">
                    <i class="bi bi-image"></i>
                </div>
                <div class="stats-number">{{ $stats['photos_count'] ?? 0 }}</div>
                <div class="stats-label">Total Photos</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card success">
                <div class="stats-icon" style="background: linear-gradient(135deg, #28a745, #6cbb6f); color: white;">
                    <i class="bi bi-folder"></i>
                </div>
                <div class="stats-number">{{ $stats['albums_count'] ?? 0 }}</div>
                <div class="stats-label">Albums</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card warning">
                <div class="stats-icon" style="background: linear-gradient(135deg, #ffc107, #ffd43b); color: white;">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stats-number">{{ $stats['organizations_count'] ?? 0 }}</div>
                <div class="stats-label">Organizations</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card info">
                <div class="stats-icon" style="background: linear-gradient(135deg, #17a2b8, #5bc0de); color: white;">
                    <i class="bi bi-eye"></i>
                </div>
                <div class="stats-number">{{ $stats['public_photos_count'] ?? 0 }}</div>
                <div class="stats-label">Public Photos</div>
            </div>
        </div>
    </div>

    <!-- Usage Limits Section -->
    @php
        $userLimits = auth()->user()->limits;
    @endphp
    @if($userLimits)
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h5>Your Usage Limits</h5>
        </div>
        <div class="p-4">
            <div class="row">
                <!-- Photos Limit -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="storage-item">
                        <div class="storage-icon" style="background: linear-gradient(135deg, #17a2b8, #5bc0de);">
                            <i class="bi bi-camera"></i>
                        </div>
                        <div class="storage-info">
                            <div class="storage-label">Photos</div>
                            <div class="storage-value">
                                {{ $userLimits->current_photos }} / {{ $userLimits->unlimited_photos ? '∞' : $userLimits->max_photos }}
                            </div>
                            @if(!$userLimits->unlimited_photos)
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar 
                                    @if($userLimits->current_photos >= $userLimits->max_photos) bg-danger
                                    @elseif($userLimits->current_photos >= $userLimits->max_photos * 0.8) bg-warning
                                    @else bg-primary @endif" 
                                    style="width: {{ min(100, ($userLimits->current_photos / $userLimits->max_photos) * 100) }}%">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Storage Limit -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="storage-item">
                        <div class="storage-icon" style="background: linear-gradient(135deg, #28a745, #6cbb6f);">
                            <i class="bi bi-hdd"></i>
                        </div>
                        <div class="storage-info">
                            <div class="storage-label">Storage</div>
                            <div class="storage-value">
                                {{ number_format($userLimits->current_storage_mb, 0) }} MB / {{ $userLimits->unlimited_storage ? '∞' : number_format($userLimits->max_storage_mb, 0) . ' MB' }}
                            </div>
                            @if(!$userLimits->unlimited_storage)
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar 
                                    @if($userLimits->current_storage_mb >= $userLimits->max_storage_mb) bg-danger
                                    @elseif($userLimits->current_storage_mb >= $userLimits->max_storage_mb * 0.8) bg-warning
                                    @else bg-success @endif" 
                                    style="width: {{ min(100, ($userLimits->current_storage_mb / $userLimits->max_storage_mb) * 100) }}%">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Albums Limit -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="storage-item">
                        <div class="storage-icon" style="background: linear-gradient(135deg, #ffc107, #ffd43b);">
                            <i class="bi bi-folder"></i>
                        </div>
                        <div class="storage-info">
                            <div class="storage-label">Albums</div>
                            <div class="storage-value">
                                {{ $userLimits->current_albums }} / {{ $userLimits->unlimited_albums ? '∞' : $userLimits->max_albums }}
                            </div>
                            @if(!$userLimits->unlimited_albums)
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar 
                                    @if($userLimits->current_albums >= $userLimits->max_albums) bg-danger
                                    @elseif($userLimits->current_albums >= $userLimits->max_albums * 0.8) bg-warning
                                    @else bg-warning @endif" 
                                    style="width: {{ min(100, ($userLimits->current_albums / $userLimits->max_albums) * 100) }}%">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Organizations Limit -->
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="storage-item">
                        <div class="storage-icon" style="background: linear-gradient(135deg, #17a2b8, #5bc0de);">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="storage-info">
                            <div class="storage-label">Organizations</div>
                            <div class="storage-value">
                                {{ $userLimits->current_organizations }} / {{ $userLimits->unlimited_organizations ? '∞' : $userLimits->max_organizations }}
                            </div>
                            @if(!$userLimits->unlimited_organizations)
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar 
                                    @if($userLimits->current_organizations >= $userLimits->max_organizations) bg-danger
                                    @elseif($userLimits->current_organizations >= $userLimits->max_organizations * 0.8) bg-warning
                                    @else bg-info @endif" 
                                    style="width: {{ min(100, ($userLimits->current_organizations / $userLimits->max_organizations) * 100) }}%">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Storage Statistics -->
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h5>Storage Statistics</h5>
        </div>
        <div class="p-4">
            <!-- Photo Types Storage -->
            <div class="mb-4">
                <h6 class="mb-3">Storage by Photo Type</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div id="photoTypeChart"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="storage-legend">
                            <div class="legend-item">
                                <div class="legend-color" style="background: #dc3545;"></div>
                                <div class="legend-info">
                                    <div class="legend-label">Private Photos</div>
                                    <div class="legend-value">{{ number_format($stats['private_photos_size'] / 1024 / 1024, 2) }} MB</div>
                                </div>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #28a745;"></div>
                                <div class="legend-info">
                                    <div class="legend-label">Public Photos</div>
                                    <div class="legend-value">{{ number_format($stats['public_photos_size'] / 1024 / 1024, 2) }} MB</div>
                                </div>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #ffc107;"></div>
                                <div class="legend-info">
                                    <div class="legend-label">Organization Photos</div>
                                    <div class="legend-value">{{ number_format($stats['org_photos_size'] / 1024 / 1024, 2) }} MB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Organizations Stats -->
            <div class="mb-4">
                <h6 class="mb-3">Organizations Storage</h6>
                <div id="orgStorageChartContainer" class="mb-3">
                    <div id="orgStorageChart"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Organization</th>
                                <th>Photos</th>
                                <th>Albums</th>
                                <th>Unorganized</th>
                                <th>Total Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['organizations'] as $org)
                                <tr>
                                    <td>{{ $org['name'] }}</td>
                                    <td>{{ $org['photos_count'] }}</td>
                                    <td>{{ $org['albums_count'] }}</td>
                                    <td>
                                        {{ number_format(($org['unorganized_size'] ?? 0) / 1024 / 1024, 2) }} MB
                                        <small class="text-muted">({{ $org['unorganized_count'] ?? 0 }} photos)</small>
                                    </td>
                                    <td>{{ number_format($org['total_size'] / 1024 / 1024, 2) }} MB</td>
                                </tr>
                                @if(!empty($org['albums_breakdown']) && count($org['albums_breakdown']) > 0)
                                <tr>
                                    <td colspan="5" class="bg-light">
                                        <div class="ms-2">
                                            <strong>Albums in {{ $org['name'] }}</strong>
                                            <div class="table-responsive mt-2">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Album</th>
                                                            <th>Photos</th>
                                                            <th>Total Size</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($org['albums_breakdown'] as $alb)
                                                        <tr>
                                                            <td>{{ $alb['name'] }}</td>
                                                            <td>{{ $alb['photos_count'] }}</td>
                                                            <td>{{ number_format(($alb['total_size'] ?? 0) / 1024 / 1024, 2) }} MB</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Albums Stats -->
            <div>
                <h6 class="mb-3">Albums Storage</h6>
                
                <!-- Albums Storage Chart -->
                @if(isset($stats['albums']) && count($stats['albums']) > 0)
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="albumsStorageChart"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="storage-legend">
                                @foreach($stats['albums'] as $index => $album)
                                <div class="legend-item">
                                    <div class="legend-color" style="background: {{ ['#0d6efd', '#20c997', '#ffc107', '#dc3545', '#6610f2', '#198754', '#fd7e14', '#0dcaf0', '#6f42c1'][$index % 9] }};"></div>
                                    <div class="legend-info">
                                        <div class="legend-label">{{ $album['name'] }}</div>
                                        <div class="legend-value">{{ number_format($album['total_size'] / 1024 / 1024, 2) }} MB ({{ $album['photos_count'] }} photos)</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Album</th>
                                <th>Photos</th>
                                <th>Total Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['albums'] as $album)
                                <tr>
                                    <td>{{ $album['name'] }}</td>
                                    <td>{{ $album['photos_count'] }}</td>
                                    <td>{{ number_format($album['total_size'] / 1024 / 1024, 2) }} MB</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="content-card equal-height-card">
                <div class="content-card-header d-flex justify-content-between align-items-center">
                    <h5>Recent Photos</h5>
                    <a href="{{ route('photos.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right me-1"></i>View All
                    </a>
                </div>
                <div class="content-card-body">
                @if(isset($recent_photos) && $recent_photos->count() > 0)
                    <div class="photo-grid">
                        @foreach($recent_photos as $photo)
                            <div class="photo-item">
                                <img src="{{ $photo->url }}" alt="{{ $photo->title }}">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-image"></i>
                        <h6>No photos yet</h6>
                        <p class="mb-3">Start building your photo collection</p>
                        <a href="{{ route('photos.create') }}" class="upload-btn">
                            <i class="bi bi-cloud-upload me-1"></i>Upload Photo
                        </a>
                    </div>
                @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="content-card equal-height-card">
                <div class="content-card-header d-flex justify-content-between align-items-center">
                    <h5>My Albums</h5>
                    <a href="{{ route('albums.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right me-1"></i>View All
                    </a>
                </div>
                <div class="content-card-body">
                @if(isset($albums) && $albums->count() > 0)
                    @foreach($albums as $album)
                        <div class="album-item d-flex align-items-center">
                            <div class="album-icon" style="background: linear-gradient(135deg, #007bff, #66b3ff); color: white;">
                                <i class="bi bi-folder"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">{{ $album->name }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-image me-1"></i>{{ $album->photos->count() }} photos
                                    @if($album->organization)
                                        <span class="mx-2">•</span>
                                        <i class="bi bi-people me-1"></i>{{ $album->organization->name }}
                                    @endif
                                </small>
                            </div>
                            <a href="{{ route('albums.show', $album->name) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>View
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="bi bi-folder"></i>
                        <h6>No albums yet</h6>
                        <p class="mb-3">Organize your photos into albums</p>
                        <a href="{{ route('albums.create') }}" class="btn btn-primary">
                            <i class="bi bi-folder-plus me-1"></i>Create Album
                        </a>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection