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

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($csrf, $_POST['csrf'] ?? '')) {
        $errors[] = "Invalid request.";
    } else {
        $name_en = trim($_POST['name_en'] ?? '');
        $name_ar = trim($_POST['name_ar'] ?? '');
        $name_fr = trim($_POST['name_fr'] ?? '');
        $description_en = trim($_POST['description_en'] ?? '');
        $description_ar = trim($_POST['description_ar'] ?? '');
        $description_fr = trim($_POST['description_fr'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $per_day = isset($_POST['per_day']) ? 1 : 0;
        $icon = trim($_POST['icon'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $sort_order = (int)($_POST['sort_order'] ?? 0);

        if (empty($name_en)) $errors['name_en'] = "English name is required.";
        if ($price < 0) $errors['price'] = "Price cannot be negative.";

        if (empty($errors)) {
            $stmt = $pdo->prepare("
                INSERT INTO travel_essentials (name, name_en, name_ar, name_fr, description, description_en, description_ar, description_fr, price, per_day, icon, is_active, sort_order)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $name_en, $name_en, $name_ar, $name_fr,
                $description_en, $description_en, $description_ar, $description_fr,
                $price, $per_day, $icon, $is_active, $sort_order
            ]);
            header("Location: travel-essentials.php?success=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Travel Essential - Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root { --gold: #FFD700; }
    body { background: #36454F; color: white; font-family: 'Inter', sans-serif; }
    
  </style>
</head>
<body class="min-h-screen">
<?php include 'header.php'; ?>

<!-- Day Mode Toggle -->
<button class="day-mode-toggle" id="dayModeToggle" title="Toggle Day/Night Mode">
    <i class="bi bi-sun-fill"></i>
</button>

<!-- Page Header -->
<div class="bg-[#2C3A44] border-b border-[#4A5A66] shadow-xl mt-16">
  <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
      <h2 class="text-xl sm:text-2xl font-bold flex items-center gap-3">
        <i class="bi bi-plus-circle text-yellow-500"></i> <span class="whitespace-nowrap">Add Travel Essential</span>
      </h2>
      <div class="flex gap-2 sm:gap-3">
        <a href="travel-essentials.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-xl text-sm sm:text-base flex items-center gap-2">
          <i class="bi bi-arrow-left"></i> <span class="hidden sm:inline">Back</span>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="container mx-auto px-4 sm:px-6 py-6 sm:py-10 max-w-4xl">
  <div class="bg-[#2C3A44] rounded-2xl shadow-2xl border border-[#4A5A66] p-8">
    <form method="POST" class="space-y-6">
      <input type="hidden" name="csrf" value="<?= $csrf ?>">

      <!-- Language Tabs -->
      <div class="border-b border-[#4A5A66] mb-6">
        <div class="flex gap-4">
          <button type="button" onclick="showLangTab('en')" id="tab-en" class="lang-tab active px-4 py-2 font-bold text-yellow-500 border-b-2 border-yellow-500">English</button>
          <button type="button" onclick="showLangTab('ar')" id="tab-ar" class="lang-tab px-4 py-2 font-bold text-gray-400 border-b-2 border-transparent">Arabic</button>
          <button type="button" onclick="showLangTab('fr')" id="tab-fr" class="lang-tab px-4 py-2 font-bold text-gray-400 border-b-2 border-transparent">French</button>
        </div>
      </div>

      <!-- English Tab -->
      <div id="lang-en" class="lang-content">
        <div>
          <label class="block text-sm font-bold text-yellow-500 mb-2">Name (English) *</label>
          <input type="text" name="name_en" required 
                 value="<?= htmlspecialchars($_POST['name_en'] ?? '') ?>"
                 class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500">
          <?php if (isset($errors['name_en'])): ?>
            <p class="text-red-400 text-sm mt-1"><?= htmlspecialchars($errors['name_en']) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label class="block text-sm font-bold text-yellow-500 mb-2">Description (English)</label>
          <textarea name="description_en" rows="3"
                    class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500"><?= htmlspecialchars($_POST['description_en'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Arabic Tab -->
      <div id="lang-ar" class="lang-content hidden">
        <div>
          <label class="block text-sm font-bold text-yellow-500 mb-2">Name (Arabic)</label>
          <input type="text" name="name_ar" dir="rtl"
                 value="<?= htmlspecialchars($_POST['name_ar'] ?? '') ?>"
                 class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500">
        </div>

        <div>
          <label class="block text-sm font-bold text-yellow-500 mb-2">Description (Arabic)</label>
          <textarea name="description_ar" rows="3" dir="rtl"
                    class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500"><?= htmlspecialchars($_POST['description_ar'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- French Tab -->
      <div id="lang-fr" class="lang-content hidden">
        <div>
          <label class="block text-sm font-bold text-yellow-500 mb-2">Name (French)</label>
          <input type="text" name="name_fr"
                 value="<?= htmlspecialchars($_POST['name_fr'] ?? '') ?>"
                 class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500">
        </div>

        <div>
          <label class="block text-sm font-bold text-yellow-500 mb-2">Description (French)</label>
          <textarea name="description_fr" rows="3"
                    class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500"><?= htmlspecialchars($_POST['description_fr'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-bold text-yellow-500 mb-2">Price (MAD) *</label>
          <input type="number" name="price" step="0.01" min="0" required
                 value="<?= htmlspecialchars($_POST['price'] ?? '0') ?>"
                 class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500">
          <?php if (isset($errors['price'])): ?>
            <p class="text-red-400 text-sm mt-1"><?= htmlspecialchars($errors['price']) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label class="block text-sm font-bold text-yellow-500 mb-2">Sort Order</label>
          <input type="number" name="sort_order" min="0" value="<?= htmlspecialchars($_POST['sort_order'] ?? '0') ?>"
                 class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500">
        </div>
      </div>

      <div>
        <label class="block text-sm font-bold text-yellow-500 mb-2">Icon Class (Bootstrap Icons)</label>
        <input type="text" name="icon" placeholder="e.g., bi-fuel-pump, bi-speedometer2"
               value="<?= htmlspecialchars($_POST['icon'] ?? '') ?>"
               class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500">
        <p class="text-gray-400 text-xs mt-1">Leave empty for no icon. Use Bootstrap Icons class names (e.g., bi-fuel-pump)</p>
      </div>

      <div class="flex gap-6">
        <label class="flex items-center gap-3 cursor-pointer">
          <input type="checkbox" name="per_day" value="1" <?= isset($_POST['per_day']) ? 'checked' : '' ?>
                 class="w-5 h-5 text-yellow-500 rounded focus:ring-yellow-500">
          <span class="text-sm font-semibold">Per Day (if unchecked, it's a one-time fee)</span>
        </label>
      </div>

      <div class="flex gap-6">
        <label class="flex items-center gap-3 cursor-pointer">
          <input type="checkbox" name="is_active" value="1" <?= !isset($_POST['is_active']) || $_POST['is_active'] ? 'checked' : '' ?>
                 class="w-5 h-5 text-yellow-500 rounded focus:ring-yellow-500">
          <span class="text-sm font-semibold">Active</span>
        </label>
      </div>

      <div class="flex gap-4 pt-4">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl">
          <i class="bi bi-check-circle"></i> Create Travel Essential
        </button>
        <a href="travel-essentials.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-xl">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>

<script>
function showLangTab(lang) {
  // Hide all content
  document.querySelectorAll('.lang-content').forEach(el => el.classList.add('hidden'));
  // Show selected content
  document.getElementById('lang-' + lang).classList.remove('hidden');
  
  // Update tabs
  document.querySelectorAll('.lang-tab').forEach(tab => {
    tab.classList.remove('active', 'text-yellow-500', 'border-yellow-500');
    tab.classList.add('text-gray-400', 'border-transparent');
  });
  const activeTab = document.getElementById('tab-' + lang);
  activeTab.classList.add('active', 'text-yellow-500', 'border-yellow-500');
  activeTab.classList.remove('text-gray-400', 'border-transparent');
}

</script>

</body>
</html>

