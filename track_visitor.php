<?php
require_once 'config.php';

// Set appropriate headers for both JSON and sendBeacon requests
if (isset($_SERVER['HTTP_CONTENT_TYPE']) && strpos($_SERVER['HTTP_CONTENT_TYPE'], 'application/json') !== false) {
    header('Content-Type: application/json');
} else {
    header('Content-Type: text/plain');
}

// Prevent multiple includes
if (defined('TRACK_LOADED')) {
    return;
}
define('TRACK_LOADED', true);

// Get data from POST request (handles both JSON and sendBeacon)
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

// If JSON decode failed, the data might be sent as plain text by sendBeacon
if (!$data && !empty($rawInput)) {
    // Try to decode again (sometimes sendBeacon sends as string)
    $data = json_decode($rawInput, true);
    // If still fails, try to parse as plain JSON string
    if (!$data) {
        $data = json_decode(stripslashes($rawInput), true);
    }
}

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'No data received']);
    exit;
}

try {
    // Get visitor information
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $referrer = $_SERVER['HTTP_REFERER'] ?? null;
    $page_url = $data['page_url'] ?? $_SERVER['HTTP_REFERER'] ?? null;
    
    // Get or create session ID from cookies or generate one
    $session_id = $data['session_id'] ?? $_COOKIE['visitor_session_id'] ?? null;
    if (!$session_id) {
        $session_id = bin2hex(random_bytes(16));
        // Set cookie for session tracking (expires in 24 hours)
        if (!headers_sent()) {
            setcookie('visitor_session_id', $session_id, time() + (24 * 60 * 60), '/');
        }
    }
    
    // Extract data
    $name = isset($data['name']) && trim($data['name']) !== '' ? trim($data['name']) : null;
    $email = isset($data['email']) && trim($data['email']) !== '' ? trim($data['email']) : null;
    $phone = isset($data['phone']) && trim($data['phone']) !== '' ? trim($data['phone']) : null;
    $cookies_data = isset($data['cookies']) ? json_encode($data['cookies']) : null;
    
    // Validate email if provided
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email = null;
    }
    
    // Check if we should track this request
    // Only track if there's meaningful data (name, email, phone) OR if it's a form submit
    $hasMeaningfulData = ($name || $email || $phone);
    $isFormSubmit = isset($data['form_submit']) && $data['form_submit'] === true;
    
    // If no meaningful data and not a form submit, only update existing record or skip
    if (!$hasMeaningfulData && !$isFormSubmit) {
        // Check if session exists in last 24 hours
        $checkStmt = $pdo->prepare("
            SELECT id FROM visitor_data 
            WHERE session_id = ? AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ORDER BY created_at DESC LIMIT 1
        ");
        $checkStmt->execute([$session_id]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            // Update existing record with latest page URL
            $updateStmt = $pdo->prepare("
                UPDATE visitor_data 
                SET page_url = ?, referrer = ?, cookies_data = COALESCE(?, cookies_data)
                WHERE id = ?
            ");
            $updateStmt->execute([$page_url, $referrer, $cookies_data, $existing['id']]);
            echo json_encode(['success' => true, 'message' => 'Updated', 'action' => 'update']);
            exit;
        } else {
            // No existing session and no meaningful data - skip tracking
            echo json_encode(['success' => true, 'message' => 'Skipped - no data', 'action' => 'skip']);
            exit;
        }
    }
    
    // Find existing record by: 1) email/phone, 2) session_id (last 24h), 3) IP + user agent (last 1 hour)
    $existing = null;
    
    // Priority 1: Check by email or phone
    if ($email || $phone) {
        $checkStmt = $pdo->prepare("SELECT id FROM visitor_data WHERE (email = ? OR phone = ?) ORDER BY created_at DESC LIMIT 1");
        $checkStmt->execute([$email ?: '', $phone ?: '']);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Priority 2: Check by session ID (last 24 hours)
    if (!$existing && $session_id) {
        $checkStmt = $pdo->prepare("
            SELECT id FROM visitor_data 
            WHERE session_id = ? AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ORDER BY created_at DESC LIMIT 1
        ");
        $checkStmt->execute([$session_id]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Priority 3: Check by IP + User Agent (last 1 hour) - only if no email/phone/session
    if (!$existing && $ip_address && $user_agent && !$email && !$phone) {
        $checkStmt = $pdo->prepare("
            SELECT id FROM visitor_data 
            WHERE ip_address = ? AND user_agent = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ORDER BY created_at DESC LIMIT 1
        ");
        $checkStmt->execute([$ip_address, $user_agent]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // If exists, update the record; otherwise insert new
    if ($existing) {
        $stmt = $pdo->prepare("
            UPDATE visitor_data 
            SET 
                name = COALESCE(?, name),
                email = COALESCE(?, email),
                phone = COALESCE(?, phone),
                cookies_data = COALESCE(?, cookies_data),
                page_url = ?,
                referrer = ?,
                ip_address = COALESCE(?, ip_address),
                user_agent = COALESCE(?, user_agent),
                session_id = COALESCE(?, session_id)
            WHERE id = ?
        ");
        $stmt->execute([
            $name, $email, $phone, $cookies_data, 
            $page_url, $referrer, $ip_address, $user_agent, 
            $session_id, $existing['id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Updated', 'action' => 'update']);
    } else {
        // Only insert if there's meaningful data
        if ($hasMeaningfulData || $isFormSubmit) {
            $stmt = $pdo->prepare("
                INSERT INTO visitor_data 
                (ip_address, user_agent, name, email, phone, cookies_data, page_url, referrer, session_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $ip_address, $user_agent, $name, $email, $phone, 
                $cookies_data, $page_url, $referrer, $session_id
            ]);
            echo json_encode(['success' => true, 'message' => 'Inserted', 'action' => 'insert']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Skipped - no data', 'action' => 'skip']);
        }
    }
    
} catch (PDOException $e) {
    error_log("Visitor tracking error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error']);
} catch (Exception $e) {
    error_log("Visitor tracking error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Server error']);
}

