<?php
// admin/products.php - Manage Shuttlecock Catalog
$admin_title = "Product Inventory";
require_once __DIR__ . '/includes/admin_header.php';

// Handle Toggle Featured
if (isset($_GET['action']) && $_GET['action'] === 'toggle_featured' && isset($_GET['id'])) {
    $pId = intval($_GET['id']);
    $current = $pdo->prepare("SELECT is_featured FROM products WHERE id = ?");
    $current->execute([$pId]);
    $feat = $current->fetchColumn();
    $newFeat = ($feat == 1) ? 0 : 1;

    $upd = $pdo->prepare("UPDATE products SET is_featured = ? WHERE id = ?");
    $upd->execute([$newFeat, $pId]);
    setFlash('success', "Featured status toggled successfully.");
    header("Location: products.php");
    exit;
}

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.65rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem;">Shuttlecock Catalog Management</h2>
        <p style="color: var(--adm-muted); font-size: 0.9rem;">Add, modify specifications, change pricing, and configure model badges.</p>
    </div>
    <a href="product_add.php" class="btn btn-cyan btn-glow">
        <i class="fa-solid fa-plus"></i> Add New Shuttlecock
    </a>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fa-solid fa-boxes-packing text-cyan"></i>
            <span>All Products (<?= count($products) ?>)</span>
        </div>
    </div>
    <div class="admin-card-body" style="padding: 0;">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product & Details</th>
                        <th>Model</th>
                        <th>Category</th>
                        <th>Speed</th>
                        <th>Tube Qty</th>
                        <th>Price</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: var(--adm-muted);">
                                No products in catalog yet. <a href="product_add.php" style="color: var(--adm-cyan);">Add your first product</a>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td style="display: flex; align-items: center; gap: 1rem;">
                                    <img src="../<?= htmlspecialchars($p['image']) ?>" alt="Thumb" class="table-thumb">
                                    <div>
                                        <strong style="color: #fff; font-size: 0.95rem;"><?= htmlspecialchars($p['name']) ?></strong>
                                        <div style="font-size: 0.8rem; color: var(--adm-muted);">
                                            <?= htmlspecialchars($p['feather_type']) ?> • <?= htmlspecialchars($p['cork_base']) ?>
                                        </div>
                                        <?php if (!empty($p['badge'])): ?>
                                            <span class="badge-status badge-new" style="margin-top: 0.25rem; font-size: 0.685rem;">
                                                <?= htmlspecialchars($p['badge']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-cyan" style="font-family: var(--font-tech); font-size: 1.1rem;">
                                        <?= htmlspecialchars($p['model_code']) ?>
                                    </strong>
                                </td>
                                <td><?= htmlspecialchars($p['category']) ?></td>
                                <td>
                                    <span style="font-family: var(--font-tech); font-weight: 700; color: #fff;">
                                        Speed <?= htmlspecialchars($p['speed']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($p['quantity_per_tube']) ?> pcs/tube</td>
                                <td>
                                    <strong style="color: var(--adm-cyan); font-size: 1.05rem;">
                                        $<?= number_format($p['price'], 2) ?>
                                    </strong>
                                </td>
                                <td>
                                    <a href="products.php?action=toggle_featured&id=<?= $p['id'] ?>" title="Click to toggle featured on homepage" style="font-size: 1.15rem; color: <?= ($p['is_featured'] == 1) ? 'var(--adm-cyan)' : 'var(--adm-muted)' ?>;">
                                        <i class="fa-<?= ($p['is_featured'] == 1) ? 'solid' : 'regular' ?> fa-star"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="product_edit.php?id=<?= $p['id'] ?>" class="btn-icon btn-icon-edit" title="Edit Product">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="product_delete.php?id=<?= $p['id'] ?>" class="btn-icon btn-icon-delete btn-confirm-delete" 
                                           data-confirm-message="Are you sure you want to delete <?= htmlspecialchars($p['name']) ?>?" title="Delete Product">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
