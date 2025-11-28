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
            --bg-dark: #36454F;
            --card: #2C3A44;
            --border: #4A5A66;
        }
        body {
            background: var(--bg-dark);
            color: #fff;
            font-family: 'Inter', sans-serif;
        }
        body.day-mode .bg-\[#36454F\].rounded-lg {
            background: #ffffff !important;
        }
        body.day-mode pre.bg-\[#36454F\] {
            background: #f8fafc !important;
        }
        body.day-mode .text-blue-400 {
            color: #2563eb !important;
        }
        body.day-mode .text-green-400 {
            color: #059669 !important;
        }
        body.day-mode .text-yellow-400 {
            color: #d97706 !important;
        }
        body.day-mode .text-purple-400 {
            color: #9333ea !important;
        }
        body.day-mode button.bg-blue-600:hover,
        body.day-mode a.bg-blue-600:hover {
            background: #2563eb !important;
        }
        body.day-mode button.bg-green-600:hover,
        body.day-mode a.bg-green-600:hover {
            background: #059669 !important;
        }
        body.day-mode .bg-\[#36454F\].hover\:bg-\[#4A5A66\] {
            background: #e2e8f0 !important;
            color: #1e293b !important;
        }
        body.day-mode .bg-\[#36454F\].hover\:bg-\[#4A5A66\]:hover {
            background: #cbd5e1 !important;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-900 text-white">
<?php include 'header.php'; ?>

<main class="min-h-screen">
    <!-- Page Header -->
    <div class="bg-[#2C3A44] border-b border-[#4A5A66] shadow-xl">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <h2 class="text-xl sm:text-2xl font-bold flex items-center gap-3">
                    <i class="bi bi-people-fill text-yellow-500"></i> <span class="whitespace-nowrap">Visitor Data Collection</span>
                </h2>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8 max-w-7xl">

        <!-- Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 sm:mb-8">
            <div class="bg-[#2C3A44] p-4 sm:p-6 rounded-xl border border-[#4A5A66] shadow-lg hover:shadow-xl transition-shadow">
                <div class="text-gray-400 text-xs sm:text-sm mb-2">Total Records</div>
                <div class="text-2xl sm:text-3xl font-bold text-yellow-400"><?= number_format($stats['total']) ?></div>
            </div>
            <div class="bg-[#2C3A44] p-4 sm:p-6 rounded-xl border border-[#4A5A66] shadow-lg hover:shadow-xl transition-shadow">
                <div class="text-gray-400 text-xs sm:text-sm mb-2">Unique Emails</div>
                <div class="text-2xl sm:text-3xl font-bold text-green-400"><?= number_format($stats['unique_emails']) ?></div>
            </div>
            <div class="bg-[#2C3A44] p-4 sm:p-6 rounded-xl border border-[#4A5A66] shadow-lg hover:shadow-xl transition-shadow">
                <div class="text-gray-400 text-xs sm:text-sm mb-2">Unique Phones</div>
                <div class="text-2xl sm:text-3xl font-bold text-blue-400"><?= number_format($stats['unique_phones']) ?></div>
            </div>
            <div class="bg-[#2C3A44] p-4 sm:p-6 rounded-xl border border-[#4A5A66] shadow-lg hover:shadow-xl transition-shadow">
                <div class="text-gray-400 text-xs sm:text-sm mb-2">Unique IPs</div>
                <div class="text-2xl sm:text-3xl font-bold text-purple-400"><?= number_format($stats['unique_ips']) ?></div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-[#2C3A44] rounded-xl shadow-xl p-4 sm:p-6 mb-6 border border-[#4A5A66]">
            <form method="GET" class="flex flex-col sm:flex-row flex-wrap gap-4 items-end">
                <div class="flex-1 w-full sm:min-w-[200px]">
                    <label class="block text-xs sm:text-sm text-gray-400 mb-2">Search</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                           placeholder="Name, Email, Phone, IP..."
                           class="w-full px-4 py-2 bg-[#36454F] border border-[#4A5A66] rounded-lg text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div class="w-full sm:w-auto sm:min-w-[150px]">
                    <label class="block text-xs sm:text-sm text-gray-400 mb-2">Filter</label>
                    <select name="filter" class="w-full px-4 py-2 bg-[#36454F] border border-[#4A5A66] rounded-lg text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All Records</option>
                        <option value="with_email" <?= $filter === 'with_email' ? 'selected' : '' ?>>With Email</option>
                        <option value="with_phone" <?= $filter === 'with_phone' ? 'selected' : '' ?>>With Phone</option>
                        <option value="with_name" <?= $filter === 'with_name' ? 'selected' : '' ?>>With Name</option>
                    </select>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-black font-bold rounded-lg transition text-sm sm:text-base flex items-center justify-center gap-2">
                        <i class="bi bi-search"></i> <span>Search</span>
                    </button>
                    <a href="visitors.php" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition text-sm sm:text-base flex items-center justify-center gap-2">
                        <i class="bi bi-x-circle"></i> <span class="hidden sm:inline">Clear</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Export Button -->
        <div class="mb-4 sm:mb-6">
            <a href="export_visitors.php?<?= http_build_query($_GET) ?>" 
               class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition text-sm sm:text-base shadow-lg hover:shadow-xl">
                <i class="bi bi-download"></i> <span>Export to CSV</span>
            </a>
        </div>

        <!-- Visitors Table -->
        <div class="bg-[#2C3A44] rounded-xl shadow-xl overflow-hidden border border-[#4A5A66]">
            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#36454F]">
                        <tr>
                            <th class="px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-yellow-500">ID</th>
                            <th class="px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-yellow-500">Name</th>
                            <th class="px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-yellow-500">Email</th>
                            <th class="px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-yellow-500">Phone</th>
                            <th class="px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-yellow-500">IP Address</th>
                            <th class="px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-yellow-500">Page URL</th>
                            <th class="px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold text-yellow-500">Date</th>
                            <th class="px-4 lg:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm font-bold text-yellow-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($visitors)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-400">No visitor data found</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($visitors as $visitor): ?>
                        <tr class="border-t border-[#4A5A66] hover:bg-[#36454F] transition">
                            <td class="px-4 lg:px-6 py-3 sm:py-4 text-sm"><?= $visitor['id'] ?></td>
                            <td class="px-4 lg:px-6 py-3 sm:py-4">
                                <?= $visitor['name'] ? htmlspecialchars($visitor['name']) : '<span class="text-gray-500">-</span>' ?>
                            </td>
                            <td class="px-4 lg:px-6 py-3 sm:py-4">
                                <?php if ($visitor['email']): ?>
                                    <a href="mailto:<?= htmlspecialchars($visitor['email']) ?>" class="text-blue-400 hover:underline text-sm break-all">
                                        <?= htmlspecialchars($visitor['email']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 lg:px-6 py-3 sm:py-4">
                                <?php if ($visitor['phone']): ?>
                                    <a href="tel:<?= htmlspecialchars($visitor['phone']) ?>" class="text-green-400 hover:underline text-sm">
                                        <?= htmlspecialchars($visitor['phone']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 lg:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-400"><?= htmlspecialchars($visitor['ip_address'] ?? '-') ?></td>
                            <td class="px-4 lg:px-6 py-3 sm:py-4">
                                <?php if ($visitor['page_url']): ?>
                                    <a href="<?= htmlspecialchars($visitor['page_url']) ?>" target="_blank" 
                                       class="text-blue-400 hover:underline text-xs sm:text-sm truncate block max-w-xs">
                                        <?= htmlspecialchars($visitor['page_url']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 lg:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-400">
                                <?= date('Y-m-d H:i', strtotime($visitor['created_at'])) ?>
                            </td>
                            <td class="px-4 lg:px-6 py-3 sm:py-4 text-center">
                                <button onclick="viewDetails(<?= $visitor['id'] ?>)" 
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 rounded-lg text-xs sm:text-sm transition font-semibold">
                                    <i class="bi bi-eye"></i> Details
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile Cards -->
            <div class="lg:hidden p-4 space-y-4">
                <?php if (empty($visitors)): ?>
                    <div class="text-center text-gray-400 py-8">No visitor data found</div>
                <?php else: ?>
                    <?php foreach ($visitors as $visitor): ?>
                        <div class="bg-[#36454F] rounded-lg p-4 border border-[#4A5A66] space-y-3">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="text-xs text-gray-400 mb-1">ID: <?= $visitor['id'] ?></div>
                                    <div class="font-semibold text-white">
                                        <?= $visitor['name'] ? htmlspecialchars($visitor['name']) : '<span class="text-gray-500">No Name</span>' ?>
                                    </div>
                                </div>
                                <button onclick="viewDetails(<?= $visitor['id'] ?>)" 
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 rounded-lg text-xs transition font-semibold whitespace-nowrap">
                                    <i class="bi bi-eye"></i> Details
                                </button>
                            </div>
                            
                            <?php if ($visitor['email']): ?>
                                <div>
                                    <div class="text-xs text-gray-400 mb-1">Email</div>
                                    <a href="mailto:<?= htmlspecialchars($visitor['email']) ?>" class="text-blue-400 hover:underline text-sm break-all">
                                        <?= htmlspecialchars($visitor['email']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($visitor['phone']): ?>
                                <div>
                                    <div class="text-xs text-gray-400 mb-1">Phone</div>
                                    <a href="tel:<?= htmlspecialchars($visitor['phone']) ?>" class="text-green-400 hover:underline text-sm">
                                        <?= htmlspecialchars($visitor['phone']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div>
                                <div class="text-xs text-gray-400 mb-1">IP Address</div>
                                <div class="text-sm text-gray-300"><?= htmlspecialchars($visitor['ip_address'] ?? '-') ?></div>
                            </div>
                            
                            <?php if ($visitor['page_url']): ?>
                                <div>
                                    <div class="text-xs text-gray-400 mb-1">Page URL</div>
                                    <a href="<?= htmlspecialchars($visitor['page_url']) ?>" target="_blank" 
                                       class="text-blue-400 hover:underline text-xs break-all block">
                                        <?= htmlspecialchars($visitor['page_url']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div>
                                <div class="text-xs text-gray-400 mb-1">Date</div>
                                <div class="text-sm text-gray-300"><?= date('Y-m-d H:i', strtotime($visitor['created_at'])) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="px-4 sm:px-6 py-4 border-t border-[#4A5A66] flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-gray-400 text-xs sm:text-sm text-center sm:text-left">
                    Showing <?= $offset + 1 ?> to <?= min($offset + $perPage, $totalRecords) ?> of <?= $totalRecords ?> records
                </div>
                <div class="flex gap-2">
                    <?php if ($page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" 
                       class="px-4 py-2 bg-[#36454F] hover:bg-[#4A5A66] border border-[#4A5A66] rounded-lg transition text-sm sm:text-base font-semibold">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                    <?php endif; ?>
                    <?php if ($page < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" 
                       class="px-4 py-2 bg-[#36454F] hover:bg-[#4A5A66] border border-[#4A5A66] rounded-lg transition text-sm sm:text-base font-semibold">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4">
        <div class="bg-[#2C3A44] rounded-xl p-4 sm:p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-[#4A5A66] shadow-2xl">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-yellow-400">Visitor Details</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition p-2">
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
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div><strong class="text-gray-400 text-xs sm:text-sm">ID:</strong> <span class="text-white text-sm sm:text-base">${data.id}</span></div>
                            <div><strong class="text-gray-400 text-xs sm:text-sm">IP Address:</strong> <span class="text-white text-sm sm:text-base">${data.ip_address || '-'}</span></div>
                            <div><strong class="text-gray-400 text-xs sm:text-sm">Name:</strong> <span class="text-white text-sm sm:text-base">${data.name || '-'}</span></div>
                            <div><strong class="text-gray-400 text-xs sm:text-sm">Email:</strong> <span class="text-blue-400 text-sm sm:text-base break-all">${data.email || '-'}</span></div>
                            <div><strong class="text-gray-400 text-xs sm:text-sm">Phone:</strong> <span class="text-green-400 text-sm sm:text-base">${data.phone || '-'}</span></div>
                            <div><strong class="text-gray-400 text-xs sm:text-sm">Session ID:</strong> <span class="text-white text-xs sm:text-sm break-all">${data.session_id || '-'}</span></div>
                            <div class="col-span-1 sm:col-span-2"><strong class="text-gray-400 text-xs sm:text-sm">Page URL:</strong> <a href="${data.page_url || '#'}" target="_blank" class="text-blue-400 hover:underline break-all text-sm sm:text-base block mt-1">${data.page_url || '-'}</a></div>
                            <div class="col-span-1 sm:col-span-2"><strong class="text-gray-400 text-xs sm:text-sm">Referrer:</strong> <span class="text-white break-all text-sm sm:text-base block mt-1">${data.referrer || '-'}</span></div>
                            <div class="col-span-1 sm:col-span-2"><strong class="text-gray-400 text-xs sm:text-sm">User Agent:</strong> <span class="text-white text-xs sm:text-sm break-all block mt-1">${data.user_agent || '-'}</span></div>
                            <div class="col-span-1 sm:col-span-2"><strong class="text-gray-400 text-xs sm:text-sm">Cookies:</strong> <pre class="bg-[#36454F] p-3 rounded-lg text-xs text-gray-300 overflow-auto max-h-40 mt-1 border border-[#4A5A66]">${data.cookies_data ? JSON.stringify(JSON.parse(data.cookies_data), null, 2) : '-'}</pre></div>
                            <div class="col-span-1 sm:col-span-2"><strong class="text-gray-400 text-xs sm:text-sm">Created At:</strong> <span class="text-white text-sm sm:text-base">${data.created_at}</span></div>
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
</main>
</body>
</html>

