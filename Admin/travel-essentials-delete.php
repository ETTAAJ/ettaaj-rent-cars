<?php
require_once 'config.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$csrf = $_GET['csrf'] ?? '';

if ($id <= 0) {
    header('Location: travel-essentials.php?error=1');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
    header('Location: travel-essentials.php?error=1');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM travel_essentials WHERE id = ?");
$stmt->execute([$id]);

header('Location: travel-essentials.php?deleted=1');
exit;

