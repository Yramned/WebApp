<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?action=login');
    exit;
}

$pageTitle  = 'SafeHaven – Dashboard';
$activePage = 'dashboard';
$extraCss   = ['css/Dashboard.css'];
$extraJs    = [];

require_once 'header.php';
?>

<div class="dash-page">
<main class="dash-main">
<div class="dash-container">

<!-- ─── Welcome banner ─────────────────────────── -->
<div class="dash-banner">
    <div>
        <h2>Welcome back, <span><?= htmlspecialchars($_SESSION['user_name']) ?></span></h2>
        <p>You are now connected to the SafeHaven Emergency Evacuation Network.</p>
    </div>
    <div class="dash-banner-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    </div>
</div>

<!-- ─── Stat cards ─────────────────────────────── -->
<div class="stat-row">
    <div class="stat-card top-green">
        <div class="stat-icon si-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div><div class="stat-label">Profile</div><div class="stat-value">Verified</div></div>
    </div>
    <div class="stat-card top-blue">
        <div class="stat-icon si-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div><div class="stat-label">Alerts</div><div class="stat-value">Active</div></div>
    </div>
    <div class="stat-card top-orange">
        <div class="stat-icon si-orange">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div><div class="stat-label">Last Check</div><div class="stat-value">Just Now</div></div>
    </div>
</div>

<!-- ─── Module grid ────────────────────────────── -->
<div class="modules-head">
    <h3>Modules</h3>
    <a href="#">View all →</a>
</div>
<div class="modules-grid">

    <!-- Evacuation Request -->
    <a href="EvacuationRequest.php" class="module-card">
        <div class="mod-icon mi-red">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="mod-info">
            <h4>Evacuation Request</h4>
            <p>Submit a GPS-tagged request with priority and special needs.</p>
        </div>
    </a>

    <!-- Situational Alerts -->
    <a href="HeatMonitoring.php" class="module-card">
        <div class="mod-icon mi-orange">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div class="mod-info">
            <h4>Situational Alerts</h4>
            <p>Live disaster monitoring, heat maps, and sensor readings.</p>
        </div>
    </a>

    <!-- User Management -->
    <a href="user-management.php" class="module-card">
        <div class="mod-icon mi-teal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="mod-info">
            <h4>User Management</h4>
            <p>View, search, and manage registered evacuees and staff.</p>
        </div>
    </a>

    <!-- Evacuation Centers -->
    <a href="#" class="module-card">
        <div class="mod-icon mi-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div class="mod-info">
            <h4>Evacuation Centers</h4>
            <p>Browse nearby centers with real-time capacity and directions.</p>
        </div>
    </a>

    <!-- SMS Notifications -->
    <a href="#" class="module-card">
        <div class="mod-icon mi-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="mod-info">
            <h4>SMS Notifications</h4>
            <p>Manage and send SMS alerts to evacuees in real time.</p>
        </div>
    </a>

    <!-- Reports -->
    <a href="#" class="module-card">
        <div class="mod-icon mi-yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        </div>
        <div class="mod-info">
            <h4>Reports</h4>
            <p>Generate and download evacuation incident reports.</p>
        </div>
    </a>

</div>

<!-- Logout -->
<div class="logout-row">
    <a href="auth.php?action=logout" class="btn-logout">Logout</a>
</div>

</div><!-- /dash-container -->
</main><!-- /dash-main -->
</div><!-- /dash-page -->

<?php require_once 'footer.php'; ?>