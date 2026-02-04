<?php
/**
 * SafeHaven – auth.php
 * Handles:  ?action=login  |  ?action=register  |  ?action=logout
 *           POST login_submit  |  POST register_submit
 */
session_start();

/* ─── Logout ─────────────────────────────────────── */
if (($_GET['action'] ?? '') === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

/* ─── POST – Login ───────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'login_submit') {
    $email    = strtolower(trim($_POST['email']    ?? ''));
    $password = $_POST['password'] ?? '';
    $errs     = [];

    if (empty($email))    $errs[] = 'Email is required.';
    if (empty($password)) $errs[] = 'Password is required.';

    if (empty($errs)) {
        // Load users from JSON
        $usersFile = 'storage/users.json';
        $users     = file_exists($usersFile) ? (json_decode(file_get_contents($usersFile), true) ?: []) : [];
        $found     = null;
        foreach ($users as $u) { if (strtolower($u['email']) === $email) { $found = $u; break; } }

        if (!$found || !password_verify($password, $found['password'])) {
            $errs[] = 'Invalid email or password.';
        } else {
            $_SESSION['user_id']    = $found['id'];
            $_SESSION['user_name']  = $found['full_name'];
            $_SESSION['user_email'] = $found['email'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $_SESSION['auth_errors']    = $errs;
    $_SESSION['auth_old_email'] = $email;
    header('Location: auth.php?action=login');
    exit;
}

/* ─── POST – Register ────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'register_submit') {
    $d = [
        'full_name'    => trim($_POST['full_name']    ?? ''),
        'phone_number' => trim($_POST['phone_number'] ?? ''),
        'address'      => trim($_POST['address']      ?? ''),
        'email'        => strtolower(trim($_POST['email'] ?? '')),
        'password'     => $_POST['password'] ?? '',
    ];
    $errs = [];
    if (strlen($d['full_name']) < 2)  $errs[] = 'Full name must be at least 2 characters.';
    if (empty($d['phone_number']))    $errs[] = 'Phone number is required.';
    if (empty($d['address']))         $errs[] = 'Address (Barangay) is required.';
    if (empty($d['email']))           $errs[] = 'Email is required.';
    elseif (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $errs[] = 'Invalid email.';
    if (strlen($d['password']) < 6)   $errs[] = 'Password must be at least 6 characters.';

    if (empty($errs)) {
        @mkdir('storage', 0755, true);
        $usersFile = 'storage/users.json';
        $users     = file_exists($usersFile) ? (json_decode(file_get_contents($usersFile), true) ?: []) : [];

        // Check duplicate email
        foreach ($users as $u) {
            if (strtolower($u['email']) === $d['email']) { $errs[] = 'This email is already registered.'; break; }
        }
    }

    if (empty($errs)) {
        $newId = empty($users) ? 1 : max(array_column($users, 'id')) + 1;
        $users[] = [
            'id'            => $newId,
            'full_name'     => $d['full_name'],
            'phone_number'  => $d['phone_number'],
            'address'       => $d['address'],
            'email'         => $d['email'],
            'password'      => password_hash($d['password'], PASSWORD_BCRYPT),
            'role'          => 'evacuee',
            'created_at'    => date('Y-m-d H:i:s'),
        ];
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

        // Auto-login
        $_SESSION['user_id']    = $newId;
        $_SESSION['user_name']  = $d['full_name'];
        $_SESSION['user_email'] = $d['email'];
        header('Location: dashboard.php');
        exit;
    }

    $_SESSION['auth_errors']  = $errs;
    $_SESSION['auth_old_data'] = $d;
    unset($_SESSION['auth_old_data']['password']);
    header('Location: auth.php?action=register');
    exit;
}

/* ═══════════════════════════════════════════════════════
   RENDER – determine which form to show
   ═══════════════════════════════════════════════════════ */
$action = $_GET['action'] ?? 'login';   // 'login' | 'register'
$isReg  = ($action === 'register');

$pageTitle = $isReg ? 'SafeHaven – Register' : 'SafeHaven – Login';
$extraCss  = ['css/auth.css'];
$extraJs   = ['js/auth.js'];

// Pull flash data
$errors  = $_SESSION['auth_errors'] ?? [];
$oldEmail = $_SESSION['auth_old_email'] ?? '';
$oldData  = $_SESSION['auth_old_data']  ?? [];
unset($_SESSION['auth_errors'], $_SESSION['auth_old_email'], $_SESSION['auth_old_data']);

// Suppress navbar for auth pages – we render a standalone page
// (header.php outputs <nav> but auth has its own layout; we skip it cleanly)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/header_footer_styles.css" />
    <link rel="stylesheet" href="css/auth.css" />
</head>
<body>

<div class="auth-page">
    <!-- Blobs -->
    <div class="auth-blob ab-1"></div>
    <div class="auth-blob ab-2"></div>
    <div class="auth-blob ab-3"></div>

    <div class="auth-wrap <?= $isReg ? 'wide' : '' ?>">

        <!-- Logo -->
        <div class="auth-logo">
            <div class="auth-logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#5dade2" stroke-width="2" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <h1 class="auth-brand">SafeHaven</h1>
            <p class="auth-brand-sub">Evacuation &amp; Rescue Management System</p>
        </div>

        <!-- Tabs -->
        <div class="auth-tabs">
            <a href="auth.php?action=login"    class="auth-tab <?= !$isReg ? 'active' : '' ?>">Login</a>
            <a href="auth.php?action=register" class="auth-tab <?= $isReg  ? 'active' : '' ?>">Sign up</a>
        </div>

        <!-- Card -->
        <div class="auth-card">
            <?php if (!empty($errors)): ?>
                <div class="auth-alert auth-alert-err">
                    <?php foreach ($errors as $e): ?><p><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($isReg): /* ── REGISTER FORM ──────────────────── */ ?>

            <form action="auth.php" method="POST">
                <input type="hidden" name="_action" value="register_submit" />

                <div class="ag">
                    <label>Full Name</label>
                    <div class="inp-wrap">
                        <span class="inp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="8" r="4"/></svg></span>
                        <input type="text" name="full_name" placeholder="Juan dela Cruz" value="<?= htmlspecialchars($oldData['full_name'] ?? '') ?>" required />
                    </div>
                </div>
                <div class="ag">
                    <label>Phone Number</label>
                    <div class="inp-wrap">
                        <span class="inp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
                        <input type="tel" name="phone_number" placeholder="+63 9XX XXX XXXX" value="<?= htmlspecialchars($oldData['phone_number'] ?? '') ?>" required />
                    </div>
                </div>
                <div class="ag">
                    <label>Address (Barangay)</label>
                    <div class="inp-wrap">
                        <span class="inp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                        <input type="text" name="address" placeholder="e.g. Brgy. Sta. Ana, Cebu" value="<?= htmlspecialchars($oldData['address'] ?? '') ?>" required />
                    </div>
                </div>
                <div class="ag">
                    <label>Email Address</label>
                    <div class="inp-wrap">
                        <span class="inp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                        <input type="email" name="email" placeholder="you@email.com" value="<?= htmlspecialchars($oldData['email'] ?? '') ?>" required />
                    </div>
                </div>
                <div class="ag">
                    <label>Password</label>
                    <div class="inp-wrap">
                        <span class="inp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                        <input type="password" name="password" id="regPassword" placeholder="••••••••" required />
                        <button type="button" class="pw-toggle" data-target="regPassword">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <span class="hint" id="pwHint">Minimum 6 characters</span>
                </div>

                <button type="submit" class="btn-auth">Create Account</button>
            </form>

            <?php else: /* ── LOGIN FORM ─────────────────────────── */ ?>

            <form action="auth.php" method="POST">
                <input type="hidden" name="_action" value="login_submit" />

                <div class="ag">
                    <label>Email</label>
                    <div class="inp-wrap">
                        <span class="inp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                        <input type="email" name="email" placeholder="your@email.com" value="<?= htmlspecialchars($oldEmail) ?>" required autocomplete="email" />
                    </div>
                </div>
                <div class="ag">
                    <label>Password</label>
                    <div class="inp-wrap">
                        <span class="inp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                        <input type="password" name="password" id="loginPassword" placeholder="••••••••" required autocomplete="current-password" />
                        <button type="button" class="pw-toggle" data-target="loginPassword">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <label class="chk-label">
                        <input type="checkbox" name="remember" />
                        <span class="chk-box"></span>
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-auth">Login to SafeHaven</button>
            </form>

            <?php endif; ?>
        </div>

        <!-- Footer link -->
        <p class="auth-foot">
            <?= $isReg ? 'Already have an account? <a href="auth.php?action=login">Login</a>'
                       : 'Don\'t have an account? <a href="auth.php?action=register">Sign up</a>' ?>
        </p>
    </div>
</div>

<script src="js/auth.js"></script>
</body>
</html>