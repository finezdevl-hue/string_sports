<?php
// index.php - STRINGS SPORTS Home Page
require_once __DIR__ . '/config/db.php';
$page_title = "High Performance Badminton Shuttlecocks";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch featured products from database
$stmt = $pdo->query("SELECT * FROM products WHERE is_featured = 1 ORDER BY id ASC LIMIT 3");
$featuredProducts = $stmt->fetchAll();
if (empty($featuredProducts)) {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC LIMIT 3");
    $featuredProducts = $stmt->fetchAll();
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-content">
            <div class="hero-tag">
                <i class="fa-solid fa-atom"></i> AERODYNAMIC STRING FLIGHT DYNAMICS
            </div>
            
            <h1 class="hero-title">
                <span class="title-outline">DOMINATE</span><br>
                <span class="title-solid">THE COURT</span>
                <span class="hero-slogan-brush">Play Beyond Limits</span>
            </h1>

            <p class="hero-desc">
                Engineered with high-density Portuguese natural cork and laser-aligned Class-A goose feathers. STRINGS Sports delivers micro-balanced rotational stability and high smash resistance for tournament championships.
            </p>

            <div class="hero-actions">
                <a href="products.php" class="btn btn-cyan btn-lg btn-glow">
                    <i class="fa-solid fa-shuttlecock"></i> Explore S-Series
                </a>
                <a href="#aboutStrings" class="btn btn-outline-cyan btn-lg">
                    <i class="fa-solid fa-feather-pointed"></i> Product Features
                </a>
                <a href="contact.php?type=bulk_order" class="btn btn-white btn-lg">
                    <i class="fa-solid fa-boxes-stacked"></i> Wholesale Carton
                </a>
            </div>

            <!-- 4 Badges From Packaging Reference Artwork -->
            <div class="hero-pillars">
                <div class="pillar-item">
                    <i class="fa-solid fa-feather-pointed pillar-icon"></i>
                    <span class="pillar-title">Goose Feather</span>
                    <span class="pillar-sub">Selected Class-A</span>
                </div>
                <div class="pillar-item">
                    <i class="fa-solid fa-compass-drafting pillar-icon"></i>
                    <span class="pillar-title">Stable Flight</span>
                    <span class="pillar-sub">Laser Micro-Spin</span>
                </div>
                <div class="pillar-item">
                    <i class="fa-solid fa-shield-halved pillar-icon"></i>
                    <span class="pillar-title">Durable Core</span>
                    <span class="pillar-sub">Portuguese Cork</span>
                </div>
                <div class="pillar-item">
                    <i class="fa-solid fa-trophy pillar-icon"></i>
                    <span class="pillar-title">Tournament</span>
                    <span class="pillar-sub">BWF Match Grade</span>
                </div>
            </div>
        </div>

        <!-- Hero Visual Showcase -->
        <div class="hero-visual-card">
            <div class="visual-glow-ring"></div>
            
            <div class="visual-image-wrapper">
                <video src="assets/images/hero_video.mp4" poster="assets/images/s40.jpg" class="hero-product-img hero-product-video" autoplay loop muted playsinline>
                    Your browser does not support the video tag.
                </video>
            </div>

            <!-- Floating Highlights -->
            <div class="floating-badge badge-top-left">
                <div class="fb-icon"><i class="fa-solid fa-bolt"></i></div>
                <div class="fb-text">
                    <strong>STRINGS S40</strong>
                    <span>Distance 77 / Tournament</span>
                </div>
            </div>

            <div class="floating-badge badge-bottom-right">
                <div class="fb-icon"><i class="fa-solid fa-award"></i></div>
                <div class="fb-text">
                    <strong>12 PCS / TUBE</strong>
                    <span>Precision Goose Feather</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Strings & Product Features Showcase (Built For The Game / Precision In Every Shot) -->
<section class="strings-showcase-section" id="aboutStrings">
    <!-- Top Block: About Strings - Built For The Game -->
    <div class="about-strings-block">
        <div class="container about-strings-grid">
            <!-- Left Visual: Player Jump Smash -->
            <div class="about-player-visual">
                <img src="assets/images/about_strings_player.jpg" alt="STRINGS Badminton Player Jump Smash" class="about-player-img">
                <div class="about-player-fade"></div>
            </div>

            <!-- Right Content: About Strings Copy & 3 Pillars -->
            <div class="about-strings-content">
                <div class="eyebrow-label">
                    <span>ABOUT STRINGS</span>
                    <div class="eyebrow-line"></div>
                </div>

                <h2 class="about-strings-heading">
                    <span class="heading-white">BUILT FOR</span>
                    <span class="heading-blue">THE GAME</span>
                </h2>

                <p class="about-strings-desc">
                    STRINGS is a badminton brand dedicated to players who demand more. Our shuttlecocks are designed with premium materials and engineered for consistent flight, excellent control and longer durability &mdash; helping you play beyond limits in every rally.
                </p>

                <!-- 3 Pillars with Vertical Dividers -->
                <div class="about-values-row">
                    <div class="about-value-item">
                        <div class="about-value-icon">
                            <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.5 5.5C30.5 15 26.5 25 16 28.5C11.5 30 8.5 28.5 8.5 25C8.5 15.5 17.5 5.5 30.5 5.5Z" fill="#00a8ff"/>
                                <path d="M9 28.5C14.5 23 20.5 16.5 26 10" stroke="#030816" stroke-width="2" stroke-linecap="round"/>
                                <path d="M9.5 27.5L5.5 31.5" stroke="#00a8ff" stroke-width="2.4" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="about-value-label">
                            <span>PREMIUM</span>
                            <span>QUALITY</span>
                        </div>
                    </div>

                    <div class="about-value-divider"></div>

                    <div class="about-value-item">
                        <div class="about-value-icon">
                            <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5 22.5L10.5 32.5L16.5 29.2L18 32.5" fill="#00a8ff"/>
                                <path d="M22.5 22.5L25.5 32.5L19.5 29.2L18 32.5" fill="#00a8ff"/>
                                <circle cx="18" cy="14.5" r="9" stroke="#00a8ff" stroke-width="3" fill="#030816"/>
                                <path d="M18 9.8L19.45 12.75L22.7 13.22L20.35 15.51L20.9 18.75L18 17.22L15.1 18.75L15.65 15.51L13.3 13.22L16.55 12.75L18 9.8Z" fill="#00a8ff"/>
                            </svg>
                        </div>
                        <div class="about-value-label">
                            <span>TRUSTED</span>
                            <span>BY PLAYERS</span>
                        </div>
                    </div>

                    <div class="about-value-divider"></div>

                    <div class="about-value-item">
                        <div class="about-value-icon">
                            <svg viewBox="0 0 42 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="21" cy="11.5" r="4" fill="#00a8ff"/>
                                <circle cx="11.5" cy="13.5" r="3.2" fill="#00a8ff" fill-opacity="0.85"/>
                                <circle cx="30.5" cy="13.5" r="3.2" fill="#00a8ff" fill-opacity="0.85"/>
                                <path d="M13.5 27.5C13.5 23 16.8 19.8 21 19.8C25.2 19.8 28.5 23 28.5 27.5V29H13.5V27.5Z" fill="#00a8ff"/>
                                <path d="M5 27.5C5 24 7.5 21.2 11 21.2C12.3 21.2 13.5 21.6 14.4 22.3C12.7 23.8 11.8 25.7 11.8 28V29H5V27.5Z" fill="#00a8ff" fill-opacity="0.8"/>
                                <path d="M37 27.5C37 24 34.5 21.2 31 21.2C29.7 21.2 28.5 21.6 27.6 22.3C29.3 23.8 30.2 25.7 30.2 28V29H37V27.5Z" fill="#00a8ff" fill-opacity="0.8"/>
                            </svg>
                        </div>
                        <div class="about-value-label">
                            <span>FOR EVERY</span>
                            <span>LEVEL</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Horizontal Divider -->
    <div class="showcase-section-divider"></div>

    <!-- Bottom Block: Product Features - Precision In Every Shot -->
    <div class="product-features-block">
        <div class="container">
            <div class="features-header-center">
                <span class="eyebrow-text">PRODUCT FEATURES</span>
                <div class="eyebrow-line-center"></div>
                <h2 class="features-main-title">
                    PRECISION IN <span class="heading-blue">EVERY SHOT</span>
                </h2>
            </div>

            <!-- 4 Feature Cards Grid -->
            <div class="precision-features-grid">
                <div class="precision-feature-item">
                    <div class="pf-image-box">
                        <img src="assets/images/feat_goose_feather.jpg" alt="Goose Feather" class="pf-img">
                    </div>
                    <h3 class="pf-title">GOOSE FEATHER</h3>
                    <p class="pf-desc">Carefully selected goose feathers for superior flight and feel.</p>
                </div>

                <div class="precision-feature-item">
                    <div class="pf-image-box">
                        <img src="assets/images/feat_stable_flight.jpg" alt="Stable Flight" class="pf-img">
                    </div>
                    <h3 class="pf-title">STABLE FLIGHT</h3>
                    <p class="pf-desc">Consistent trajectory and excellent control in every game.</p>
                </div>

                <div class="precision-feature-item">
                    <div class="pf-image-box">
                        <img src="assets/images/feat_durable.jpg" alt="Durable" class="pf-img">
                    </div>
                    <h3 class="pf-title">DURABLE</h3>
                    <p class="pf-desc">Longer lasting performance.</p>
                </div>

                <div class="precision-feature-item">
                    <div class="pf-image-box">
                        <img src="assets/images/feat_tournament_grade.jpg" alt="Tournament Grade" class="pf-img">
                    </div>
                    <h3 class="pf-title">TOURNAMENT GRADE</h3>
                    <p class="pf-desc">Built for professional and competitive play.</p>
                </div>
            </div>

            <!-- Bottom Specifications Bar -->
            <div class="specs-bottom-bar">
                <div class="specs-bar-cell specs-bar-heading">
                    <span>SPECIFICATIONS</span>
                </div>

                <div class="specs-bar-cell">
                    <div class="specs-bar-icon">
                        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g transform="rotate(42 20 20)">
                                <path d="M15.5 29C15.5 31.5 17.5 33.5 20 33.5C22.5 33.5 24.5 31.5 24.5 29V26.5H15.5V29Z" fill="#ffffff"/>
                                <path d="M15.5 23.5L10 8.5L14.5 9.5L17.5 23.5H15.5Z" fill="#ffffff"/>
                                <path d="M17.8 23.5L15.5 7.5L20 6.5L20 23.5H17.8Z" fill="#ffffff"/>
                                <path d="M20.2 23.5L20 6.5L24.5 7.5L22.2 23.5H20.2Z" fill="#ffffff"/>
                                <path d="M22.5 23.5L25.5 9.5L30 8.5L24.5 23.5H22.5Z" fill="#ffffff"/>
                                <line x1="13" y1="16" x2="27" y2="16" stroke="#040b1a" stroke-width="1.4"/>
                                <line x1="14.5" y1="20" x2="25.5" y2="20" stroke="#040b1a" stroke-width="1.4"/>
                            </g>
                        </svg>
                    </div>
                    <span class="specs-bar-text">MODEL : S40</span>
                </div>

                <div class="specs-bar-cell">
                    <div class="specs-bar-icon">
                        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M33 7C23 7 14 13.5 10.5 23.5C9.5 26.5 9.5 29 9.5 29L13.5 25.5L11 29.5C15 29.5 19.5 28.5 23 26L19.5 24.5L25 24C29.5 20 33 13.5 33 7Z" fill="#ffffff"/>
                            <path d="M7 33L26 14" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M11 29L27 13" stroke="#040b1a" stroke-width="1.2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <span class="specs-bar-text">GOOSE FEATHER</span>
                </div>

                <div class="specs-bar-cell">
                    <div class="specs-bar-icon">
                        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L33 12.5L20 19L7 12.5L20 6Z" stroke="#ffffff" stroke-width="2" fill="#040b1a" stroke-linejoin="round"/>
                            <path d="M13.5 9.25L26.5 15.75" stroke="#ffffff" stroke-width="1.8"/>
                            <path d="M7 12.5L20 19V34L7 27.5V12.5Z" fill="#ffffff"/>
                            <path d="M33 12.5L20 19V34L33 27.5V12.5Z" fill="#ffffff"/>
                            <path d="M20 19V34" stroke="#040b1a" stroke-width="2.2"/>
                            <path d="M25 16.5V22.5L28.5 20.8V14.8L25 16.5Z" fill="#040b1a"/>
                        </svg>
                    </div>
                    <div class="specs-bar-text-stack">
                        <span>QUANTITY</span>
                        <span>12 PCS</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured S-Series Products -->
<section class="section section-dark">
    <div class="container">
        <div class="section-header">
            <div class="section-tag"><i class="fa-solid fa-shuttlecock"></i> PRECISION RANGE</div>
            <h2 class="section-title">FEATURED SHUTTLECOCKS</h2>
            <p class="section-subtitle">
                Engineered for tournaments, high-intensity training, and club leagues. Choose the model that suits your competition grade.
            </p>
        </div>

        <div class="products-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <div class="product-thumb-container">
                        <?php if (!empty($product['badge'])): ?>
                            <span class="product-badge-overlay"><?= htmlspecialchars($product['badge']) ?></span>
                        <?php endif; ?>
                        <span class="product-speed-tag"><i class="fa-solid fa-gauge-simple-high"></i> Speed <?= htmlspecialchars($product['speed']) ?></span>
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
                    </div>

                    <div class="product-body">
                        <span class="product-category"><?= htmlspecialchars($product['category']) ?></span>
                        <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="product-desc"><?= htmlspecialchars($product['description']) ?></p>

                        <div class="product-specs-chips">
                            <span class="spec-chip"><i class="fa-solid fa-feather"></i> <?= htmlspecialchars($product['feather_type']) ?></span>
                            <span class="spec-chip"><i class="fa-solid fa-circle-dot"></i> <?= htmlspecialchars($product['cork_base']) ?></span>
                            <span class="spec-chip"><i class="fa-solid fa-box"></i> <?= htmlspecialchars($product['quantity_per_tube']) ?> Pcs/Tube</span>
                        </div>

                        <div class="product-footer-bar">
                            <div class="product-card-actions">
                                <button type="button" class="btn btn-cyan btn-sm" data-inquire-product="<?= htmlspecialchars($product['name']) ?>">
                                    <i class="fa-solid fa-bolt"></i> Inquire
                                </button>
                                <a href="products.php" class="btn btn-outline-cyan btn-sm btn-icon-only" title="View Specifications">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 3.5rem;">
            <a href="products.php" class="btn btn-outline-cyan btn-lg">
                <i class="fa-solid fa-layer-group"></i> View All Models & Specifications
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
