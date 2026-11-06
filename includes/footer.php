<?php
// includes/footer.php
?>
<footer class="main-footer">
    <div class="footer-glow-separator"></div>
    <div class="container footer-content">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-col brand-col">
                <a href="index.php" class="footer-logo">
                    <img src="assets/images/logo.png" alt="STRINGS Sports" class="footer-logo-img">
                    <div>
                        <span class="footer-logo-text">STRINGS</span>
                        <span class="footer-logo-sub">SPORTS</span>
                    </div>
                </a>
                <p class="footer-tagline-quote">"PLAY BEYOND LIMITS"</p>
                <p class="footer-desc">
                    Engineered for relentless precision, flight stability, and championship durability. Designed with selected high-grade goose feathers and natural Portuguese cork cores.
                </p>
                <div class="footer-social-links">
                    <a href="#" class="social-icon" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="social-icon" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social-icon" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" class="social-icon" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div class="footer-col">
                <h4 class="footer-heading"><i class="fa-solid fa-compass"></i> Navigation</h4>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="fa-solid fa-chevron-right"></i> Home</a></li>
                    <li><a href="products.php"><i class="fa-solid fa-chevron-right"></i> Shuttlecock Products</a></li>
                    <li><a href="products.php?category=Tournament"><i class="fa-solid fa-chevron-right"></i> Tournament S40</a></li>
                    <li><a href="products.php?category=Championship"><i class="fa-solid fa-chevron-right"></i> Championship S50</a></li>
                    <li><a href="products.php?category=Club"><i class="fa-solid fa-chevron-right"></i> Club Training S30</a></li>
                    <li><a href="contact.php"><i class="fa-solid fa-chevron-right"></i> Contact & Support</a></li>
                </ul>
            </div>

            <!-- Technical Standards -->
            <div class="footer-col">
                <h4 class="footer-heading"><i class="fa-solid fa-award"></i> Flight Standards</h4>
                <div class="tech-spec-badges">
                    <div class="mini-spec-badge">
                        <i class="fa-solid fa-feather-pointed"></i>
                        <span>Class-A Goose Feathers</span>
                    </div>
                    <div class="mini-spec-badge">
                        <i class="fa-solid fa-circle-nodes"></i>
                        <span>Laser Trajectory Spin</span>
                    </div>
                    <div class="mini-spec-badge">
                        <i class="fa-solid fa-shield-virus"></i>
                        <span>3-Layer Portuguese Cork</span>
                    </div>
                    <div class="mini-spec-badge">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span>Speeds 76 / 77 / 78</span>
                    </div>
                </div>
                <div class="tube-push-tip">
                    <span class="tip-badge"><i class="fa-solid fa-circle-info"></i> Pro Tip</span>
                    <p>Always push shuttlecocks out from top to bottom (PUSH IN ↓ / PULL OUT ↓) to safeguard feather curvature integrity.</p>
                </div>
            </div>

            <!-- Contact & Wholesale Column -->
            <div class="footer-col">
                <h4 class="footer-heading"><i class="fa-solid fa-satellite-dish"></i> Contact & Hub</h4>
                <ul class="footer-contact-list">
                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?= htmlspecialchars(getSetting('address', '700 Aero-Dynamics Way, Court Suite 10, Sports District')) ?></span>
                    </li>
                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        <span><a href="mailto:<?= htmlspecialchars(getSetting('email', 'info@stringsports.com')) ?>"><?= htmlspecialchars(getSetting('email', 'info@stringsports.com')) ?></a></span>
                    </li>
                    <li>
                        <i class="fa-solid fa-phone"></i>
                        <span><a href="tel:<?= htmlspecialchars(getSetting('phone', '+1 (800) 584-7874')) ?>"><?= htmlspecialchars(getSetting('phone', '+1 (800) 584-7874')) ?></a></span>
                    </li>
                    <li>
                        <i class="fa-solid fa-globe"></i>
                        <span><?= htmlspecialchars(getSetting('website', 'www.stringsports.com')) ?></span>
                    </li>
                </ul>

                <a href="contact.php?type=bulk_order" class="btn btn-outline-cyan btn-sm btn-full mt-3">
                    <i class="fa-solid fa-boxes-stacked"></i> Wholesale Carton Inquiry
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-flex">
                <p class="copyright-text">
                    &copy; <?= date('Y') ?> <strong class="text-cyan">STRINGS SPORTS</strong>. All Rights Reserved. Engineered for Elite Performance.
                </p>
                <div class="footer-legal-links">
                    <a href="contact.php">Distributor Terms</a>
                    <span class="dot">•</span>
                    <a href="contact.php">Quality Assurance</a>
                    <span class="dot">•</span>
                    <a href="admin/login.php" class="admin-link"><i class="fa-solid fa-lock"></i> Staff Login</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Quick Order / Inquiry Modal -->
<div class="modal-overlay" id="quickInquiryModal">
    <div class="modal-card">
        <button class="modal-close-btn" id="closeInquiryModal">&times;</button>
        <div class="modal-header">
            <div class="modal-tag"><i class="fa-solid fa-bolt"></i> FAST INQUIRY</div>
            <h3 class="modal-title" id="modalProductTitle">Order / Inquire for STRINGS S40</h3>
            <p class="modal-sub">Direct supply inquiry for academies, pro shops, tournament organizers, and players.</p>
        </div>
        <div id="modal-alert-msg"></div>
        <form action="mailapi/contact.php" method="POST" class="modal-form" id="quickInquiryForm">
            <input type="hidden" name="inquiry_type" value="bulk_order">
            <input type="hidden" name="subject" id="modalSubjectInput" value="Inquiry for STRINGS Shuttlecocks">

            <div class="form-row">
                <div class="form-group col-half">
                    <label for="modal_name">Your Name / Club Name *</label>
                    <input type="text" id="modal_name" name="name" required placeholder="e.g. Victor Lin / Metro BC" class="form-input">
                </div>
                <div class="form-group col-half">
                    <label for="modal_email">Email Address *</label>
                    <input type="email" id="modal_email" name="email" required placeholder="name@domain.com" class="form-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-half">
                    <label for="modal_phone">Phone / WhatsApp</label>
                    <input type="tel" id="modal_phone" name="phone" placeholder="+1 (555) 000-0000" class="form-input">
                </div>
                <div class="form-group col-half">
                    <label for="modal_quantity">Estimated Tubes / Cartons</label>
                    <select id="modal_quantity" name="quantity_tier" class="form-input">
                        <option value="Sample 1-3 Tubes">Sample Tube (1-3 Tubes)</option>
                        <option value="Club Pack (10-25 Tubes)">Club Pack (10 - 25 Tubes)</option>
                        <option value="Carton (50 Tubes / 600 pcs)">1 Master Carton (50 Tubes)</option>
                        <option value="Wholesale (3+ Cartons)">Wholesale Distributor (3+ Cartons)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="modal_message">Speed Requirement & Notes</label>
                <textarea id="modal_message" name="message" rows="3" class="form-input" placeholder="Preferred speed (76 / 77 / 78), delivery city, or any questions..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="submit" name="submit_inquiry" id="modalSubmitBtn" class="btn btn-cyan btn-full btn-glow">
                    <i class="fa-solid fa-paper-plane"></i> <span>Submit Inquiry Request</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scroll to top button -->
<button class="back-to-top" id="backToTop" aria-label="Scroll back to top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<!-- Main JS -->
<script src="assets/js/main.js?v=<?= @filemtime(__DIR__ . '/../assets/js/main.js') ?: time() ?>"></script>
</body>
</html>
