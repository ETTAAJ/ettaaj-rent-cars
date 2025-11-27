<?php
require_once 'config.php';

if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Apply same filters as visitors.php
$search = trim($_GET['search'] ?? '');
$filter = $_GET['filter'] ?? 'all';

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(name LIKE ? OR email LIKE ? OR phone LIKE ? OR ip_address LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($filter === 'with_email') {
    $where[] = "email IS NOT NULL AND email != ''";
} elseif ($filter === 'with_phone') {
    $where[] = "phone IS NOT NULL AND phone != ''";
} elseif ($filter === 'with_name') {
    $where[] = "name IS NOT NULL AND name != ''";
}

$whereClause = !empty($where) ? " WHERE " . implode(' AND ', $where) : "";

$stmt = $pdo->prepare("SELECT * FROM visitor_data $whereClause ORDER BY created_at DESC");
$stmt->execute($params);
$visitors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="visitors_' . date('Y-m-d_His') . '.csv"');

// Output BOM for UTF-8
echo "\xEF\xBB\xBF";

// Open output stream
$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'IP Address', 'Page URL', 'Referrer', 'User Agent', 'Session ID', 'Created At']);

// Add data rows
foreach ($visitors as $visitor) {
    $cookies = $visitor['cookies_data'] ? json_decode($visitor['cookies_data'], true) : null;
    fputcsv($output, [
        $visitor['id'],
        $visitor['name'] ?? '',
        $visitor['email'] ?? '',
        $visitor['phone'] ?? '',
        $visitor['ip_address'] ?? '',
        $visitor['page_url'] ?? '',
        $visitor['referrer'] ?? '',
        $visitor['user_agent'] ?? '',
        $visitor['session_id'] ?? '',
        $visitor['created_at']
    ]);
}

fclose($output);
exit;

