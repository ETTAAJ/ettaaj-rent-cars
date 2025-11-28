<?php
require_once 'config.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

$alert = null;
if (isset($_GET['success'])) {
    $alert = ['type' => 'success', 'msg' => 'Travel Essential saved successfully!'];
} elseif (isset($_GET['deleted'])) {
    $alert = ['type' => 'danger', 'msg' => 'Travel Essential deleted permanently.'];
} elseif (isset($_GET['error'])) {
    $alert = ['type' => 'warning', 'msg' => 'An error occurred.'];
}

// Fetch all travel essentials
$stmt = $pdo->query("SELECT * FROM travel_essentials ORDER BY sort_order ASC, id ASC");
$essentials = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Essentials Management - Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root { --gold: #FFD700; }
    body { background: #36454F; color: white; font-family: 'Inter', sans-serif; }
    
    
    /* Responsive Table */
    @media (max-width: 768px) {
      .table-container {
        display: block;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      table {
        min-width: 800px;
      }
      .mobile-card {
        display: block;
      }
      .mobile-card .table-row {
        display: none;
      }
    }
    
    @media (min-width: 769px) {
      .mobile-card {
        display: none;
      }
    }
    
    .mobile-essential-card {
      background: #36454F;
      border: 1px solid #4A5A66;
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body class="min-h-screen">
<?php include 'header.php'; ?>

<main class="min-h-screen">
<!-- Toast -->
<?php if ($alert): ?>
<div class="fixed top-4 right-4 z-50 bg-<?= $alert['type'] === 'success' ? 'green' : ($alert['type'] === 'danger' ? 'red' : 'yellow') ?>-600 text-white px-6 py-4 rounded-xl shadow-2xl">
  <?= htmlspecialchars($alert['msg']) ?>
</div>
<?php endif; ?>

<!-- Header -->
<div class="bg-[#2C3A44] border-b border-[#4A5A66] shadow-xl">
  <div class="container mx-auto px-6 py-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
      <h2 class="text-2xl font-bold flex items-center gap-3">
        <i class="bi bi-bag-check-fill text-yellow-500"></i> Travel Essentials Management
      </h2>
      <div class="flex gap-3">
        <a href="travel-essentials-create.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl flex items-center gap-2">
          <i class="bi bi-plus-circle"></i> Add New Essential
        </a>
      </div>
    </div>
  </div>
</div>

<div class="container mx-auto px-4 sm:px-6 py-10 max-w-7xl">
  <!-- Travel Essentials List -->
  <div class="bg-[#2C3A44] rounded-2xl shadow-2xl border border-[#4A5A66] overflow-hidden">
    <!-- Desktop Table -->
    <div class="table-container overflow-x-auto hidden md:block">
      <table class="w-full">
        <thead class="bg-[#36454F]">
          <tr>
            <th class="px-4 lg:px-6 py-4 text-left text-sm font-bold text-yellow-500">Icon</th>
            <th class="px-4 lg:px-6 py-4 text-left text-sm font-bold text-yellow-500">Name (EN/AR/FR)</th>
            <th class="px-4 lg:px-6 py-4 text-left text-sm font-bold text-yellow-500">Description</th>
            <th class="px-4 lg:px-6 py-4 text-left text-sm font-bold text-yellow-500">Price</th>
            <th class="px-4 lg:px-6 py-4 text-left text-sm font-bold text-yellow-500">Type</th>
            <th class="px-4 lg:px-6 py-4 text-left text-sm font-bold text-yellow-500">Status</th>
            <th class="px-4 lg:px-6 py-4 text-left text-sm font-bold text-yellow-500">Order</th>
            <th class="px-4 lg:px-6 py-4 text-center text-sm font-bold text-yellow-500">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($essentials)): ?>
            <tr>
              <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                No travel essentials found. <a href="travel-essentials-create.php" class="text-yellow-500 hover:underline">Add one now</a>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($essentials as $essential): 
              $nameEn = $essential['name_en'] ?? $essential['name'] ?? '';
              $nameAr = $essential['name_ar'] ?? '';
              $nameFr = $essential['name_fr'] ?? '';
              $descEn = $essential['description_en'] ?? $essential['description'] ?? '';
            ?>
              <tr class="border-t border-[#4A5A66] hover:bg-[#36454F] transition table-row">
                <td class="px-4 lg:px-6 py-4">
                  <?php if ($essential['icon']): ?>
                    <i class="bi <?= htmlspecialchars($essential['icon']) ?> text-2xl text-yellow-500"></i>
                  <?php else: ?>
                    <i class="bi bi-box text-2xl text-gray-500"></i>
                  <?php endif; ?>
                </td>
                <td class="px-4 lg:px-6 py-4">
                  <div class="font-semibold">EN: <?= htmlspecialchars($nameEn) ?></div>
                  <?php if ($nameAr): ?>
                    <div class="text-sm text-gray-400 mt-1">AR: <?= htmlspecialchars($nameAr) ?></div>
                  <?php endif; ?>
                  <?php if ($nameFr): ?>
                    <div class="text-sm text-gray-400 mt-1">FR: <?= htmlspecialchars($nameFr) ?></div>
                  <?php endif; ?>
                </td>
                <td class="px-4 lg:px-6 py-4 text-gray-300 text-sm"><?= htmlspecialchars($descEn) ?></td>
                <td class="px-4 lg:px-6 py-4 font-bold text-yellow-500" dir="ltr">
                  MAD <?= number_format($essential['price'], 2) ?>
                  <?php if ($essential['per_day']): ?>
                    <span class="text-xs text-gray-400">/day</span>
                  <?php else: ?>
                    <span class="text-xs text-gray-400">/rental</span>
                  <?php endif; ?>
                </td>
                <td class="px-4 lg:px-6 py-4">
                  <span class="px-3 py-1 rounded-full text-xs font-bold <?= $essential['per_day'] ? 'bg-blue-600' : 'bg-purple-600' ?>">
                    <?= $essential['per_day'] ? 'Per Day' : 'One-Time' ?>
                  </span>
                </td>
                <td class="px-4 lg:px-6 py-4">
                  <span class="px-3 py-1 rounded-full text-xs font-bold <?= $essential['is_active'] ? 'bg-green-600' : 'bg-red-600' ?>">
                    <?= $essential['is_active'] ? 'Active' : 'Inactive' ?>
                  </span>
                </td>
                <td class="px-4 lg:px-6 py-4 text-center"><?= $essential['sort_order'] ?></td>
                <td class="px-4 lg:px-6 py-4">
                  <div class="flex justify-center gap-2 flex-wrap">
                    <a href="travel-essentials-edit.php?id=<?= $essential['id'] ?>" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-black font-bold py-2 px-3 rounded-lg text-xs">
                      <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="travel-essentials-delete.php?id=<?= $essential['id'] ?>&csrf=<?= $csrf ?>" 
                       onclick="return confirm('Are you sure you want to delete this travel essential?')"
                       class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-lg text-xs">
                      <i class="bi bi-trash"></i> Delete
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    
    <!-- Mobile Cards -->
    <div class="mobile-card p-4">
      <?php if (empty($essentials)): ?>
        <div class="text-center text-gray-400 py-8">
          No travel essentials found. <a href="travel-essentials-create.php" class="text-yellow-500 hover:underline">Add one now</a>
        </div>
      <?php else: ?>
        <?php foreach ($essentials as $essential): 
          $nameEn = $essential['name_en'] ?? $essential['name'] ?? '';
          $nameAr = $essential['name_ar'] ?? '';
          $nameFr = $essential['name_fr'] ?? '';
          $descEn = $essential['description_en'] ?? $essential['description'] ?? '';
        ?>
          <div class="mobile-essential-card">
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <?php if ($essential['icon']): ?>
                  <i class="bi <?= htmlspecialchars($essential['icon']) ?> text-2xl text-yellow-500"></i>
                <?php else: ?>
                  <i class="bi bi-box text-2xl text-gray-500"></i>
                <?php endif; ?>
                <div>
                  <div class="font-semibold text-white"><?= htmlspecialchars($nameEn) ?></div>
                  <?php if ($nameAr || $nameFr): ?>
                    <div class="text-xs text-gray-400 mt-1">
                      <?php if ($nameAr): ?>AR: <?= htmlspecialchars($nameAr) ?><?php endif; ?>
                      <?php if ($nameAr && $nameFr): ?> • <?php endif; ?>
                      <?php if ($nameFr): ?>FR: <?= htmlspecialchars($nameFr) ?><?php endif; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <span class="px-2 py-1 rounded-full text-xs font-bold <?= $essential['is_active'] ? 'bg-green-600' : 'bg-red-600' ?>">
                <?= $essential['is_active'] ? 'Active' : 'Inactive' ?>
              </span>
            </div>
            <div class="text-sm text-gray-300 mb-3"><?= htmlspecialchars($descEn) ?></div>
            <div class="flex items-center justify-between flex-wrap gap-2">
              <div>
                <span class="font-bold text-yellow-500" dir="ltr">MAD <?= number_format($essential['price'], 2) ?></span>
                <span class="text-xs text-gray-400"><?= $essential['per_day'] ? '/day' : '/rental' ?></span>
                <span class="px-2 py-1 rounded-full text-xs font-bold ml-2 <?= $essential['per_day'] ? 'bg-blue-600' : 'bg-purple-600' ?>">
                  <?= $essential['per_day'] ? 'Per Day' : 'One-Time' ?>
                </span>
              </div>
              <div class="flex gap-2">
                <a href="travel-essentials-edit.php?id=<?= $essential['id'] ?>" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-black font-bold py-2 px-3 rounded-lg text-xs">
                  <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="travel-essentials-delete.php?id=<?= $essential['id'] ?>&csrf=<?= $csrf ?>" 
                   onclick="return confirm('Are you sure?')"
                   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-lg text-xs">
                  <i class="bi bi-trash"></i>
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
</main>

<script>
</script>
</body>
</html>

