<?php
// config/db.php - Database Configuration & Auto-Migration for Strings Sports

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-detect environment (Localhost vs Live Server)
$is_local = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']) 
    || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0);

if ($is_local) {
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'strings_sports_db';
} else {
    $db_host = 'localhost';
    $db_user = 'finezin_dbu_stringsports';
    $db_pass = 'Sports2026#';
    $db_name = 'finezin_db_stringsports';
}

try {
    // 1. Direct connection specifying database name in DSN (Crucial for cPanel / shared hosting)
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    // 2. Fallback for fresh local setup (creates DB if missing and user has root privileges)
    if ($is_local) {
        try {
            $pdo = new PDO("mysql:host=$db_host;charset=utf8mb4", $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `$db_name`");
        } catch (PDOException $e2) {
            die("Database Connection / Setup Failed: " . htmlspecialchars($e2->getMessage()));
        }
    } else {
        die("Database Connection Failed: " . htmlspecialchars($e->getMessage()));
    }
}

// Initialize Schema
try {
    initDatabaseSchema($pdo);
} catch (PDOException $e) {
    // Error logged if table creation fails
    error_log("Schema init error: " . $e->getMessage());
}

function initDatabaseSchema(PDO $pdo) {
    // 1. Admins Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `name` VARCHAR(100) NOT NULL DEFAULT 'Strings Admin',
        `email` VARCHAR(100) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 2. Products Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `products` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(150) NOT NULL,
        `model_code` VARCHAR(50) NOT NULL,
        `category` VARCHAR(50) NOT NULL,
        `speed` VARCHAR(20) NOT NULL DEFAULT '77',
        `feather_type` VARCHAR(100) NOT NULL,
        `cork_base` VARCHAR(100) NOT NULL,
        `durability_rating` INT DEFAULT 5,
        `flight_rating` INT DEFAULT 5,
        `price` DECIMAL(10, 2) NOT NULL,
        `stock_status` ENUM('in_stock', 'low_stock', 'pre_order') DEFAULT 'in_stock',
        `quantity_per_tube` INT DEFAULT 12,
        `description` TEXT,
        `features` TEXT,
        `image` VARCHAR(255) NOT NULL,
        `badge` VARCHAR(50) DEFAULT NULL,
        `is_featured` TINYINT(1) DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 3. Inquiries Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `inquiries` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `phone` VARCHAR(50),
        `subject` VARCHAR(150),
        `inquiry_type` ENUM('general', 'bulk_order', 'dealership', 'sponsorship') DEFAULT 'general',
        `message` TEXT NOT NULL,
        `status` ENUM('new', 'in_progress', 'replied') DEFAULT 'new',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 4. Site Settings Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `site_settings` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `setting_key` VARCHAR(50) NOT NULL UNIQUE,
        `setting_value` TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Check if Admin exists, otherwise create default
    $adminCheck = $pdo->query("SELECT COUNT(*) FROM `admins`")->fetchColumn();
    if ($adminCheck == 0) {
        $defaultPassword = password_hash('Strings@Sports2026!', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO `admins` (username, password, name, email) VALUES (?, ?, ?, ?)");
        $stmt->execute(['strings_admin', $defaultPassword, 'Master Admin', 'info@stringsports.com']);
    }

    // Check if Products exist, otherwise seed defaults matching the brand
    $prodCheck = $pdo->query("SELECT COUNT(*) FROM `products`")->fetchColumn();
    if ($prodCheck == 0) {
        $sampleProducts = [
            [
                'name' => 'STRINGS S40 Tournament Shuttlecock',
                'model_code' => 'S40',
                'category' => 'Tournament Grade',
                'speed' => '77',
                'feather_type' => 'Class A Premium Goose Feather',
                'cork_base' => 'Solid Natural Portuguese Cork',
                'durability_rating' => 5,
                'flight_rating' => 5,
                'price' => 32.50,
                'stock_status' => 'in_stock',
                'quantity_per_tube' => 12,
                'description' => 'Engineered for tournament play, the STRINGS S40 delivers razor-sharp trajectory accuracy, consistent rotational balance, and extreme durability under high-impact smashes.',
                'features' => "Premium Goose Feather\nStable Aerodynamic Flight\nPortuguese Natural Cork Base\nBWF Tournament Spec Approved\n12 Shuttlecocks per Tube",
                'image' => 'assets/images/s40.jpg',
                'badge' => 'Flagship Model',
                'is_featured' => 1
            ],
            [
                'name' => 'STRINGS S50 Elite Championship',
                'model_code' => 'S50',
                'category' => 'Championship',
                'speed' => '77',
                'feather_type' => 'Selected Top-Tier Straight Goose Feather',
                'cork_base' => 'Dual-Layer Natural Cork Composite',
                'durability_rating' => 5,
                'flight_rating' => 5,
                'price' => 38.00,
                'stock_status' => 'in_stock',
                'quantity_per_tube' => 12,
                'description' => 'The ultimate apex shuttlecock for national championships and world-level tournaments. Features laser-aligned feather spacing and unmatched flight stability in international arena environments.',
                'features' => "Top-Tier Hand-Picked Feathers\nMicro-balanced Flight Trajectory\nDual-layer Cork Core\nUltra Resilient Stem Structure\n12 Shuttlecocks per Tube",
                'image' => 'assets/images/s50.jpg',
                'badge' => 'Tournament Choice',
                'is_featured' => 1
            ],
            [
                'name' => 'STRINGS S30 Club Pro Training',
                'model_code' => 'S30',
                'category' => 'Club Pro',
                'speed' => '76',
                'feather_type' => 'High-Durability Goose Feather',
                'cork_base' => 'Portuguese Composite Cork',
                'durability_rating' => 4,
                'flight_rating' => 4,
                'price' => 24.50,
                'stock_status' => 'in_stock',
                'quantity_per_tube' => 12,
                'description' => 'The go-to shuttlecock for intensive club training, academy sessions, and competitive leagues. Delivers long-lasting feather life and reliable smash resistance at an attractive price point.',
                'features' => "Reinforced Feather Stems\nHigh Durability Against Off-Center Hits\nConsistent Speed & Spin Recovery\nIdeal for Badminton Academies\n12 Shuttlecocks per Tube",
                'image' => 'assets/images/s30.jpg',
                'badge' => 'Club Favorite',
                'is_featured' => 1
            ]
        ];

        $ins = $pdo->prepare("INSERT INTO `products` 
            (name, model_code, category, speed, feather_type, cork_base, durability_rating, flight_rating, price, stock_status, quantity_per_tube, description, features, image, badge, is_featured) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($sampleProducts as $p) {
            $ins->execute([
                $p['name'], $p['model_code'], $p['category'], $p['speed'],
                $p['feather_type'], $p['cork_base'], $p['durability_rating'],
                $p['flight_rating'], $p['price'], $p['stock_status'],
                $p['quantity_per_tube'], $p['description'], $p['features'],
                $p['image'], $p['badge'], $p['is_featured']
            ]);
        }
    }

    // Default Site Settings
    $settingsCheck = $pdo->query("SELECT COUNT(*) FROM `site_settings`")->fetchColumn();
    if ($settingsCheck == 0) {
        $defaultSettings = [
            'company_name' => 'STRINGS Sports',
            'slogan' => 'PLAY BEYOND LIMITS',
            'email' => 'info@stringsports.com',
            'phone' => '+1 (800) 584-7874',
            'address' => '700 Aero-Dynamics Way, Court Suite 10, Sports District',
            'website' => 'www.stringsports.com',
            'announcement' => 'Global Distribution Available: Experience the precision of Strings S-Series Shuttlecocks.'
        ];
        $st = $pdo->prepare("INSERT INTO `site_settings` (setting_key, setting_value) VALUES (?, ?)");
        foreach ($defaultSettings as $k => $v) {
            $st->execute([$k, $v]);
        }
    }
}

// Helper Functions
function getSetting($key, $default = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM `site_settings` WHERE setting_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return ($val !== false && $val !== null) ? $val : $default;
    } catch (Exception $e) {
        return $default;
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input ?? ''), ENT_QUOTES, 'UTF-8');
}

function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type, // 'success', 'danger', 'warning', 'info'
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function checkAdminAuth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
}
