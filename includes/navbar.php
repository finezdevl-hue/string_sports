<?php
// includes/navbar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="main-header" id="navbar">
    <div class="container navbar-container">
        <!-- Logo -->
        <a href="index.php" class="brand-logo" title="STRINGS Sports - Home">
            <div class="logo-wrapper">
                <img src="assets/images/logo.png" alt="STRINGS Sports Logo" class="brand-img">
            </div>
            <div class="brand-text-block">
                <span class="brand-name">STRINGS</span>
                <span class="brand-sub">SPORTS</span>
                <span class="brand-tagline">PLAY BEYOND LIMITS</span>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="desktop-nav">
            <ul class="nav-links">
                <li class="nav-item <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                    <a href="index.php" class="nav-link">
                        <span class="nav-dot"></span>Home
                    </a>
                </li>
                <li class="nav-item <?= ($current_page == 'products.php') ? 'active' : '' ?>">
                    <a href="products.php" class="nav-link">
                        <span class="nav-dot"></span>Products
                    </a>
                </li>
                <li class="nav-item <?= ($current_page == 'contact.php') ? 'active' : '' ?>">
                    <a href="contact.php" class="nav-link">
                        <span class="nav-dot"></span>Contact
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Right Action Buttons -->
        <div class="header-actions">
            <a href="contact.php" class="btn btn-cyan btn-sm btn-glow hide-mobile">
                <i class="fa-solid fa-paper-plane"></i> Bulk Inquiry
            </a>
            <a href="admin/login.php" class="btn-admin-link hide-mobile" title="Admin Control Panel">
                <i class="fa-solid fa-shield-halved"></i>
            </a>

            <!-- Mobile Hamburger Toggle -->
            <button type="button" class="mobile-menu-toggle" id="mobileMenuBtn" aria-label="Toggle Navigation Menu" aria-expanded="false" aria-controls="mobileDrawer">
                <span class="hamburger-bar"></span>
                <span class="hamburger-bar"></span>
                <span class="hamburger-bar"></span>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Off-Canvas Drawer (Outside header to avoid backdrop-filter fixed-position containment) -->
<div class="mobile-drawer" id="mobileDrawer" aria-hidden="true">
    <div class="mobile-drawer-header">
        <a href="index.php" class="brand-logo" style="gap: 0.65rem;">
            <div class="logo-wrapper" style="height: 40px;">
                <img src="assets/images/logo.png" alt="STRINGS Sports Logo" class="brand-img">
            </div>
            <div class="brand-text-block">
                <span class="brand-name">STRINGS</span>
                <span class="brand-tagline">PLAY BEYOND LIMITS</span>
            </div>
        </a>
        <button type="button" class="drawer-close-btn" id="drawerCloseBtn" aria-label="Close Navigation Menu">&times;</button>
    </div>
    <div class="mobile-drawer-body">
        <ul class="mobile-nav-links">
            <li>
                <a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i> Home
                </a>
            </li>
            <li>
                <a href="products.php" class="<?= ($current_page == 'products.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-feather-pointed"></i> Products
                </a>
            </li>
            <li>
                <a href="contact.php" class="<?= ($current_page == 'contact.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-headset"></i> Contact
                </a>
            </li>
            <li>
                <a href="admin/login.php" class="mobile-admin-item">
                    <i class="fa-solid fa-shield-halved"></i> Admin Panel
                </a>
            </li>
        </ul>

        <div class="mobile-drawer-footer">
            <div class="drawer-contact-snippet">
                <p><i class="fa-regular fa-envelope"></i> <?= htmlspecialchars(getSetting('email', 'info@stringsports.com')) ?></p>
                <p><i class="fa-solid fa-phone"></i> <?= htmlspecialchars(getSetting('phone', '+1 (800) 584-7874')) ?></p>
            </div>
            <a href="contact.php" class="btn btn-cyan btn-full">
                <i class="fa-solid fa-paper-plane"></i> Request Catalog & Samples
            </a>
        </div>
    </div>
</div>
<div class="drawer-backdrop" id="drawerBackdrop"></div>
