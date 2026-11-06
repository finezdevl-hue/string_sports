<?php
// admin/includes/admin_header.php
require_once __DIR__ . '/../../config/db.php';
checkAdminAuth();

$active_page = basename($_SERVER['PHP_SELF']);

// Count new inquiries for notification badge
$newInquiriesCount = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($admin_title) ? htmlspecialchars($admin_title) . " | STRINGS Admin" : "STRINGS Sports | Admin Portal" ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Admin & Main CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
</head>
<body class="admin-body">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar-header">
            <img src="../assets/images/logo.png" alt="STRINGS Logo" class="admin-logo-img">
            <div>
                <div class="admin-brand-title">STRINGS</div>
                <div class="admin-brand-sub">ADMIN CONTROL</div>
            </div>
        </div>

        <ul class="admin-nav-list">
            <li>
                <a href="index.php" class="admin-nav-link <?= ($active_page == 'index.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="products.php" class="admin-nav-link <?= in_array($active_page, ['products.php', 'product_add.php', 'product_edit.php']) ? 'active' : '' ?>">
                    <i class="fa-solid fa-shuttlecock"></i> Products
                </a>
            </li>
            <li>
                <a href="inquiries.php" class="admin-nav-link <?= ($active_page == 'inquiries.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-inbox"></i> Inquiries
                    <?php if ($newInquiriesCount > 0): ?>
                        <span class="nav-badge-count"><?= $newInquiriesCount ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="settings.php" class="admin-nav-link <?= ($active_page == 'settings.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-sliders"></i> Settings
                </a>
            </li>
        </ul>

        <div class="admin-sidebar-footer">
            <a href="logout.php" class="admin-nav-link" style="color: #f87171;">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div class="admin-page-heading">
                <i class="fa-solid fa-terminal text-cyan"></i>
                <span><?= isset($admin_title) ? htmlspecialchars($admin_title) : "Control Dashboard" ?></span>
            </div>

            <div class="admin-topbar-actions">
                <a href="../index.php" target="_blank" class="btn-site-preview">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live Website
                </a>
                <div class="admin-user-pill">
                    <div class="user-avatar-mini">
                        <?= strtoupper(substr($_SESSION['admin_user'] ?? 'A', 0, 1)) ?>
                    </div>
                    <span><?= htmlspecialchars($_SESSION['admin_user'] ?? 'Administrator') ?></span>
                </div>
            </div>
        </header>

        <main class="admin-content-area">
            <?php
            $flash = getFlash();
            if ($flash):
            ?>
                <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?>" style="margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-circle-info"></i>
                    <div><?= htmlspecialchars($flash['message']) ?></div>
                </div>
            <?php endif; ?>
