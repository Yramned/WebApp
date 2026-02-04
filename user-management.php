<?php
/**
 * SafeHaven – User Management (Self-Contained Version)
 * This version includes CSS and JS internally to prevent "Not Found" errors.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// --- 1. DATA & PERMISSIONS ---
$storageDir = __DIR__ . '/storage';
$usersFile  = $storageDir . '/users.json';

if (!is_dir($storageDir)) { @mkdir($storageDir, 0777, true); }

if (!file_exists($usersFile) || filesize($usersFile) == 0) {
    $initialUsers = [
        ['id'=>1,'full_name'=>'Maria Santos','email'=>'maria@example.com','phone_number'=>'+63 917 123 4567','address'=>'Brgy. Sta. Ana','role'=>'evacuee','created_at'=>'2026-01-28 09:00:00'],
        ['id'=>2,'full_name'=>'Juan dela Cruz','email'=>'juan@example.com','phone_number'=>'+63 918 234 5678','address'=>'Brgy. Poblacion','role'=>'center_manager','created_at'=>'2026-01-29 10:30:00'],
        ['id'=>3,'full_name'=>'Ana Reyes','email'=>'ana@example.com','phone_number'=>'+63 919 345 6789','address'=>'Brgy. Silang','role'=>'evacuee','created_at'=>'2026-01-30 14:15:00']
    ];
    file_put_contents($usersFile, json_encode($initialUsers, JSON_PRETTY_PRINT));
}

$users = json_decode(file_get_contents($usersFile), true) ?: [];

// --- 2. HELPERS ---
function initials($name) {
    $parts = explode(' ', trim($name));
    $out = '';
    foreach ($parts as $p) { if (!empty($p)) $out .= strtoupper($p[0]); }
    return substr($out, 0, 2);
}

// Stats
$totalUsers = count($users);
$evacuees   = count(array_filter($users, fn($u) => ($u['role']??'') === 'evacuee'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeHaven – User Management</title>
    
    <style>
        /* --- INLINED CSS --- */
        :root {
            --accent: #3498db; --teal: #1abc9c; --white: #ffffff;
            --text-primary: #ecf0f1; --navy-800: #2c3e50; --navy-900: #1f2d3d;
        }

        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--navy-900); color: var(--text-primary); }
        .um-main { padding: 40px 20px; }
        .um-container { max-width: 1000px; margin: 0 auto; }
        
        /* Header & Stats */
        .um-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .um-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .um-stat { background: var(--navy-800); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); }
        
        /* Table Style */
        .um-table-wrap { background: var(--navy-800); border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); }
        .um-table { width: 100%; border-collapse: collapse; }
        .um-table th, .um-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .um-avatar { width: 35px; height: 35px; background: var(--accent); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-weight: bold; }

        /* Badges */
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; }
        .badge-blue { background: #3498db; }
        .badge-green { background: #2ecc71; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); align-items: center; justify-content: center; z-index: 100; }
        .modal-box { background: var(--navy-800); padding: 30px; border-radius: 15px; width: 400px; }
        .modal-overlay.open { display: flex; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; background: #34495e; border: 1px solid #555; color: white; border-radius: 5px; }
        .btn-add-user { background: var(--teal); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>

<div class="um-page">
    <main class="um-main">
        <div class="um-container">
            
            <div class="um-head">
                <div>
                    <h2>User Management</h2>
                    <p>Total Registered: <?= $totalUsers ?></p>
                </div>
                <button class="btn-add-user" onclick="toggleModal(true)">+ Add New User</button>
            </div>

            <div class="um-stats">
                <div class="um-stat"><b>Evacuees:</b> <?= $evacuees ?></div>
                <div class="um-stat"><b>Active Staff:</b> <?= ($totalUsers - $evacuees) ?></div>
            </div>

            <div class="um-table-wrap">
                <table class="um-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <div class="um-avatar"><?= initials($u['full_name']) ?></div>
                                <?= htmlspecialchars($u['full_name']) ?>
                            </td>
                            <td><span class="badge badge-blue"><?= ucfirst(str_replace('_',' ',$u['role'])) ?></span></td>
                            <td><?= htmlspecialchars($u['phone_number'] ?? 'N/A') ?></td>
                            <td>
                                <button onclick="alert('Edit logic triggered')" style="cursor:pointer">✏️</button>
                                <button onclick="this.closest('tr').remove()" style="cursor:pointer">🗑️</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal-overlay" id="modalOverlay">
    <div class="modal-box">
        <h3>Add New User</h3>
        <form onsubmit="event.preventDefault(); alert('User saved!'); toggleModal(false);">
            <label>Full Name</label>
            <input type="text" placeholder="e.g. Juan Dela Cruz" required>
            <label>Email</label>
            <input type="email" placeholder="email@example.com" required>
            <label>Role</label>
            <select>
                <option value="evacuee">Evacuee</option>
                <option value="center_manager">Center Manager</option>
            </select>
            <div style="margin-top:20px; display:flex; gap:10px;">
                <button type="submit" class="btn-add-user">Save User</button>
                <button type="button" onclick="toggleModal(false)" style="background:#7f8c8d; color:white; border:none; padding:10px; border-radius:8px;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    /* --- INLINED JS --- */
    function toggleModal(show) {
        const modal = document.getElementById('modalOverlay');
        if (show) modal.classList.add('open');
        else modal.classList.remove('open');
    }

    // Close modal if clicking outside the box
    window.onclick = function(event) {
        const modal = document.getElementById('modalOverlay');
        if (event.target == modal) toggleModal(false);
    }
</script>

</body>
</html>