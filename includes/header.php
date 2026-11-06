<?php
// includes/header.php
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/db.php';
}
$site_name = getSetting('company_name', 'STRINGS Sports');
$site_slogan = getSetting('slogan', 'PLAY BEYOND LIMITS');
$page_title = isset($page_title) ? $page_title . " | " . $site_name : $site_name . " - " . $site_slogan;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="STRINGS Sports - Premium Tournament & Professional Badminton Shuttlecocks. Goose feather precision, ultra-durable flight stability, and aerodynamic engineering. Play Beyond Limits.">
    <meta name="keywords" content="badminton shuttlecock, strings sports, S40 shuttlecock, goose feather shuttlecock, tournament shuttlecock, speed 77, badminton equipment">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Rajdhani:wght@500;600;700&family=Space+Grotesk:wght@500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?= @filemtime(__DIR__ . '/../assets/css/style.css') ?: time() ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>
<body class="cyber-theme">
    <!-- Page Preloader with STRINGS Logo -->
    <div id="pageLoader" class="strings-preloader" aria-label="Loading STRINGS Sports">
        <div class="preloader-inner">
            <div class="preloader-emblem-wrap">
                <div class="preloader-ring-outer"></div>
                <div class="preloader-ring-inner"></div>
                <div class="preloader-glow-pulse"></div>
                <img src="assets/images/logo.png" alt="STRINGS Logo" class="preloader-logo-img">
            </div>
            <div class="preloader-brand-text">
                <span class="preloader-title">STRINGS</span>
                <span class="preloader-sub">PLAY BEYOND LIMITS</span>
            </div>
            <div class="preloader-bar-track">
                <div class="preloader-bar-fill"></div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            var closePreloader = function() {
                var loader = document.getElementById('pageLoader');
                if (!loader || loader.dataset.closed) return;
                loader.dataset.closed = '1';
                loader.classList.add('loader-hidden');
                loader.style.opacity = '0';
                loader.style.visibility = 'hidden';
                loader.style.pointerEvents = 'none';
                setTimeout(function() {
                    if (loader && loader.parentNode) {
                        loader.style.display = 'none';
                    }
                }, 600);
            };
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(closePreloader, 500);
            });
            window.addEventListener('load', function() {
                setTimeout(closePreloader, 450);
            });
            setTimeout(closePreloader, 1200);
        })();
    </script>

    <!-- Cyber string grid background canvas -->
    <canvas id="cyberStringsCanvas" class="background-canvas"></canvas>
