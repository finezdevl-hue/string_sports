<?php
// products.php - STRINGS SPORTS Products Catalog
require_once __DIR__ . '/config/db.php';
$page_title = "Shuttlecock Products & Specifications";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch all products from MySQL
$stmt = $pdo->query("SELECT * FROM products ORDER BY is_featured DESC, id ASC");
$products = $stmt->fetchAll();
?>

<!-- Products Header Banner -->
<section style="padding: 4.5rem 0 3rem; background: radial-gradient(ellipse at 50% 0%, rgba(0, 240, 255, 0.15) 0%, transparent 70%); border-bottom: 1px solid rgba(0, 240, 255, 0.15);">
    <div class="container text-center" style="text-align: center;">
        <span class="badge-live"><i class="fa-solid fa-shuttlecock"></i> PRECISION FLIGHT SERIES</span>
        <h1 style="font-size: 3.5rem; font-weight: 900; margin: 1rem 0 0.5rem; text-transform: uppercase;">
            STRINGS <span class="text-cyan">S-SERIES</span> COLLECTION
        </h1>
        <p style="max-width: 680px; margin: 0 auto; color: var(--text-muted); font-size: 1.1rem;">
            Designed for badminton athletes demanding consistent trajectory, high-durability goose feathers, and solid Portuguese cork feedback on every smash.
        </p>
    </div>
</section>

<!-- Products Filter & Grid Section -->
<section class="section" style="padding-top: 3.5rem;">
    <div class="container">
        <!-- Live Search Bar -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 2.5rem;">
            <div class="search-input-wrap" style="width: 100%; max-width: 340px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="productSearchInput" placeholder="Search S40, S50, speed, goose...">
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid" id="productsCatalogGrid">
            <?php foreach ($products as $product): ?>
                <div class="product-card catalog-product-card" 
                     data-category="<?= htmlspecialchars($product['category']) ?>" 
                     data-speed="<?= htmlspecialchars($product['speed']) ?>"
                     data-title="<?= htmlspecialchars($product['name']) ?>"
                     data-model="<?= htmlspecialchars($product['model_code']) ?>">
                    
                    <div class="product-thumb-container">
                        <?php if (!empty($product['badge'])): ?>
                            <span class="product-badge-overlay"><?= htmlspecialchars($product['badge']) ?></span>
                        <?php endif; ?>
                        <span class="product-speed-tag"><i class="fa-solid fa-gauge-high"></i> Speed <?= htmlspecialchars($product['speed']) ?></span>
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
                    </div>

                    <div class="product-body">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <span class="product-category"><?= htmlspecialchars($product['category']) ?></span>
                            <span style="font-family: var(--font-tech); font-weight: 700; color: var(--text-white); font-size: 0.85rem;">
                                MODEL: <span class="text-cyan"><?= htmlspecialchars($product['model_code']) ?></span>
                            </span>
                        </div>

                        <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="product-desc"><?= htmlspecialchars($product['description']) ?></p>

                        <!-- Ratings Bars -->
                        <div style="margin: 0.75rem 0 1.25rem; font-size: 0.8rem; display: flex; flex-direction: column; gap: 0.4rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: var(--text-muted);"><i class="fa-solid fa-shield"></i> Durability:</span>
                                <span style="color: var(--cyan-glow);">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-solid fa-star<?= ($i <= $product['durability_rating']) ? '' : '-half-stroke' ?>" style="font-size: 0.75rem;"></i>
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: var(--text-muted);"><i class="fa-solid fa-wind"></i> Flight Stability:</span>
                                <span style="color: var(--cyan-glow);">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-solid fa-star" style="font-size: 0.75rem;"></i>
                                    <?php endfor; ?>
                                </span>
                            </div>
                        </div>

                        <div class="product-specs-chips">
                            <span class="spec-chip"><i class="fa-solid fa-feather"></i> <?= htmlspecialchars($product['feather_type']) ?></span>
                            <span class="spec-chip"><i class="fa-solid fa-circle-dot"></i> <?= htmlspecialchars($product['cork_base']) ?></span>
                            <span class="spec-chip"><i class="fa-solid fa-box"></i> <?= htmlspecialchars($product['quantity_per_tube']) ?> Pcs/Tube</span>
                        </div>

                        <div class="product-footer-bar">
                            <div class="product-card-actions">
                                <button type="button" class="btn btn-cyan btn-sm btn-glow" data-inquire-product="<?= htmlspecialchars($product['name']) ?>">
                                    <i class="fa-solid fa-paper-plane"></i> Inquire
                                </button>
                                <button type="button" class="btn btn-outline-cyan btn-sm open-product-detail" 
                                        data-product='<?= json_encode($product, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'
                                        title="View Detailed Technical Specifications">
                                    <i class="fa-solid fa-eye"></i> Specs
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Found Notice -->
        <div id="noProductsNotice" style="display: none; text-align: center; padding: 4rem 1rem; background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px dashed rgba(0, 240, 255, 0.2); margin-top: 2rem;">
            <i class="fa-solid fa-shuttlecock" style="font-size: 3rem; color: var(--cyan-primary); margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">No shuttlecocks found matching criteria</h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Try adjusting your search query.</p>
            <button type="button" class="btn btn-outline-cyan" onclick="const s = document.getElementById('productSearchInput'); if (s) { s.value=''; s.dispatchEvent(new Event('input')); }">
                Reset Search
            </button>
        </div>
    </div>
</section>

<!-- Technical Comparison & Tube Push/Pull Guide -->
<section class="section section-dark">
    <div class="container">
        <div class="section-header">
            <div class="section-tag"><i class="fa-solid fa-circle-info"></i> SPECIFICATION COMPARISON</div>
            <h2 class="section-title">THE STRINGS DIFFERENCE</h2>
            <p class="section-subtitle">
                Compare feather types, flight recovery speed, and cork composition across our lineup.
            </p>
        </div>

        <div style="overflow-x: auto; background: var(--bg-surface); border: 1px solid rgba(0, 240, 255, 0.2); border-radius: var(--radius-lg); padding: 1.5rem;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                <thead>
                    <tr style="border-bottom: 2px solid rgba(0, 240, 255, 0.3); color: var(--cyan-glow); font-family: var(--font-tech); font-size: 1.1rem;">
                        <th style="padding: 1rem;">Specification</th>
                        <th style="padding: 1rem;">STRINGS S50 Elite</th>
                        <th style="padding: 1rem;">STRINGS S40 Tournament (Flagship)</th>
                        <th style="padding: 1rem;">STRINGS S30 Club Pro</th>
                    </tr>
                </thead>
                <tbody style="color: var(--text-light);">
                    <tr style="border-bottom: 1px solid rgba(0, 240, 255, 0.1);">
                        <td style="padding: 1rem; font-weight: 700; color: var(--text-white);"><i class="fa-solid fa-feather text-cyan"></i> Feather Grade</td>
                        <td style="padding: 1rem;">Top-Tier Straight Goose (Class A+)</td>
                        <td style="padding: 1rem;">Selected Premium Goose (Class A)</td>
                        <td style="padding: 1rem;">High-Impact Goose Feather</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(0, 240, 255, 0.1);">
                        <td style="padding: 1rem; font-weight: 700; color: var(--text-white);"><i class="fa-solid fa-circle text-cyan"></i> Cork Core</td>
                        <td style="padding: 1rem;">Dual-Layer Solid Portuguese Cork</td>
                        <td style="padding: 1rem;">Solid Natural Portuguese Cork</td>
                        <td style="padding: 1rem;">Composite Natural Cork Core</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(0, 240, 255, 0.1);">
                        <td style="padding: 1rem; font-weight: 700; color: var(--text-white);"><i class="fa-solid fa-gauge text-cyan"></i> Available Speeds</td>
                        <td style="padding: 1rem;">76 / 77</td>
                        <td style="padding: 1rem;">76 / 77 / 78</td>
                        <td style="padding: 1rem;">76 / 77</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(0, 240, 255, 0.1);">
                        <td style="padding: 1rem; font-weight: 700; color: var(--text-white);"><i class="fa-solid fa-clock text-cyan"></i> Smash Durability</td>
                        <td style="padding: 1rem;">2.5 - 3 Matches / Shuttle</td>
                        <td style="padding: 1rem;">2 - 2.5 Matches / Shuttle</td>
                        <td style="padding: 1rem;">1.5 - 2 Matches / Shuttle</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; font-weight: 700; color: var(--text-white);"><i class="fa-solid fa-box text-cyan"></i> Packaging Tube</td>
                        <td style="padding: 1rem;">12 Pcs / Cyber Foil Tube</td>
                        <td style="padding: 1rem;">12 Pcs / Push-In Pull-Out Tube</td>
                        <td style="padding: 1rem;">12 Pcs / Matte Pro Tube</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Product Detail Specs Modal -->
<div class="modal-overlay" id="productDetailModal">
    <div class="modal-card" style="max-width: 720px;">
        <button class="modal-close-btn" id="closeDetailModal">&times;</button>
        <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
            <div style="width: 200px; height: 250px; background: radial-gradient(circle at center, #0a1b40 0%, #040816 100%); border-radius: var(--radius-md); border: 1px solid rgba(0,240,255,0.3); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                <img id="detailModalImg" src="" alt="Product" style="max-height: 90%; max-width: 90%; object-fit: contain;">
            </div>
            <div style="flex: 1; min-width: 260px;">
                <span class="badge-live" id="detailModalCategory">TOURNAMENT GRADE</span>
                <h3 style="font-size: 1.85rem; margin: 0.5rem 0 0.35rem;" id="detailModalTitle">STRINGS S40</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin: 0.75rem 0 1.25rem;" id="detailModalDesc"></p>

                <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem; margin-bottom: 1.5rem; border-top: 1px solid rgba(0,240,255,0.15); padding-top: 0.75rem;">
                    <div><strong class="text-white">Feather Type:</strong> <span id="detailModalFeather" style="color: var(--text-light);">Goose Feather</span></div>
                    <div><strong class="text-white">Base Composition:</strong> <span id="detailModalCork" style="color: var(--text-light);">Portuguese Cork</span></div>
                    <div><strong class="text-white">Speeds Available:</strong> <span id="detailModalSpeed" class="text-cyan">76, 77, 78</span></div>
                    <div><strong class="text-white">Packaging:</strong> <span id="detailModalQuantity" style="color: var(--text-light);">12 pcs / tube</span></div>
                </div>

                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" class="btn btn-cyan btn-full btn-glow" id="detailModalInquireBtn">
                        <i class="fa-solid fa-paper-plane"></i> Instant Order Inquiry
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const detailModal = document.getElementById('productDetailModal');
    const closeDetailBtn = document.getElementById('closeDetailModal');
    const detailInquireBtn = document.getElementById('detailModalInquireBtn');

    let currentSelectedProduct = null;

    document.querySelectorAll('.open-product-detail').forEach(btn => {
        btn.addEventListener('click', () => {
            try {
                const prod = JSON.parse(btn.dataset.product);
                currentSelectedProduct = prod;

                document.getElementById('detailModalImg').src = prod.image;
                document.getElementById('detailModalTitle').textContent = prod.name;
                document.getElementById('detailModalCategory').textContent = prod.category.toUpperCase();
                document.getElementById('detailModalDesc').textContent = prod.description;
                document.getElementById('detailModalFeather').textContent = prod.feather_type;
                document.getElementById('detailModalCork').textContent = prod.cork_base;
                document.getElementById('detailModalSpeed').textContent = 'Speed ' + prod.speed;
                document.getElementById('detailModalQuantity').textContent = prod.quantity_per_tube + ' Shuttlecocks / Tube';

                detailModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            } catch(e) {
                console.error("Error parsing product details:", e);
            }
        });
    });

    if (closeDetailBtn) {
        closeDetailBtn.addEventListener('click', () => {
            detailModal.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    if (detailModal) {
        detailModal.addEventListener('click', (e) => {
            if (e.target === detailModal) {
                detailModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    if (detailInquireBtn) {
        detailInquireBtn.addEventListener('click', () => {
            detailModal.classList.remove('active');
            document.body.style.overflow = '';
            if (currentSelectedProduct) {
                window.openInquiryModal(currentSelectedProduct.name);
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
