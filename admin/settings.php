<?php
// admin/settings.php - Store Settings & Admin Security
$admin_title = "System Settings";
require_once __DIR__ . '/includes/admin_header.php';

$error = '';

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_settings'])) {
        $fields = ['company_name', 'slogan', 'email', 'phone', 'address', 'website', 'announcement'];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $val = trim($_POST[$field]);
                $st = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) 
                                     ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
                $st->execute([$field, $val]);
            }
        }
        setFlash('success', "Website settings successfully updated!");
        header("Location: settings.php");
        exit;
    }

    // Handle Password Change
    if (isset($_POST['change_password'])) {
        $currentPass = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (empty($currentPass) || empty($newPass)) {
            $error = "Please fill out all password fields.";
        } elseif ($newPass !== $confirmPass) {
            $error = "New password and confirmation do not match.";
        } elseif (strlen($newPass) < 6) {
            $error = "New password must be at least 6 characters long.";
        } else {
            $adminId = $_SESSION['admin_id'];
            $st = $pdo->prepare("SELECT password FROM admins WHERE id = ?");
            $st->execute([$adminId]);
            $adminHash = $st->fetchColumn();

            if (password_verify($currentPass, $adminHash)) {
                $newHash = password_hash($newPass, PASSWORD_DEFAULT);
                $upd = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $upd->execute([$newHash, $adminId]);
                setFlash('success', "Password successfully updated! Use your new password on next login.");
                header("Location: settings.php");
                exit;
            } else {
                $error = "Current password is incorrect.";
            }
        }
    }
}
?>

<div style="max-width: 900px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.65rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem;">Portal & Branding Settings</h2>
        <p style="color: var(--adm-muted); font-size: 0.9rem;">Manage company contact channels, header announcements, and security credentials.</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>

    <!-- Site Configuration Card -->
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="fa-solid fa-sliders text-cyan"></i>
                <span>Brand Identity & Contact Information</span>
            </div>
        </div>
        <div class="admin-card-body">
            <form method="POST">
                <input type="hidden" name="update_settings" value="1">

                <div class="form-row">
                    <div class="form-group col-half">
                        <label for="company_name">Company Name</label>
                        <input type="text" id="company_name" name="company_name" class="form-input" value="<?= htmlspecialchars(getSetting('company_name', 'STRINGS Sports')) ?>">
                    </div>
                    <div class="form-group col-half">
                        <label for="slogan">Brand Tagline</label>
                        <input type="text" id="slogan" name="slogan" class="form-input" value="<?= htmlspecialchars(getSetting('slogan', 'PLAY BEYOND LIMITS')) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-half">
                        <label for="email">Public Contact Email</label>
                        <input type="email" id="email" name="email" class="form-input" value="<?= htmlspecialchars(getSetting('email', 'info@stringsports.com')) ?>">
                    </div>
                    <div class="form-group col-half">
                        <label for="phone">Customer & Wholesale Hotline</label>
                        <input type="text" id="phone" name="phone" class="form-input" value="<?= htmlspecialchars(getSetting('phone', '+1 (800) 584-7874')) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-half">
                        <label for="website">Official Domain URL</label>
                        <input type="text" id="website" name="website" class="form-input" value="<?= htmlspecialchars(getSetting('website', 'www.stringsports.com')) ?>">
                    </div>
                    <div class="form-group col-half">
                        <label for="address">Distribution Hub Address</label>
                        <input type="text" id="address" name="address" class="form-input" value="<?= htmlspecialchars(getSetting('address', '700 Aero-Dynamics Way, Court Suite 10, Sports District')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="announcement">Top Navigation Announcement Banner</label>
                    <input type="text" id="announcement" name="announcement" class="form-input" value="<?= htmlspecialchars(getSetting('announcement', 'Global Distribution Available: Experience the precision of Strings S-Series Shuttlecocks.')) ?>">
                </div>

                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-cyan btn-glow">
                        <i class="fa-solid fa-floppy-disk"></i> Save Global Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security & Password Card -->
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="fa-solid fa-lock text-cyan"></i>
                <span>Admin Password & Security</span>
            </div>
        </div>
        <div class="admin-card-body">
            <form method="POST">
                <input type="hidden" name="change_password" value="1">

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required class="form-input" placeholder="Enter current admin password">
                </div>

                <div class="form-row">
                    <div class="form-group col-half">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required class="form-input" placeholder="Minimum 6 characters">
                    </div>
                    <div class="form-group col-half">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required class="form-input" placeholder="Repeat new password">
                    </div>
                </div>

                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-outline-cyan">
                        <i class="fa-solid fa-key"></i> Update Admin Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
