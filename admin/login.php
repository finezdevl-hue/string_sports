<?php
// admin/login.php - STRINGS SPORTS Admin Authentication
require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please enter both username/email and password.";
    } else {
        try {
            // Check if admins table exists; if not, initialize schema
            $tableCheck = $pdo->query("SHOW TABLES LIKE 'admins'")->fetchAll();
            if (empty($tableCheck)) {
                initDatabaseSchema($pdo);
            }

            // Ensure at least one admin exists
            $adminCount = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
            if ($adminCount == 0) {
                $defaultPassword = password_hash('Strings@Sports2026!', PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO admins (username, password, name, email) VALUES (?, ?, ?, ?)");
                $stmt->execute(['strings_admin', $defaultPassword, 'Master Admin', 'info@stringsports.com']);
            }

            // Look up by username or email
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_user'] = $admin['username'];
                $_SESSION['admin_name'] = $admin['name'];
                setFlash('success', "Welcome back, " . htmlspecialchars($admin['name']) . "!");
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username or password. (Default: strings_admin / Strings@Sports2026!)";
            }
        } catch (PDOException $e) {
            $error = "Database query error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login | STRINGS SPORTS</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800;900&family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
</head>
<body style="background: #02050e; margin: 0;">
    <div class="admin-login-wrapper">
        <div class="admin-login-box">
            <div style="text-align: center; margin-bottom: 2rem;">
                <img src="../assets/images/logo.png" alt="STRINGS Logo" style="height: 68px; width: auto; padding: 6px 12px; background: rgba(7, 13, 30, 0.85); border-radius: 12px; margin: 0 auto 1rem; border: 1px solid var(--adm-cyan); box-shadow: 0 0 20px rgba(0, 240, 255, 0.4); object-fit: contain;">
                <h1 style="font-family: 'Outfit', sans-serif; font-size: 1.85rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem;">
                    STRINGS <span class="text-cyan">ADMIN</span>
                </h1>
                <p style="font-size: 0.85rem; color: var(--adm-muted); letter-spacing: 0.05em;">SECURE SPORTS MANAGEMENT PORTAL</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div><?= htmlspecialchars($error) ?></div>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username" style="color: var(--text-light); font-size: 0.85rem;">Username</label>
                    <input type="text" id="username" name="username" required class="form-input" placeholder="Enter admin username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" autocomplete="username">
                </div>

                <div class="form-group" style="margin-bottom: 1.75rem;">
                    <label for="password" style="color: var(--text-light); font-size: 0.85rem;">Password</label>
                    <input type="password" id="password" name="password" required class="form-input" placeholder="Enter password" autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-cyan btn-full btn-glow btn-lg">
                    <i class="fa-solid fa-unlock-keyhole"></i> Authorize & Enter
                </button>
            </form>

            <div style="text-align: center; margin-top: 2rem;">
                <a href="../index.php" style="color: var(--adm-cyan); font-size: 0.85rem; text-decoration: none;">
                    <i class="fa-solid fa-arrow-left"></i> Return to Main Website
                </a>
            </div>
        </div>
    </div>
</body>
</html>
