<?php
/**
 * SafeHaven – Dashboard Header (ISOLATED & SAFE)
 * This header ONLY affects itself. It will NOT touch dashboard CSS.
 *
 * REQUIRED BEFORE INCLUDING:
 * $pageTitle
 * $activePage
 * $extraCss
 * $extraJs
 */

$pageTitle  = $pageTitle  ?? 'SafeHaven Dashboard';
$activePage = $activePage ?? '';
$extraCss   = $extraCss   ?? [];
$extraJs    = $extraJs    ?? [];
$userName   = $_SESSION['user_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Page-specific CSS FIRST -->
    <?php foreach ($extraCss as $css): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
    <?php endforeach; ?>

    <!-- HEADER-ONLY STYLES (SCOPED) -->
    <style>
        .dashboard-header {
            --hd-bg: #1a2332;
            --hd-blue: #3498db;
            --hd-teal: #1abc9c;
            --hd-text: #ecf0f1;
            --hd-muted: #95a5a6;
        }

        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: var(--hd-bg);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-header * {
            box-sizing: border-box;
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Logo */
        .header-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--hd-text);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--hd-teal);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon svg {
            width: 22px;
            height: 22px;
            color: #fff;
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        /* Navigation */
        .header-nav {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--hd-muted);
            text-decoration: none;
            transition: 0.2s;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: var(--hd-text);
            background: rgba(255,255,255,0.05);
        }

        .nav-link.active {
            color: var(--hd-blue);
            background: rgba(52,152,219,0.12);
        }

        /* User section */
        .header-user {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .user-greeting {
            font-size: 14px;
            color: var(--hd-muted);
        }

        .user-greeting span {
            color: var(--hd-blue);
            font-weight: 600;
        }

        .header-logout {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #e74c3c;
            text-decoration: none;
            background: rgba(231,76,60,0.12);
            border: 1px solid rgba(231,76,60,0.35);
            transition: 0.2s;
            white-space: nowrap;
        }

        .header-logout:hover {
            background: rgba(231,76,60,0.2);
            border-color: #e74c3c;
        }

        /* Mobile */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--hd-text);
            cursor: pointer;
            padding: 8px;
        }

        .mobile-toggle svg {
            width: 24px;
            height: 24px;
        }

        @media (max-width: 768px) {
            .header-nav {
                display: none;
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                background: var(--hd-bg);
                flex-direction: column;
                padding: 16px;
                border-top: 1px solid rgba(255,255,255,0.08);
            }

            .header-nav.open {
                display: flex;
            }

            .nav-link {
                width: 100%;
                padding: 12px 16px;
            }

            .mobile-toggle {
                display: block;
            }

            .user-greeting {
                display: none;
            }
        }
    </style>
</head>

<body>

<header class="dashboard-header">
    <div class="header-container">

        <!-- Logo -->
        <a href="Dashboard.php" class="header-logo">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                </svg>
            </div>
            <span class="logo-text">SafeHaven</span>
        </a>

        <!-- Navigation -->
        <nav class="header-nav" id="headerNav">
            <a href="Dashboard.php" class="nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a href="evacuation-request.php" class="nav-link <?= $activePage === 'evacuation-request' ? 'active' : '' ?>">Evacuation Request</a>
            <a href="HeatMonitoring.php" class="nav-link <?= $activePage === 'heat-monitoring' ? 'active' : '' ?>">Situational Alerts</a>
            <a href="user-management.php" class="nav-link <?= $activePage === 'user-management' ? 'active' : '' ?>">User Management</a>
        </nav>

        <!-- User -->
        <div class="header-user">
            <span class="user-greeting">Hi, <span><?= htmlspecialchars($userName) ?></span></span>
            <a href="auth.php?action=logout" class="header-logout">Logout</a>
            <button class="mobile-toggle" onclick="toggleHeaderMenu()" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
        </div>

    </div>
</header>

<script>
function toggleHeaderMenu() {
    document.getElementById('headerNav').classList.toggle('open');
}

document.addEventListener('click', function (e) {
    const nav = document.getElementById('headerNav');
    const toggle = document.querySelector('.mobile-toggle');
    if (!nav.contains(e.target) && !toggle.contains(e.target)) {
        nav.classList.remove('open');
    }
});
</script>
