<?php
// admin/index.php - STRINGS SPORTS Admin Dashboard
$admin_title = "Overview Dashboard";
require_once __DIR__ . '/includes/admin_header.php';

// Quick Status Update for Inquiries directly from dashboard
if (isset($_GET['action']) && $_GET['action'] === 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $inqId = intval($_GET['id']);
    $newStatus = sanitize($_GET['status']);
    if (in_array($newStatus, ['new', 'in_progress', 'replied'])) {
        $stmt = $pdo->prepare("UPDATE inquiries SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $inqId]);
        setFlash('success', "Inquiry status updated to " . strtoupper($newStatus));
        header("Location: index.php");
        exit;
    }
}

// Fetch KPI metrics
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$newInquiries = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();
$bulkOrders = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE inquiry_type = 'bulk_order'")->fetchColumn();
$totalInquiries = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();

// Fetch Recent Inquiries (last 5)
$recentInquiries = $pdo->query("SELECT * FROM inquiries ORDER BY id DESC LIMIT 5")->fetchAll();

// Fetch Products overview (last 4)
$recentProducts = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 4")->fetchAll();
?>

<!-- KPI Counters Grid -->
<div class="admin-kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon-wrap">
            <i class="fa-solid fa-shuttlecock"></i>
        </div>
        <div class="kpi-data">
            <span class="kpi-number"><?= $totalProducts ?></span>
            <span class="kpi-label">Active Shuttlecock Models</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon-wrap" style="color: #38bdf8; border-color: rgba(56, 189, 248, 0.4); background: rgba(56, 189, 248, 0.1);">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
        <div class="kpi-data">
            <span class="kpi-number"><?= $newInquiries ?></span>
            <span class="kpi-label">Unread Customer Inquiries</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon-wrap" style="color: #34d399; border-color: rgba(52, 211, 153, 0.4); background: rgba(52, 211, 153, 0.1);">
            <i class="fa-solid fa-boxes-stacked"></i>
        </div>
        <div class="kpi-data">
            <span class="kpi-number"><?= $bulkOrders ?></span>
            <span class="kpi-label">Wholesale / Carton Requests</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon-wrap" style="color: #fbbf24; border-color: rgba(251, 191, 36, 0.4); background: rgba(251, 191, 36, 0.1);">
            <i class="fa-solid fa-comments"></i>
        </div>
        <div class="kpi-data">
            <span class="kpi-number"><?= $totalInquiries ?></span>
            <span class="kpi-label">Total Messages Logged</span>
        </div>
    </div>
</div>

<!-- Quick Action Shortcuts -->
<div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
    <a href="product_add.php" class="btn btn-cyan btn-sm btn-glow">
        <i class="fa-solid fa-plus"></i> Add New Product
    </a>
    <a href="inquiries.php" class="btn btn-outline-cyan btn-sm">
        <i class="fa-solid fa-inbox"></i> Manage All Inquiries
    </a>
    <a href="settings.php" class="btn btn-outline-cyan btn-sm">
        <i class="fa-solid fa-gear"></i> Update Store Settings
    </a>
</div>

<!-- Recent Inquiries Panel -->
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fa-solid fa-bell text-cyan"></i>
            <span>Latest Customer & Distributor Inquiries</span>
        </div>
        <a href="inquiries.php" style="color: var(--adm-cyan); font-size: 0.85rem; text-decoration: none;">
            View All Inquiries <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <div class="admin-card-body" style="padding: 0;">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client / Club</th>
                        <th>Email & Phone</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentInquiries)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2.5rem; color: var(--adm-muted);">
                                <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                                No customer inquiries submitted yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentInquiries as $inq): ?>
                            <tr>
                                <td style="font-size: 0.8rem; color: var(--adm-muted);">
                                    <?= date('M d, Y', strtotime($inq['created_at'])) ?>
                                </td>
                                <td>
                                    <strong style="color: #fff;"><?= htmlspecialchars($inq['name']) ?></strong>
                                    <div style="font-size: 0.8rem; color: var(--adm-muted);"><?= htmlspecialchars($inq['subject'] ?: 'No subject') ?></div>
                                </td>
                                <td>
                                    <div><a href="mailto:<?= htmlspecialchars($inq['email']) ?>" style="color: var(--adm-cyan);"><?= htmlspecialchars($inq['email']) ?></a></div>
                                    <div style="font-size: 0.8rem; color: var(--adm-muted);"><?= htmlspecialchars($inq['phone'] ?: 'N/A') ?></div>
                                </td>
                                <td>
                                    <span style="font-family: var(--font-tech); text-transform: uppercase; font-size: 0.8rem; color: var(--adm-cyan);">
                                        <?= str_replace('_', ' ', $inq['inquiry_type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($inq['status'] === 'new'): ?>
                                        <span class="badge-status badge-new"><i class="fa-solid fa-bolt"></i> New</span>
                                    <?php elseif ($inq['status'] === 'in_progress'): ?>
                                        <span class="badge-status badge-progress"><i class="fa-solid fa-spinner"></i> In Progress</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-replied"><i class="fa-solid fa-check"></i> Replied</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="inquiries.php?view=<?= $inq['id'] ?>" class="btn-icon btn-icon-edit" title="Read Message">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <?php if ($inq['status'] !== 'replied'): ?>
                                            <a href="index.php?action=update_status&id=<?= $inq['id'] ?>&status=replied" class="btn-icon btn-icon-edit" style="background: rgba(16, 185, 129, 0.15); color: #34d399;" title="Mark as Replied">
                                                <i class="fa-solid fa-check"></i>
                                            </a>
                                        <?php endif; ?>
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

<!-- Product Lineup Quick View -->
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fa-solid fa-layer-group text-cyan"></i>
            <span>Current Shuttlecock Product Lineup</span>
        </div>
        <a href="products.php" style="color: var(--adm-cyan); font-size: 0.85rem; text-decoration: none;">
            Manage All Products (<?= $totalProducts ?>) <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <div class="admin-card-body" style="padding: 0;">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Model</th>
                        <th>Category</th>
                        <th>Speed</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentProducts as $prod): ?>
                        <tr>
                            <td style="display: flex; align-items: center; gap: 0.85rem;">
                                <img src="../<?= htmlspecialchars($prod['image']) ?>" alt="Thumb" class="table-thumb">
                                <div>
                                    <strong style="color: #fff;"><?= htmlspecialchars($prod['name']) ?></strong>
                                    <div style="font-size: 0.785rem; color: var(--adm-muted);"><?= htmlspecialchars($prod['feather_type']) ?></div>
                                </div>
                            </td>
                            <td><strong class="text-cyan"><?= htmlspecialchars($prod['model_code']) ?></strong></td>
                            <td><?= htmlspecialchars($prod['category']) ?></td>
                            <td>Speed <?= htmlspecialchars($prod['speed']) ?></td>
                            <td><strong style="color: #fff;">$<?= number_format($prod['price'], 2) ?></strong></td>
                            <td>
                                <div class="action-btn-group">
                                    <a href="product_edit.php?id=<?= $prod['id'] ?>" class="btn-icon btn-icon-edit" title="Edit Product">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
