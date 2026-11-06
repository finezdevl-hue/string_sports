<?php
// admin/diagnose.php - STRINGS SPORTS Server & Database Diagnostic Tool
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? '';
$action_msg = '';

if ($action === 'seed_admin' && isset($pdo)) {
    try {
        $defaultPassword = password_hash('Strings@Sports2026!', PASSWORD_DEFAULT);
        // Delete existing or insert fresh strings_admin
        $st = $pdo->prepare("DELETE FROM admins WHERE username = 'strings_admin'");
        $st->execute();

        $st = $pdo->prepare("INSERT INTO admins (username, password, name, email) VALUES (?, ?, ?, ?)");
        $st->execute(['strings_admin', $defaultPassword, 'Master Admin', 'info@stringsports.com']);
        $action_msg = "<div style='color: #00ff88; font-weight: bold; margin-bottom: 1rem;'>[SUCCESS] Default admin account successfully created/reset! Username: strings_admin | Password: Strings@Sports2026!</div>";
    } catch (Exception $e) {
        $action_msg = "<div style='color: #ff4757; font-weight: bold; margin-bottom: 1rem;'>[ERROR] Failed to reset admin: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Server Diagnostics | STRINGS SPORTS</title>
    <style>
        body { background: #02050e; color: #e2e8f0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding: 2rem; }
        .card { background: #0b1329; border: 1px solid #1e293b; border-radius: 12px; padding: 1.5rem; max-width: 750px; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        h1 { color: #00f0ff; margin-top: 0; font-size: 1.5rem; border-bottom: 1px solid #1e293b; padding-bottom: 0.75rem; }
        .row { display: flex; justify-content: space-between; padding: 0.65rem 0; border-bottom: 1px solid #142042; font-size: 0.95rem; }
        .badge { padding: 3px 8px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
        .badge-success { background: rgba(0, 255, 136, 0.2); color: #00ff88; border: 1px solid #00ff88; }
        .badge-danger { background: rgba(255, 71, 87, 0.2); color: #ff4757; border: 1px solid #ff4757; }
        .btn { display: inline-block; padding: 0.6rem 1.2rem; background: #00f0ff; color: #02050e; text-decoration: none; border-radius: 8px; font-weight: 700; margin-top: 1rem; border: none; cursor: pointer; }
        .btn:hover { background: #38bdf8; }
        pre { background: #060b18; padding: 1rem; border-radius: 8px; color: #94a3b8; font-size: 0.85rem; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="card">
        <h1>STRINGS Server & Login Diagnostics</h1>
        <?= $action_msg ?>

        <div class="row">
            <span>PHP Version</span>
            <span class="badge badge-success"><?= PHP_VERSION ?></span>
        </div>

        <div class="row">
            <span>Session Status</span>
            <span>
                <?php if (session_status() === PHP_SESSION_ACTIVE): ?>
                    <span class="badge badge-success">ACTIVE (ID: <?= substr(session_id(), 0, 8) ?>...)</span>
                <?php else: ?>
                    <span class="badge badge-danger">INACTIVE / FAILED</span>
                <?php endif; ?>
            </span>
        </div>

        <div class="row">
            <span>Database Connection ($pdo)</span>
            <span>
                <?php if (isset($pdo) && $pdo instanceof PDO): ?>
                    <span class="badge badge-success">CONNECTED</span>
                <?php else: ?>
                    <span class="badge badge-danger">FAILED</span>
                <?php endif; ?>
            </span>
        </div>

        <?php if (isset($pdo)): ?>
            <div class="row">
                <span>Database Name</span>
                <span><code><?= htmlspecialchars($db_name ?? 'unknown') ?></code></span>
            </div>

            <div class="row">
                <span>Database User</span>
                <span><code><?= htmlspecialchars($db_user ?? 'unknown') ?></code></span>
            </div>

            <div class="row">
                <span>Admins Table</span>
                <span>
                    <?php
                    $tables = $pdo->query("SHOW TABLES LIKE 'admins'")->fetchAll();
                    if (count($tables) > 0):
                    ?>
                        <span class="badge badge-success">EXISTS</span>
                    <?php else: ?>
                        <span class="badge badge-danger">NOT FOUND</span>
                    <?php endif; ?>
                </span>
            </div>

            <?php if (count($tables) > 0): ?>
                <?php
                $admins = $pdo->query("SELECT id, username, email, name FROM admins")->fetchAll();
                ?>
                <div class="row">
                    <span>Admin Accounts Found</span>
                    <span><strong><?= count($admins) ?></strong> account(s)</span>
                </div>

                <div style="margin-top: 1rem;">
                    <strong>Existing Admins:</strong>
                    <?php if (count($admins) > 0): ?>
                        <ul>
                            <?php foreach ($admins as $adm): ?>
                                <li>Username: <code><?= htmlspecialchars($adm['username']) ?></code> | Email: <code><?= htmlspecialchars($adm['email']) ?></code> (<?= htmlspecialchars($adm['name']) ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="color: #ff4757;">No admin users in the database! Click the button below to generate the default admin account.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div style="margin-top: 1.5rem; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="diagnose.php?action=seed_admin" class="btn">
                <i class="fa-solid fa-key"></i> Force Reset/Create 'strings_admin'
            </a>
            <a href="login.php" class="btn" style="background: #1e293b; color: #fff;">
                Go to Login Page &rarr;
            </a>
        </div>
    </div>
</body>
</html>
