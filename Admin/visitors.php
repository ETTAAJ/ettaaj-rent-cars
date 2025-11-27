<?php
require_once 'config.php';

/* -------------------------------------------------
   1. SESSION PROTECTION
   ------------------------------------------------- */
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

/* -------------------------------------------------
   2. FILTERS & PAGINATION
   ------------------------------------------------- */
$search = trim($_GET['search'] ?? '');
$filter = $_GET['filter'] ?? 'all'; // all, with_email, with_phone, with_name
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 50;
$offset = ($page - 1) * $perPage;

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

// Get total count
$countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM visitor_data $whereClause");
$countStmt->execute($params);
$totalRecords = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $perPage);

// Get visitors
$sql = "SELECT * FROM visitor_data $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $pdo->prepare($sql);
$params[] = $perPage;
$params[] = $offset;
$stmt->execute($params);
$visitors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$statsStmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(DISTINCT email) as unique_emails,
    COUNT(DISTINCT phone) as unique_phones,
    COUNT(DISTINCT ip_address) as unique_ips
    FROM visitor_data
    WHERE email IS NOT NULL OR phone IS NOT NULL");
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Data - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-color: #FFB22C;
            --bg-dark: #1a1a1a;
            --card: #2a2a2a;
            --border: #3a3a3a;
        }
        body {
            background: var(--bg-dark);
            color: #fff;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-yellow-400">
                <i class="bi bi-people-fill"></i> Visitor Data Collection
            </h1>
            <a href="index.php" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition">
                <i class="bi bi-arrow-left"></i> Back to Cars
            </a>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
                <div class="text-gray-400 text-sm mb-2">Total Records</div>
                <div class="text-3xl font-bold text-yellow-400"><?= number_format($stats['total']) ?></div>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
                <div class="text-gray-400 text-sm mb-2">Unique Emails</div>
                <div class="text-3xl font-bold text-green-400"><?= number_format($stats['unique_emails']) ?></div>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
                <div class="text-gray-400 text-sm mb-2">Unique Phones</div>
                <div class="text-3xl font-bold text-blue-400"><?= number_format($stats['unique_phones']) ?></div>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
                <div class="text-gray-400 text-sm mb-2">Unique IPs</div>
                <div class="text-3xl font-bold text-purple-400"><?= number_format($stats['unique_ips']) ?></div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-6 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm text-gray-400 mb-2">Search</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                           placeholder="Name, Email, Phone, IP..."
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-yellow-400">
                </div>
                <div class="min-w-[150px]">
                    <label class="block text-sm text-gray-400 mb-2">Filter</label>
                    <select name="filter" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-yellow-400">
                        <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All Records</option>
                        <option value="with_email" <?= $filter === 'with_email' ? 'selected' : '' ?>>With Email</option>
                        <option value="with_phone" <?= $filter === 'with_phone' ? 'selected' : '' ?>>With Phone</option>
                        <option value="with_name" <?= $filter === 'with_name' ? 'selected' : '' ?>>With Name</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-black font-bold rounded transition">
                    <i class="bi bi-search"></i> Search
                </button>
                <a href="visitors.php" class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded transition">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
            </form>
        </div>

        <!-- Export Button -->
        <div class="mb-4">
            <a href="export_visitors.php?<?= http_build_query($_GET) ?>" 
               class="inline-block px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition">
                <i class="bi bi-download"></i> Export to CSV
            </a>
        </div>

        <!-- Visitors Table -->
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left">ID</th>
                            <th class="px-6 py-4 text-left">Name</th>
                            <th class="px-6 py-4 text-left">Email</th>
                            <th class="px-6 py-4 text-left">Phone</th>
                            <th class="px-6 py-4 text-left">IP Address</th>
                            <th class="px-6 py-4 text-left">Page URL</th>
                            <th class="px-6 py-4 text-left">Date</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($visitors)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-400">No visitor data found</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($visitors as $visitor): ?>
                        <tr class="border-t border-gray-700 hover:bg-gray-750">
                            <td class="px-6 py-4"><?= $visitor['id'] ?></td>
                            <td class="px-6 py-4">
                                <?= $visitor['name'] ? htmlspecialchars($visitor['name']) : '<span class="text-gray-500">-</span>' ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($visitor['email']): ?>
                                    <a href="mailto:<?= htmlspecialchars($visitor['email']) ?>" class="text-blue-400 hover:underline">
                                        <?= htmlspecialchars($visitor['email']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($visitor['phone']): ?>
                                    <a href="tel:<?= htmlspecialchars($visitor['phone']) ?>" class="text-green-400 hover:underline">
                                        <?= htmlspecialchars($visitor['phone']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400"><?= htmlspecialchars($visitor['ip_address'] ?? '-') ?></td>
                            <td class="px-6 py-4">
                                <?php if ($visitor['page_url']): ?>
                                    <a href="<?= htmlspecialchars($visitor['page_url']) ?>" target="_blank" 
                                       class="text-blue-400 hover:underline text-sm truncate block max-w-xs">
                                        <?= htmlspecialchars($visitor['page_url']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                <?= date('Y-m-d H:i', strtotime($visitor['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="viewDetails(<?= $visitor['id'] ?>)" 
                                        class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm transition">
                                    <i class="bi bi-eye"></i> Details
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="px-6 py-4 border-t border-gray-700 flex justify-between items-center">
                <div class="text-gray-400 text-sm">
                    Showing <?= $offset + 1 ?> to <?= min($offset + $perPage, $totalRecords) ?> of <?= $totalRecords ?> records
                </div>
                <div class="flex gap-2">
                    <?php if ($page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" 
                       class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded transition">Previous</a>
                    <?php endif; ?>
                    <?php if ($page < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" 
                       class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded transition">Next</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center">
        <div class="bg-gray-800 rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-yellow-400">Visitor Details</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                    <i class="bi bi-x-circle text-2xl"></i>
                </button>
            </div>
            <div id="modalContent" class="space-y-4"></div>
        </div>
    </div>

    <script>
        function viewDetails(id) {
            fetch(`get_visitor_details.php?id=${id}`)
                .then(r => r.json())
                .then(data => {
                    const content = `
                        <div class="grid grid-cols-2 gap-4">
                            <div><strong class="text-gray-400">ID:</strong> <span class="text-white">${data.id}</span></div>
                            <div><strong class="text-gray-400">IP Address:</strong> <span class="text-white">${data.ip_address || '-'}</span></div>
                            <div><strong class="text-gray-400">Name:</strong> <span class="text-white">${data.name || '-'}</span></div>
                            <div><strong class="text-gray-400">Email:</strong> <span class="text-blue-400">${data.email || '-'}</span></div>
                            <div><strong class="text-gray-400">Phone:</strong> <span class="text-green-400">${data.phone || '-'}</span></div>
                            <div><strong class="text-gray-400">Session ID:</strong> <span class="text-white text-xs">${data.session_id || '-'}</span></div>
                            <div class="col-span-2"><strong class="text-gray-400">Page URL:</strong> <a href="${data.page_url || '#'}" target="_blank" class="text-blue-400 hover:underline break-all">${data.page_url || '-'}</a></div>
                            <div class="col-span-2"><strong class="text-gray-400">Referrer:</strong> <span class="text-white break-all">${data.referrer || '-'}</span></div>
                            <div class="col-span-2"><strong class="text-gray-400">User Agent:</strong> <span class="text-white text-sm break-all">${data.user_agent || '-'}</span></div>
                            <div class="col-span-2"><strong class="text-gray-400">Cookies:</strong> <pre class="bg-gray-900 p-3 rounded text-xs text-gray-300 overflow-auto max-h-40">${data.cookies_data ? JSON.stringify(JSON.parse(data.cookies_data), null, 2) : '-'}</pre></div>
                            <div class="col-span-2"><strong class="text-gray-400">Created At:</strong> <span class="text-white">${data.created_at}</span></div>
                        </div>
                    `;
                    document.getElementById('modalContent').innerHTML = content;
                    document.getElementById('detailsModal').classList.remove('hidden');
                    document.getElementById('detailsModal').classList.add('flex');
                });
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
            document.getElementById('detailsModal').classList.remove('flex');
        }
    </script>
</body>
</html>

