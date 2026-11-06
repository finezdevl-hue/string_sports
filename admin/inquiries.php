<?php
// admin/inquiries.php - Manage Customer & Distributor Inquiries
$admin_title = "Inquiries & Leads";
require_once __DIR__ . '/includes/admin_header.php';

// Handle Status Updates
if (isset($_GET['action']) && $_GET['action'] === 'status' && isset($_GET['id']) && isset($_GET['val'])) {
    $inqId = intval($_GET['id']);
    $val = sanitize($_GET['val']);
    if (in_array($val, ['new', 'in_progress', 'replied'])) {
        $stmt = $pdo->prepare("UPDATE inquiries SET status = ? WHERE id = ?");
        $stmt->execute([$val, $inqId]);
        setFlash('success', "Inquiry #{$inqId} status changed to " . strtoupper($val));
        header("Location: inquiries.php");
        exit;
    }
}

// Handle Delete Inquiry
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $inqId = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
    $stmt->execute([$inqId]);
    setFlash('success', "Inquiry #{$inqId} has been removed.");
    header("Location: inquiries.php");
    exit;
}

// Filter logic
$statusFilter = sanitize($_GET['status'] ?? 'all');
$sql = "SELECT * FROM inquiries";
$params = [];

if ($statusFilter !== 'all' && in_array($statusFilter, ['new', 'in_progress', 'replied'])) {
    $sql .= " WHERE status = ?";
    $params[] = $statusFilter;
}

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$inquiries = $stmt->fetchAll();

// Check if specific inquiry requested for viewing modal
$viewInquiry = null;
if (isset($_GET['view'])) {
    $vId = intval($_GET['view']);
    $vStmt = $pdo->prepare("SELECT * FROM inquiries WHERE id = ?");
    $vStmt->execute([$vId]);
    $viewInquiry = $vStmt->fetch();
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.65rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem;">Customer Inquiries & Wholesale Leads</h2>
        <p style="color: var(--adm-muted); font-size: 0.9rem;">Review incoming bulk supply demands, dealership applications, and club inquiries.</p>
    </div>

    <!-- Filter Buttons -->
    <div style="display: flex; gap: 0.5rem;">
        <a href="inquiries.php?status=all" class="btn btn-sm <?= ($statusFilter === 'all') ? 'btn-cyan' : 'btn-outline-cyan' ?>">All</a>
        <a href="inquiries.php?status=new" class="btn btn-sm <?= ($statusFilter === 'new') ? 'btn-cyan' : 'btn-outline-cyan' ?>">New</a>
        <a href="inquiries.php?status=in_progress" class="btn btn-sm <?= ($statusFilter === 'in_progress') ? 'btn-cyan' : 'btn-outline-cyan' ?>">In Progress</a>
        <a href="inquiries.php?status=replied" class="btn btn-sm <?= ($statusFilter === 'replied') ? 'btn-cyan' : 'btn-outline-cyan' ?>">Replied</a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fa-solid fa-inbox text-cyan"></i>
            <span>Inquiries List (<?= count($inquiries) ?>)</span>
        </div>
    </div>
    <div class="admin-card-body" style="padding: 0;">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Client / Organization</th>
                        <th>Contact Email & Phone</th>
                        <th>Inquiry Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inquiries)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem; color: var(--adm-muted);">
                                No inquiries match the selected filter.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inquiries as $inq): ?>
                            <tr>
                                <td style="font-family: var(--font-tech); font-weight: 700; color: var(--adm-cyan);">#<?= $inq['id'] ?></td>
                                <td style="font-size: 0.8rem; color: var(--adm-muted);">
                                    <?= date('M d, Y H:i', strtotime($inq['created_at'])) ?>
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
                                    <span style="font-family: var(--font-tech); text-transform: uppercase; font-size: 0.8rem; color: #fff;">
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
                                        <a href="inquiries.php?view=<?= $inq['id'] ?>" class="btn-icon btn-icon-edit" title="Read Complete Message">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="inquiries.php?action=delete&id=<?= $inq['id'] ?>" class="btn-icon btn-icon-delete btn-confirm-delete" 
                                           data-confirm-message="Delete inquiry from <?= htmlspecialchars($inq['name']) ?>?" title="Delete Record">
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

<!-- View Inquiry Modal -->
<?php if ($viewInquiry): ?>
<div class="modal-overlay active" id="viewMessageModal">
    <div class="modal-card" style="max-width: 650px;">
        <a href="inquiries.php" class="modal-close-btn">&times;</a>
        <div class="modal-tag"><i class="fa-solid fa-envelope-open"></i> INQUIRY #<?= $viewInquiry['id'] ?></div>
        <h3 class="modal-title"><?= htmlspecialchars($viewInquiry['subject'] ?: 'Customer Message') ?></h3>
        <p style="font-size: 0.85rem; color: var(--adm-muted); margin-bottom: 1.5rem;">
            Received on <?= date('F d, Y at h:i A', strtotime($viewInquiry['created_at'])) ?>
        </p>

        <div style="background: rgba(3, 7, 18, 0.7); border: 1px solid var(--adm-border); border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
            <div style="margin-bottom: 0.5rem;"><strong style="color: #fff;">From:</strong> <?= htmlspecialchars($viewInquiry['name']) ?></div>
            <div style="margin-bottom: 0.5rem;"><strong style="color: #fff;">Email:</strong> <a href="mailto:<?= htmlspecialchars($viewInquiry['email']) ?>" style="color: var(--adm-cyan);"><?= htmlspecialchars($viewInquiry['email']) ?></a></div>
            <div style="margin-bottom: 0.5rem;"><strong style="color: #fff;">Phone / WhatsApp:</strong> <?= htmlspecialchars($viewInquiry['phone'] ?: 'N/A') ?></div>
            <div><strong style="color: #fff;">Classification:</strong> <span class="text-cyan"><?= strtoupper(str_replace('_', ' ', $viewInquiry['inquiry_type'])) ?></span></div>
        </div>

        <div style="background: rgba(6, 14, 34, 0.9); border: 1px solid rgba(0, 240, 255, 0.2); border-radius: 8px; padding: 1.5rem; margin-bottom: 1.75rem;">
            <h4 style="font-size: 0.9rem; color: var(--adm-cyan); margin-bottom: 0.75rem; text-transform: uppercase;">Message Content</h4>
            <div style="color: var(--text-light); white-space: pre-wrap; line-height: 1.7; font-size: 0.95rem;">
                <?= htmlspecialchars($viewInquiry['message']) ?>
            </div>
        </div>

        <!-- Status Changer & Reply -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-top: 1px solid var(--adm-border); padding-top: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 0.85rem; color: var(--adm-muted);">Set Status:</span>
                <a href="inquiries.php?action=status&id=<?= $viewInquiry['id'] ?>&val=new" class="btn btn-sm <?= ($viewInquiry['status'] === 'new') ? 'btn-cyan' : 'btn-outline-cyan' ?>">New</a>
                <a href="inquiries.php?action=status&id=<?= $viewInquiry['id'] ?>&val=in_progress" class="btn btn-sm <?= ($viewInquiry['status'] === 'in_progress') ? 'btn-cyan' : 'btn-outline-cyan' ?>">In Progress</a>
                <a href="inquiries.php?action=status&id=<?= $viewInquiry['id'] ?>&val=replied" class="btn btn-sm <?= ($viewInquiry['status'] === 'replied') ? 'btn-cyan' : 'btn-outline-cyan' ?>">Replied</a>
            </div>

            <a href="mailto:<?= htmlspecialchars($viewInquiry['email']) ?>?subject=Re: <?= urlencode($viewInquiry['subject']) ?> - STRINGS Sports" class="btn btn-cyan btn-sm btn-glow">
                <i class="fa-solid fa-reply"></i> Reply via Email
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
