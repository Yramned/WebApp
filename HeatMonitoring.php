<?php
/**
 * SafeHaven – heat-monitoring.php
 * Situational Alerts / Heat Monitoring module
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) { header('Location: auth.php?action=login'); exit; }

$pageTitle  = 'SafeHaven – Monitoring';
$activePage = 'heat-monitoring';
$extraCss   = ['css/Heatmonitoring.css'];
$extraJs    = [];

require_once 'header.php';
?>

<div class="heat-page">
<main class="heat-main">
<div class="heat-container">

<!-- ─── Header row ─────────────────────────────── -->
<div class="heat-page-head">
    <div class="heat-page-head-left">
        <div class="heat-page-head-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
            </svg>
        </div>
        <div>
            <h2>Situational Alerts</h2>
            <p>Real-time disaster monitoring and sensor data</p>
        </div>
    </div>
    <div class="heat-live-badge">
        <span class="heat-live-dot"></span>
        LIVE
    </div>
</div>

<!-- ─── Map placeholder ────────────────────────── -->
<div class="map-box">
    <div class="map-placeholder">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
        </svg>
        <span>Interactive Map – Coming Soon</span>
    </div>
    <!-- Legend -->
    <div class="map-legend">
        <div class="legend-item"><span class="legend-dot ld-red"></span><span>Critical</span></div>
        <div class="legend-item"><span class="legend-dot ld-org"></span><span>Warning</span></div>
        <div class="legend-item"><span class="legend-dot ld-grn"></span><span>Normal</span></div>
    </div>
</div>

<!-- ─── Sensor cards ───────────────────────────── -->
<div class="sensor-grid">

    <div class="sensor-card status-critical">
        <div class="sensor-card-head">
            <span>🌡️ Temperature</span>
            <span class="badge badge-red">Critical</span>
        </div>
        <div class="sensor-val">38.4<span class="unit">°C</span></div>
        <div class="sensor-sub">↑ 2.1° from last hour</div>
    </div>

    <div class="sensor-card status-warn">
        <div class="sensor-card-head">
            <span>💧 Humidity</span>
            <span class="badge badge-orange">Warning</span>
        </div>
        <div class="sensor-val">82<span class="unit">%</span></div>
        <div class="sensor-sub">↑ 5% from last hour</div>
    </div>

    <div class="sensor-card status-ok">
        <div class="sensor-card-head">
            <span>🌬️ Wind Speed</span>
            <span class="badge badge-green">Normal</span>
        </div>
        <div class="sensor-val">14<span class="unit">km/h</span></div>
        <div class="sensor-sub">↓ 3 km/h from last hour</div>
    </div>

    <div class="sensor-card status-warn">
        <div class="sensor-card-head">
            <span>🌊 Flood Level</span>
            <span class="badge badge-orange">Warning</span>
        </div>
        <div class="sensor-val">2.8<span class="unit">m</span></div>
        <div class="sensor-sub">↑ 0.4m – rising</div>
    </div>

</div>

<!-- ─── Recent alerts list ─────────────────────── -->
<div class="alerts-section-head">
    <h3>Recent Alerts</h3>
    <a href="#">View all →</a>
</div>
<div class="alerts-list">

    <div class="alert-item">
        <div class="alert-item-icon ai-red">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="alert-item-body">
            <h4>Extreme Heat Alert – Barangay 18</h4>
            <p>Temperature has exceeded safe thresholds. Residents are advised to stay indoors and hydrate.</p>
            <div class="alert-item-meta">
                <span class="badge badge-red">Critical</span>
                <span class="ai-time">10 minutes ago</span>
            </div>
        </div>
    </div>

    <div class="alert-item">
        <div class="alert-item-icon ai-orange">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="alert-item-body">
            <h4>Rising Flood Level – District 3</h4>
            <p>Water level in creek has risen 0.4 m in the last 2 hours. Monitor closely.</p>
            <div class="alert-item-meta">
                <span class="badge badge-orange">Warning</span>
                <span class="ai-time">45 minutes ago</span>
            </div>
        </div>
    </div>

    <div class="alert-item">
        <div class="alert-item-icon ai-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div class="alert-item-body">
            <h4>System Update – Sensor Calibration</h4>
            <p>Sensors in Barangay 5 and 12 have been recalibrated. Data is now accurate.</p>
            <div class="alert-item-meta">
                <span class="badge badge-blue">Info</span>
                <span class="ai-time">2 hours ago</span>
            </div>
        </div>
    </div>

</div>

</div><!-- /heat-container -->
</main><!-- /heat-main -->
</div><!-- /heat-page -->

<?php require_once 'footer.php'; ?>