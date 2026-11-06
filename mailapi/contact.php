<?php
/**
 * mailapi/contact.php - STRINGS SPORTS Inquiry Processing & Mail Dispatcher
 * Handles contact forms, modal inquiries, database logging, JSON fallback, and email notification.
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/sendmail/class.phpmailer.php';

// Check if request is AJAX
$is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (!empty($_POST['ajax']))
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

function respond($success, $message, $is_ajax, $http_code = 200) {
    if ($is_ajax) {
        http_response_code($http_code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $message
        ]);
        exit;
    } else {
        if ($success) {
            setFlash('success', $message);
            header("Location: ../contact.php?status=success");
        } else {
            setFlash('danger', $message);
            header("Location: ../contact.php?status=error");
        }
        exit;
    }
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, "Invalid request method. Only POST is accepted.", $is_ajax, 405);
}

// Retrieve and sanitize fields
$name = sanitize($_POST['name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone = sanitize($_POST['phone'] ?? '');
$inquiry_type = sanitize($_POST['inquiry_type'] ?? 'general');
$quantity_tier = sanitize($_POST['quantity_tier'] ?? '');
$raw_message = trim($_POST['message'] ?? '');
$subject = sanitize($_POST['subject'] ?? '');

// Validation
if (empty($name)) {
    respond(false, "Please provide your full name or club organization.", $is_ajax, 400);
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, "Please provide a valid email address.", $is_ajax, 400);
}

if (empty($raw_message)) {
    respond(false, "Please enter your message or order details.", $is_ajax, 400);
}

// Format message body
$full_message = $raw_message;
if (!empty($quantity_tier)) {
    $full_message = "[Quantity Tier: " . $quantity_tier . "]\n\n" . $full_message;
}

// Auto-fill subject if empty
if (empty($subject)) {
    switch ($inquiry_type) {
        case 'bulk_order':
            $subject = 'Wholesale Carton / Bulk Supply Inquiry';
            break;
        case 'dealership':
            $subject = 'Authorized Dealership / Retailer Inquiry';
            break;
        case 'sponsorship':
            $subject = 'Tournament Sponsorship & Academy Inquiry';
            break;
        default:
            $subject = 'General Product Inquiry';
            break;
    }
}

$inquiry_id = null;

// 1. Insert into Database (Inquiries table)
try {
    $stmt = $pdo->prepare("INSERT INTO inquiries (name, email, phone, subject, inquiry_type, message, status) VALUES (?, ?, ?, ?, ?, ?, 'new')");
    $stmt->execute([$name, $email, $phone, $subject, $full_message]);
    $inquiry_id = $pdo->lastInsertId();
} catch (PDOException $e) {
    // If table doesn't match enum or other error, attempt fallback insert with general type
    try {
        $stmt = $pdo->prepare("INSERT INTO inquiries (name, email, phone, subject, inquiry_type, message, status) VALUES (?, ?, ?, ?, 'general', ?, 'new')");
        $stmt->execute([$name, $email, $phone, $subject, $full_message]);
        $inquiry_id = $pdo->lastInsertId();
    } catch (Exception $fallback_e) {
        error_log("Database error storing inquiry: " . $fallback_e->getMessage());
    }
}

// 2. Save Backup to JSON Storage
$jsonDir = __DIR__ . '/json';
if (!is_dir($jsonDir)) {
    @mkdir($jsonDir, 0755, true);
}
$jsonFile = $jsonDir . '/inquiries.json';
$messages = [];
if (file_exists($jsonFile)) {
    $existing = @file_get_contents($jsonFile);
    $messages = json_decode($existing, true) ?: [];
}
$newRecord = [
    'id' => $inquiry_id ?: (time() . '_' . rand(100, 999)),
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'subject' => $subject,
    'inquiry_type' => $inquiry_type,
    'quantity_tier' => $quantity_tier,
    'message' => $full_message,
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
    'created_at' => date('Y-m-d H:i:s')
];
array_unshift($messages, $newRecord);
@file_put_contents($jsonFile, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 3. Dispatch Branded Email via PHPMailer
$company_name = getSetting('company_name', 'STRINGS Sports');
$admin_email = getSetting('email', 'info@stringsports.com');

// Site URL detection
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$site_root = $scheme . $host . dirname(dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$site_root = rtrim($site_root, '/\\');

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;
$mail->Timeout = 10;
$mail->Username = 'fineztechnologies@gmail.com';
$mail->Password = 'gdlxyptftlwxcbrw';

$mail->From = 'fineztechnologies@gmail.com';
$mail->FromName = $company_name . ' Portal';
$mail->addAddress($admin_email, $company_name . ' Distribution Desk');
// Also notify the verified gmail account
$mail->addCC('finezseo@gmail.com', 'Admin Notification Backup');
$mail->addReplyTo($email, $name);

$mail->isHTML(true);
$mail->CharSet = 'UTF-8';
$mail->Subject = "[STRINGS SPORTS] New Inquiry: " . $subject . " - " . $name;

// Email HTML Template
$email_body = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>New STRINGS Inquiry</title>
</head>
<body style='margin: 0; padding: 0; background-color: #0b0f19; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; color: #e2e8f0;'>
    <table role='presentation' width='100%' border='0' cellspacing='0' cellpadding='0' style='background-color: #0b0f19; padding: 30px 15px;'>
        <tr>
            <td align='center'>
                <table role='presentation' width='100%' style='max-width: 600px; background-color: #121826; border-radius: 12px; overflow: hidden; border: 1px solid #1e293b; box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1);' cellspacing='0' cellpadding='0'>
                    <!-- Header -->
                    <tr>
                        <td style='background: linear-gradient(135deg, #091321 0%, #0d1e38 100%); padding: 35px 30px; text-align: center; border-bottom: 2px solid #00f0ff;'>
                            <div style='font-size: 28px; font-weight: 900; letter-spacing: 3px; color: #ffffff;'>
                                <span style='color: #00f0ff;'>STRINGS</span> SPORTS
                            </div>
                            <div style='font-size: 11px; letter-spacing: 2px; color: #94a3b8; margin-top: 4px; text-transform: uppercase;'>
                                Play Beyond Limits • Official Inquiry Transmission
                            </div>
                        </td>
                    </tr>

                    <!-- Alert Badge -->
                    <tr>
                        <td style='padding: 24px 30px 10px;'>
                            <div style='background-color: rgba(0, 240, 255, 0.1); border: 1px solid rgba(0, 240, 255, 0.3); border-radius: 8px; padding: 12px 16px; color: #00f0ff; font-size: 13px; font-weight: 600; text-align: center;'>
                                ⚡ Direct Customer / Wholesale Inquiry Received
                            </div>
                        </td>
                    </tr>

                    <!-- Details Table -->
                    <tr>
                        <td style='padding: 10px 30px 25px;'>
                            <table width='100%' cellspacing='0' cellpadding='0' style='border-collapse: collapse;'>
                                <tr>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px; width: 140px;'>
                                        <strong>Full Name / Club:</strong>
                                    </td>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #f8fafc; font-size: 14px; font-weight: 600;'>
                                        " . htmlspecialchars($name) . "
                                    </td>
                                </tr>
                                <tr>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px;'>
                                        <strong>Email Address:</strong>
                                    </td>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #00f0ff; font-size: 14px;'>
                                        <a href='mailto:" . htmlspecialchars($email) . "' style='color: #00f0ff; text-decoration: none; font-weight: 600;'>" . htmlspecialchars($email) . "</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px;'>
                                        <strong>Phone / WhatsApp:</strong>
                                    </td>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #f8fafc; font-size: 14px;'>
                                        " . (!empty($phone) ? "<a href='tel:" . htmlspecialchars($phone) . "' style='color: #38bdf8; text-decoration: none;'>" . htmlspecialchars($phone) . "</a>" : "<span style='color: #64748b;'>Not provided</span>") . "
                                    </td>
                                </tr>
                                <tr>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px;'>
                                        <strong>Inquiry Nature:</strong>
                                    </td>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #38bdf8; font-size: 14px; font-weight: 600; text-transform: capitalize;'>
                                        " . htmlspecialchars(str_replace('_', ' ', $inquiry_type)) . "
                                    </td>
                                </tr>";

if (!empty($quantity_tier)) {
    $email_body .= "
                                <tr>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px;'>
                                        <strong>Quantity Tier:</strong>
                                    </td>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #facc15; font-size: 14px; font-weight: 600;'>
                                        " . htmlspecialchars($quantity_tier) . "
                                    </td>
                                </tr>";
}

$email_body .= "
                                <tr>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px;'>
                                        <strong>Subject:</strong>
                                    </td>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #f8fafc; font-size: 14px;'>
                                        " . htmlspecialchars($subject) . "
                                    </td>
                                </tr>
                                <tr>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px;'>
                                        <strong>Submission Time:</strong>
                                    </td>
                                    <td style='padding: 12px 0; border-bottom: 1px solid #1e293b; color: #94a3b8; font-size: 13px;'>
                                        " . date('M d, Y - h:i A T') . "
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Message Body Box -->
                    <tr>
                        <td style='padding: 0 30px 25px;'>
                            <div style='color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;'>
                                Inquiry Message / Requirements:
                            </div>
                            <div style='background-color: #0b0f19; border: 1px solid #1e293b; border-radius: 8px; padding: 18px; color: #e2e8f0; font-size: 14px; line-height: 1.6; white-space: pre-wrap;'>
" . htmlspecialchars($full_message) . "
                            </div>
                        </td>
                    </tr>

                    <!-- Actions -->
                    <tr>
                        <td style='padding: 10px 30px 30px; text-align: center;'>
                            <a href='mailto:" . htmlspecialchars($email) . "?subject=Re: " . urlencode($subject) . "' style='background: linear-gradient(135deg, #00f0ff 0%, #0088ff 100%); color: #050b14; text-decoration: none; font-weight: 800; font-size: 14px; padding: 12px 28px; border-radius: 6px; display: inline-block; letter-spacing: 0.5px;'>
                                ✉ Reply to " . htmlspecialchars($name) . "
                            </a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style='background-color: #090d16; padding: 20px 30px; text-align: center; border-top: 1px solid #1e293b;'>
                            <p style='margin: 0 0 6px; color: #64748b; font-size: 12px;'>
                                Automated notification from <strong>STRINGS SPORTS</strong> Logistics & Equipment Portal.
                            </p>
                            <p style='margin: 0; color: #475569; font-size: 11px;'>
                                &copy; " . date('Y') . " STRINGS SPORTS. All Rights Reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
";

$mail->Body = $email_body;
$mail->AltBody = "STRINGS SPORTS - New Inquiry\n\nName: $name\nEmail: $email\nPhone: $phone\nInquiry Type: $inquiry_type\nSubject: $subject\n\nMessage:\n$full_message\n\nSubmitted: " . date('Y-m-d H:i:s');

// Attempt to send email
$mail_sent = false;
try {
    $mail_sent = $mail->send();
} catch (Exception $e) {
    error_log("PHPMailer error sending inquiry email: " . $mail->ErrorInfo);
}

// Final Success Response
$success_text = "Thank you, " . $name . "! Your inquiry has been received. Our sports distribution team will get back to you within 24 business hours.";
respond(true, $success_text, $is_ajax, 200);
