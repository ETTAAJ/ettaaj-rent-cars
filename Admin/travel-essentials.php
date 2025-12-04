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
    
    
    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
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
  <!-- Travel Essentials Cards -->
  <?php if (empty($essentials)): ?>
    <div class="bg-[#2C3A44] rounded-2xl shadow-2xl border border-[#4A5A66] p-8 text-center">
      <p class="text-gray-400 text-lg mb-4">No travel essentials found.</p>
      <a href="travel-essentials-create.php" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl">
        <i class="bi bi-plus-circle"></i> Add New Essential
      </a>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($essentials as $essential): 
        $nameEn = $essential['name_en'] ?? $essential['name'] ?? '';
        $nameAr = $essential['name_ar'] ?? '';
        $nameFr = $essential['name_fr'] ?? '';
        $descEn = $essential['description_en'] ?? $essential['description'] ?? '';
      ?>
        <div class="bg-[#2C3A44] rounded-2xl shadow-2xl border border-[#4A5A66] p-6 hover:shadow-yellow-500/20 transition-all duration-300 transform hover:-translate-y-2 hover:scale-[1.02] flex flex-col">
          <!-- Card Header -->
          <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3 flex-1">
              <?php if ($essential['icon']): ?>
                <i class="bi <?= htmlspecialchars($essential['icon']) ?> text-3xl text-yellow-500"></i>
              <?php else: ?>
                <i class="bi bi-box text-3xl text-gray-500"></i>
              <?php endif; ?>
              <div class="flex-1 min-w-0">
                <h3 class="font-bold text-white text-lg mb-1 truncate"><?= htmlspecialchars($nameEn) ?></h3>
                <?php if ($nameAr || $nameFr): ?>
                  <div class="text-xs text-gray-400 space-y-1">
                    <?php if ($nameAr): ?>
                      <div>AR: <span class="text-gray-300"><?= htmlspecialchars($nameAr) ?></span></div>
                    <?php endif; ?>
                    <?php if ($nameFr): ?>
                      <div>FR: <span class="text-gray-300"><?= htmlspecialchars($nameFr) ?></span></div>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap <?= $essential['is_active'] ? 'bg-green-600' : 'bg-red-600' ?>">
              <?= $essential['is_active'] ? 'Active' : 'Inactive' ?>
            </span>
          </div>

          <!-- Description -->
          <?php if ($descEn): ?>
            <p class="text-sm text-gray-300 mb-4 line-clamp-2 flex-1"><?= htmlspecialchars($descEn) ?></p>
          <?php endif; ?>

          <!-- Price & Type -->
          <div class="flex items-center justify-between flex-wrap gap-3 mb-4 pb-4 border-b border-[#4A5A66]">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="font-bold text-yellow-500 text-xl" dir="ltr">MAD <?= number_format($essential['price'], 2) ?></span>
              <span class="text-xs text-gray-400"><?= $essential['per_day'] ? '/day' : '/rental' ?></span>
            </div>
            <div class="flex items-center gap-2">
              <span class="px-3 py-1 rounded-full text-xs font-bold <?= $essential['per_day'] ? 'bg-blue-600' : 'bg-purple-600' ?>">
                <?= $essential['per_day'] ? 'Per Day' : 'One-Time' ?>
              </span>
              <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-600 text-gray-300">
                Order: <?= $essential['sort_order'] ?>
              </span>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-2 mt-auto">
            <a href="travel-essentials-edit.php?id=<?= $essential['id'] ?>" 
               class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-black font-bold py-2.5 px-4 rounded-lg text-sm text-center transition">
              <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="travel-essentials-delete.php?id=<?= $essential['id'] ?>&csrf=<?= $csrf ?>" 
               onclick="return confirm('Are you sure you want to delete this travel essential?')"
               class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded-lg text-sm text-center transition">
              <i class="bi bi-trash"></i> Delete
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</main>

<script>
</script>
</body>
</html>

