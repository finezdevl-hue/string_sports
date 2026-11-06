<?php
// admin/product_edit.php - Edit Shuttlecock Product
$admin_title = "Edit Product";
require_once __DIR__ . '/includes/admin_header.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: products.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    setFlash('danger', "Product not found.");
    header("Location: products.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $model_code = strtoupper(sanitize($_POST['model_code'] ?? ''));
    $category = sanitize($_POST['category'] ?? 'Tournament Grade');
    $speed = sanitize($_POST['speed'] ?? '77');
    $feather_type = sanitize($_POST['feather_type'] ?? 'Premium Goose Feather');
    $cork_base = sanitize($_POST['cork_base'] ?? 'Solid Portuguese Cork');
    $durability_rating = intval($_POST['durability_rating'] ?? 5);
    $price = floatval($_POST['price'] ?? 0.00);
    $quantity_per_tube = intval($_POST['quantity_per_tube'] ?? 12);
    $badge = sanitize($_POST['badge'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $description = sanitize($_POST['description'] ?? '');

    $imagePath = $product['image']; // retain existing

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['product_image']['tmp_name'];
        $fileName = $_FILES['product_image']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($fileExt, $allowedExts)) {
            $error = "Invalid image file type. Please upload a JPG, PNG, or WEBP image.";
        } else {
            $newFileName = 'prod_' . time() . '_' . rand(100, 999) . '.' . $fileExt;
            $destination = __DIR__ . '/../assets/uploads/' . $newFileName;
            if (move_uploaded_file($fileTmp, $destination)) {
                $imagePath = 'assets/uploads/' . $newFileName;
            } else {
                $error = "Failed to upload image file.";
            }
        }
    }

    if (empty($error)) {
        if (empty($name) || empty($model_code) || $price <= 0) {
            $error = "Please provide Product Name, Model Code, and a valid Price.";
        } else {
            try {
                $upd = $pdo->prepare("UPDATE products SET 
                    name = ?, model_code = ?, category = ?, speed = ?, feather_type = ?, cork_base = ?, 
                    durability_rating = ?, price = ?, quantity_per_tube = ?, description = ?, image = ?, 
                    badge = ?, is_featured = ? 
                    WHERE id = ?");
                $upd->execute([
                    $name, $model_code, $category, $speed, $feather_type, $cork_base,
                    $durability_rating, $price, $quantity_per_tube, $description, $imagePath,
                    $badge, $is_featured, $id
                ]);

                setFlash('success', "Product '{$name}' updated successfully!");
                header("Location: products.php");
                exit;
            } catch (PDOException $e) {
                $error = "Database Error: " . $e->getMessage();
            }
        }
    }
}
?>

<div style="max-width: 860px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.65rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem;">
                Edit: <?= htmlspecialchars($product['name']) ?>
            </h2>
            <p style="color: var(--adm-muted); font-size: 0.9rem;">Modify product specifications, speed grade, price, or packaging visuals.</p>
        </div>
        <a href="products.php" class="btn btn-outline-cyan btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>

    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="fa-solid fa-pen-to-square text-cyan"></i>
                <span>Update Model Details</span>
            </div>
        </div>
        <div class="admin-card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-half">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" required class="form-input" value="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                    <div class="form-group col-half">
                        <label for="model_code">Model Code *</label>
                        <input type="text" id="model_code" name="model_code" required class="form-input" value="<?= htmlspecialchars($product['model_code']) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-half">
                        <label for="category">Category / Play Grade</label>
                        <select id="category" name="category" class="form-input">
                            <option value="Tournament Grade" <?= ($product['category'] === 'Tournament Grade') ? 'selected' : '' ?>>Tournament Grade</option>
                            <option value="Championship" <?= ($product['category'] === 'Championship') ? 'selected' : '' ?>>Championship / Apex</option>
                            <option value="Club Pro" <?= ($product['category'] === 'Club Pro') ? 'selected' : '' ?>>Club Pro</option>
                            <option value="Academy Training" <?= ($product['category'] === 'Academy Training') ? 'selected' : '' ?>>Academy Training</option>
                        </select>
                    </div>
                    <div class="form-group col-half">
                        <label for="speed">Flight Speed Rating</label>
                        <select id="speed" name="speed" class="form-input">
                            <option value="76" <?= ($product['speed'] === '76') ? 'selected' : '' ?>>Speed 76 (Warm / High Altitude)</option>
                            <option value="77" <?= ($product['speed'] === '77') ? 'selected' : '' ?>>Speed 77 (Standard International 18-24°C)</option>
                            <option value="78" <?= ($product['speed'] === '78') ? 'selected' : '' ?>>Speed 78 (Cold Climate &lt;18°C)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-half">
                        <label for="feather_type">Feather Specification</label>
                        <input type="text" id="feather_type" name="feather_type" class="form-input" value="<?= htmlspecialchars($product['feather_type']) ?>">
                    </div>
                    <div class="form-group col-half">
                        <label for="cork_base">Cork Core Composition</label>
                        <input type="text" id="cork_base" name="cork_base" class="form-input" value="<?= htmlspecialchars($product['cork_base']) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-half">
                        <label for="price">Price Per Tube ($ USD) *</label>
                        <input type="number" step="0.01" min="1" id="price" name="price" required class="form-input" value="<?= htmlspecialchars($product['price']) ?>">
                    </div>
                    <div class="form-group col-half">
                        <label for="quantity_per_tube">Quantity Per Tube</label>
                        <input type="number" id="quantity_per_tube" name="quantity_per_tube" class="form-input" value="<?= htmlspecialchars($product['quantity_per_tube']) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-half">
                        <label for="durability_rating">Durability Rating (1 - 5)</label>
                        <select id="durability_rating" name="durability_rating" class="form-input">
                            <option value="5" <?= ($product['durability_rating'] == 5) ? 'selected' : '' ?>>5 Stars (Tournament)</option>
                            <option value="4" <?= ($product['durability_rating'] == 4) ? 'selected' : '' ?>>4 Stars (Club)</option>
                            <option value="3" <?= ($product['durability_rating'] == 3) ? 'selected' : '' ?>>3 Stars (Standard)</option>
                        </select>
                    </div>
                    <div class="form-group col-half">
                        <label for="badge">Product Badge / Tag (Optional)</label>
                        <input type="text" id="badge" name="badge" class="form-input" value="<?= htmlspecialchars($product['badge'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Product Description</label>
                    <textarea id="description" name="description" rows="3" class="form-input"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>

                <!-- Product Image Update -->
                <div class="form-group" style="background: rgba(0, 240, 255, 0.04); border: 1px dashed rgba(0, 240, 255, 0.3); border-radius: 8px; padding: 1.5rem;">
                    <label style="color: var(--adm-cyan); font-weight: 700; margin-bottom: 0.5rem; display: block;">
                        <i class="fa-solid fa-image"></i> Product Image
                    </label>
                    <div style="display: flex; gap: 1.5rem; align-items: center; margin-bottom: 1rem;">
                        <div>
                            <span style="font-size: 0.75rem; color: var(--adm-muted); display: block; margin-bottom: 0.25rem;">Current Image:</span>
                            <img src="../<?= htmlspecialchars($product['image']) ?>" alt="Current" style="width: 80px; height: 80px; object-fit: contain; background: #030712; border-radius: 8px; border: 1px solid var(--adm-cyan);">
                        </div>
                        <div style="flex: 1;">
                            <label for="adminProductImageInput" style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 0.25rem;">Choose New Image (optional):</label>
                            <input type="file" id="adminProductImageInput" name="product_image" accept="image/*" class="form-input" style="padding: 0.5rem;">
                        </div>
                    </div>
                    <div>
                        <img id="adminImagePreview" src="" alt="New Preview" style="display: none; max-width: 140px; border-radius: 8px; border: 1px solid var(--adm-cyan);">
                    </div>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1.5rem;">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" style="width: 18px; height: 18px; accent-color: var(--adm-cyan);" <?= ($product['is_featured'] == 1) ? 'checked' : '' ?>>
                    <label for="is_featured" style="color: #fff; font-size: 0.95rem; margin-bottom: 0; cursor: pointer;">
                        Display as Featured Model on Homepage
                    </label>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-cyan btn-lg btn-glow btn-full">
                        <i class="fa-solid fa-floppy-disk"></i> Update Shuttlecock Specifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
