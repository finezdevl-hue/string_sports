<?php
// admin/product_delete.php
require_once __DIR__ . '/../config/db.php';
checkAdminAuth();

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT name FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $name = $stmt->fetchColumn();

        if ($name) {
            $del = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $del->execute([$id]);
            setFlash('success', "Product '{$name}' was deleted successfully.");
        } else {
            setFlash('danger', "Product not found.");
        }
    } catch (PDOException $e) {
        setFlash('danger', "Error deleting product: " . $e->getMessage());
    }
}

header("Location: products.php");
exit;
