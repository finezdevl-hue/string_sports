<?php
// contact.php - STRINGS SPORTS Contact & Distribution Inquiries
require_once __DIR__ . '/config/db.php';
$page_title = "Contact Us & Global Distribution";

$success_msg = '';
$error_msg = '';

// Handle direct POST if submitted directly to contact.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/mailapi/contact.php';
    exit;
}

// Retrieve flash notifications or query status
$flash = getFlash();
if ($flash) {
    if ($flash['type'] === 'success') {
        $success_msg = $flash['message'];
    } else {
        $error_msg = $flash['message'];
    }
} elseif (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $success_msg = "Thank you! Your inquiry has been received. Our sports distribution team will get back to you within 24 business hours.";
    } elseif ($_GET['status'] === 'error') {
        $error_msg = "Unable to process inquiry. Please check the required fields and try again.";
    }
}

$default_type = sanitize($_GET['type'] ?? 'general');
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Contact Header Banner -->
<section style="padding: 4.5rem 0 3rem; background: radial-gradient(ellipse at 50% 0%, rgba(0, 240, 255, 0.15) 0%, transparent 70%); border-bottom: 1px solid rgba(0, 240, 255, 0.15);">
    <div class="container text-center" style="text-align: center;">
        <span class="badge-live"><i class="fa-solid fa-satellite-dish"></i> GLOBAL CONNECT</span>
        <h1 style="font-size: 3.5rem; font-weight: 900; margin: 1rem 0 0.5rem; text-transform: uppercase;">
            GET IN TOUCH WITH <span class="text-cyan">STRINGS</span>
        </h1>
        <p style="max-width: 680px; margin: 0 auto; color: var(--text-muted); font-size: 1.1rem;">
            Whether you need tournament bulk supplies, club cartons, dealer partnership, or sample testing, our technical team is ready to assist.
        </p>
    </div>
</section>

<!-- Main Contact Section -->
<section class="section" style="padding-top: 4rem;">
    <div class="container">
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check" style="font-size: 1.35rem;"></i>
                <div>
                    <strong>Success!</strong> <?= $success_msg ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.35rem;"></i>
                <div>
                    <strong>Error!</strong> <?= $error_msg ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="contact-grid">
            <!-- Left Info Panel -->
            <div class="contact-info-panel">
                <div class="section-tag"><i class="fa-solid fa-building-circle-check"></i> HEADQUARTERS & DISTRIBUTION</div>
                <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.75rem;">STRINGS SPORTS HQ</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">
                    Supplying international badminton federations, academies, and authorized pro shops worldwide.
                </p>

                <div class="contact-card-list">
                    <div class="contact-card-item">
                        <div class="cci-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="cci-content">
                            <h4>Logistics & Distribution Center</h4>
                            <p><?= htmlspecialchars(getSetting('address', '700 Aero-Dynamics Way, Court Suite 10, Sports District')) ?></p>
                        </div>
                    </div>

                    <div class="contact-card-item">
                        <div class="cci-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div class="cci-content">
                            <h4>Direct Email Contact</h4>
                            <p><a href="mailto:<?= htmlspecialchars(getSetting('email', 'info@stringsports.com')) ?>"><?= htmlspecialchars(getSetting('email', 'info@stringsports.com')) ?></a></p>
                        </div>
                    </div>

                    <div class="contact-card-item">
                        <div class="cci-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="cci-content">
                            <h4>Customer & Wholesale Hotline</h4>
                            <p><a href="tel:<?= htmlspecialchars(getSetting('phone', '+1 (800) 584-7874')) ?>"><?= htmlspecialchars(getSetting('phone', '+1 (800) 584-7874')) ?></a></p>
                            <p style="font-size: 0.785rem; color: var(--text-dim);">Mon - Fri: 8:00 AM - 6:00 PM EST</p>
                        </div>
                    </div>

                    <div class="contact-card-item">
                        <div class="cci-icon"><i class="fa-solid fa-globe"></i></div>
                        <div class="cci-content">
                            <h4>Official Domain</h4>
                            <p><?= htmlspecialchars(getSetting('website', 'www.stringsports.com')) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Wholesale Tier Box -->
                <div style="background: rgba(0, 240, 255, 0.06); border: 1px solid rgba(0, 240, 255, 0.25); border-radius: var(--radius-md); padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--cyan-glow); font-family: var(--font-tech); font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        <i class="fa-solid fa-truck-fast"></i> BULK ORDER DISCOUNTS
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.6;">
                        • Orders <strong>10+ Tubes</strong>: 10% Club Discount<br>
                        • Master Carton (<strong>50 Tubes</strong>): 25% Wholesale Margin<br>
                        • Annual Academy Contract: Custom Stamped Tube Lids
                    </p>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="form-card">
                <div class="section-tag"><i class="fa-solid fa-envelope-open-text"></i> SUBMIT INQUIRY</div>
                <h2 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 0.5rem;">SEND US A MESSAGE</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem;">
                    Please provide your requirements below and our equipment specialist will connect with you.
                </p>

                <div id="alert-msg"></div>

                <form action="mailapi/contact.php" method="POST" id="contact-form">
                    <div class="form-row">
                        <div class="form-group col-half">
                            <label for="contact_name">Full Name or Club Organization *</label>
                            <input type="text" id="contact_name" name="name" required class="form-input" placeholder="e.g. Coach Marcus / Zenith Badminton Club">
                        </div>
                        <div class="form-group col-half">
                            <label for="contact_email">Email Address *</label>
                            <input type="email" id="contact_email" name="email" required class="form-input" placeholder="coach@club.com">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-half">
                            <label for="contact_phone">Phone / WhatsApp Number</label>
                            <input type="tel" id="contact_phone" name="phone" class="form-input" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="form-group col-half">
                            <label for="inquiry_type">Inquiry Nature</label>
                            <select id="inquiry_type" name="inquiry_type" class="form-input">
                                <option value="general" <?= ($default_type == 'general') ? 'selected' : '' ?>>General Inquiry</option>
                                <option value="bulk_order" <?= ($default_type == 'bulk_order') ? 'selected' : '' ?>>Bulk / Club Order (10 - 50+ Tubes)</option>
                                <option value="dealership" <?= ($default_type == 'dealership') ? 'selected' : '' ?>>Authorized Dealership / Retailer</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact_subject">Subject</label>
                        <input type="text" id="contact_subject" name="subject" class="form-input" placeholder="e.g. S40 Speed 77 Carton Pricing for Autumn League">
                    </div>

                    <div class="form-group">
                        <label for="contact_message">Your Message / Order Details *</label>
                        <textarea id="contact_message" name="message" rows="5" required class="form-input" placeholder="Detail your quantity, preferred speed (76 / 77 / 78), target shipping destination, or any questions..."></textarea>
                    </div>

                    <button type="submit" id="submit_contact_btn" class="btn btn-cyan btn-lg btn-glow btn-full">
                        <i class="fa-solid fa-paper-plane"></i> <span>Transmit Inquiry Message</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Badminton FAQ Section -->
<section class="section section-dark">
    <div class="container" style="max-width: 900px;">
        <div class="section-header">
            <div class="section-tag"><i class="fa-solid fa-circle-question"></i> FREQUENTLY ASKED QUESTIONS</div>
            <h2 class="section-title">SHUTTLECOCK TECHNICAL FAQ</h2>
            <p class="section-subtitle">
                Expert guidance on feather types, speed selection, and humidity preservation for badminton equipment managers.
            </p>
        </div>

        <div class="faq-accordion">
            <div class="faq-item open">
                <div class="faq-header">
                    <span class="faq-title">1. How do I select the right speed (76, 77, or 78)?</span>
                    <i class="fa-solid fa-chevron-down faq-toggle-icon"></i>
                </div>
                <div class="faq-content">
                    Shuttlecock speed is governed by ambient temperature and altitude. In cold arenas (below 18°C / 64°F), air density is thicker, so a faster shuttlecock like <strong>Speed 78</strong> is recommended. For standard indoor courts between 18°C and 24°C, <strong>Speed 77</strong> is the international tournament benchmark. In hot climates or high altitudes (above 25°C), <strong>Speed 76</strong> travels further and maintains proper court boundaries.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-header">
                    <span class="faq-title">2. What is the difference between Goose Feather and Duck Feather?</span>
                    <i class="fa-solid fa-chevron-down faq-toggle-icon"></i>
                </div>
                <div class="faq-content">
                    STRINGS S40 and S50 use 100% selected <strong>Class-A Goose Feathers</strong>. Goose feathers possess thicker, stronger natural calamus shafts and oil-rich barbs, yielding 40% higher smash durability and more stable rotation than duck feathers, which are more brittle and prone to stem fractures during hard smashes.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-header">
                    <span class="faq-title">3. What is the correct way to extract shuttlecocks from the tube?</span>
                    <i class="fa-solid fa-chevron-down faq-toggle-icon"></i>
                </div>
                <div class="faq-content">
                    As marked on the STRINGS packaging with <strong>PUSH IN ↓ (top) and PULL OUT ↓ (bottom)</strong>, always push the shuttlecock from the top cap towards the bottom opening. Pulling shuttlecocks out backwards against their natural feather angle bends and frays the delicate feather tips, compromising aerodynamic stability.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-header">
                    <span class="faq-title">4. How do I keep goose feather shuttlecocks durable in dry seasons?</span>
                    <i class="fa-solid fa-chevron-down faq-toggle-icon"></i>
                </div>
                <div class="faq-content">
                    Natural feathers lose moisture in dry or air-conditioned environments, making stems brittle. We recommend using a shuttlecock humidifier or gentle steaming 24-48 hours before match play. Store tubes standing upright with both caps securely fastened in a cool, shaded environment.
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
