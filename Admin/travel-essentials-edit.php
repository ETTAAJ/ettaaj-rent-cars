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

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: travel-essentials.php?error=1');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM travel_essentials WHERE id = ?");
    $stmt->execute([$id]);
    $essential = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Edit travel essential error: " . $e->getMessage());
    header('Location: travel-essentials.php?error=1');
    exit;
}

if (!$essential || empty($essential)) {
    header('Location: travel-essentials.php?error=1');
    exit;
}

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
                UPDATE travel_essentials 
                SET name = ?, name_en = ?, name_ar = ?, name_fr = ?, 
                    description = ?, description_en = ?, description_ar = ?, description_fr = ?,
                    price = ?, per_day = ?, icon = ?, is_active = ?, sort_order = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $name_en, $name_en, $name_ar, $name_fr,
                $description_en, $description_en, $description_ar, $description_fr,
                $price, $per_day, $icon, $is_active, $sort_order, $id
            ]);
            header("Location: travel-essentials.php?success=1");
            exit;
        }
    }
} else {
    // Load existing data
    $_POST = $essential;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Travel Essential - Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root { --gold: #FFD700; }
    body { background: #36454F; color: white; font-family: 'Inter', sans-serif; }
    
    /* Day Mode Overrides */
    body.day-mode {
        background: #f8fafc !important;
        color: #1e293b;
    }
    /* Universal rule for all divs with dark backgrounds - ensure white */
    body.day-mode div.bg-\[#2C3A44\],
    body.day-mode div.bg-\[#36454F\],
    body.day-mode div.bg-gray-800,
    body.day-mode div.bg-gray-700,
    body.day-mode div.bg-gray-900,
    body.day-mode .bg-\[#2C3A44\].rounded,
    body.day-mode .bg-\[#2C3A44\].rounded-xl,
    body.day-mode .bg-\[#2C3A44\].rounded-2xl,
    body.day-mode .bg-\[#2C3A44\].rounded-lg,
    body.day-mode .bg-\[#36454F\].rounded,
    body.day-mode .bg-\[#36454F\].rounded-xl,
    body.day-mode .bg-\[#36454F\].rounded-2xl,
    body.day-mode .bg-\[#36454F\].rounded-lg {
        background: #ffffff !important;
    }
    body.day-mode .bg-\[#2C3A44\] {
        background: #ffffff !important;
        color: #1e293b !important;
    }
    body.day-mode .bg-\[#36454F\] {
        background: #ffffff !important;
    }
    body.day-mode div.bg-\[#36454F\] {
        background: #ffffff !important;
    }
    body.day-mode div.bg-\[#2C3A44\] {
        background: #ffffff !important;
    }
    /* Text Colors in Light Mode */
    body.day-mode .text-white,
    body.day-mode h1.text-white,
    body.day-mode h2.text-white,
    body.day-mode h3.text-white,
    body.day-mode p.text-white,
    body.day-mode span.text-white,
    body.day-mode div.text-white {
        color: #1e293b !important;
    }
    body.day-mode .text-gray-400,
    body.day-mode .text-gray-500 {
        color: #64748b !important;
    }
    body.day-mode .text-gray-300 {
        color: #475569 !important;
    }
    body.day-mode h1,
    body.day-mode h2,
    body.day-mode h3,
    body.day-mode h4,
    body.day-mode h5,
    body.day-mode h6 {
        color: #1e293b !important;
    }
    body.day-mode p:not(.text-green-400):not(.text-blue-400):not(.text-red-400):not(.text-yellow-500),
    body.day-mode span:not(.text-green-400):not(.text-blue-400):not(.text-red-400):not(.text-yellow-500),
    body.day-mode div:not(.bg-green-600):not(.bg-red-600):not(.bg-yellow-600) {
        color: #1e293b !important;
    }
    /* Ensure text in white backgrounds is dark */
    body.day-mode .bg-\[#2C3A44\] *:not(.text-green-400):not(.text-blue-400):not(.text-red-400):not(.text-yellow-500):not(.text-yellow-400),
    body.day-mode .bg-\[#36454F\] *:not(.text-green-400):not(.text-blue-400):not(.text-red-400):not(.text-yellow-500):not(.text-yellow-400) {
        color: #1e293b !important;
    }
    /* Page Headers */
    body.day-mode .bg-\[#2C3A44\].border-b h2,
    body.day-mode .bg-\[#2C3A44\].border-b * {
        color: #1e293b !important;
    }
    body.day-mode .bg-\[#2C3A44\].border-b .text-yellow-500 {
        color: #d97706 !important;
    }
    /* Form Labels */
    body.day-mode .text-yellow-500 {
        color: #d97706 !important;
    }
    body.day-mode .text-gray-300 {
        color: #475569 !important;
    }
    body.day-mode .border-\[#4A5A66\] {
        border-color: #e2e8f0 !important;
    }
    body.day-mode input,
    body.day-mode select,
    body.day-mode textarea {
        background: #ffffff !important;
        color: #1e293b !important;
        border-color: #e2e8f0 !important;
    }
    body.day-mode input:focus,
    body.day-mode select:focus,
    body.day-mode textarea:focus {
        border-color: #FFB22C !important;
        background: #ffffff !important;
    }
    body.day-mode .bg-green-600,
    body.day-mode .bg-gray-600 {
        color: #ffffff !important;
    }
    body.day-mode .bg-gray-600:hover {
        background: #475569 !important;
    }
    body.day-mode .bg-green-600:hover {
        background: #059669 !important;
    }
    
    /* Day Mode Toggle Button */
    .day-mode-toggle {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #FFD700;
        color: #000;
        border: none;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(255,215,0,.4);
        cursor: pointer;
        transition: all .3s ease;
    }
    .day-mode-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 30px rgba(255,215,0,.5);
    }
    .day-mode-toggle i {
        transition: transform .3s;
    }
    .day-mode-toggle.active i {
        transform: rotate(180deg);
    }
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
        <i class="bi bi-pencil text-yellow-500"></i> <span class="whitespace-nowrap">Edit Travel Essential</span>
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
                 value="<?= htmlspecialchars($_POST['name_en'] ?? $_POST['name'] ?? '') ?>"
                 class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500">
          <?php if (isset($errors['name_en'])): ?>
            <p class="text-red-400 text-sm mt-1"><?= htmlspecialchars($errors['name_en']) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label class="block text-sm font-bold text-yellow-500 mb-2">Description (English)</label>
          <textarea name="description_en" rows="3"
                    class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500"><?= htmlspecialchars($_POST['description_en'] ?? $_POST['description'] ?? '') ?></textarea>
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
          <input type="checkbox" name="per_day" value="1" <?= ($_POST['per_day'] ?? 0) ? 'checked' : '' ?>
                 class="w-5 h-5 text-yellow-500 rounded focus:ring-yellow-500">
          <span class="text-sm font-semibold">Per Day (if unchecked, it's a one-time fee)</span>
        </label>
      </div>

      <div class="flex gap-6">
        <label class="flex items-center gap-3 cursor-pointer">
          <input type="checkbox" name="is_active" value="1" <?= ($_POST['is_active'] ?? 0) ? 'checked' : '' ?>
                 class="w-5 h-5 text-yellow-500 rounded focus:ring-yellow-500">
          <span class="text-sm font-semibold">Active</span>
        </label>
      </div>

      <div class="flex gap-4 pt-4">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl">
          <i class="bi bi-check-circle"></i> Update Travel Essential
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

// Day/Night Mode Toggle
const toggleBtn = document.getElementById('dayModeToggle');
const body = document.body;
const icon = toggleBtn.querySelector('i');

if (localStorage.getItem('dayMode') === 'true') {
    body.classList.add('day-mode');
    icon.classList.replace('bi-sun-fill', 'bi-moon-fill');
    toggleBtn.classList.add('active');
}

toggleBtn.addEventListener('click', () => {
    body.classList.toggle('day-mode');
    const isDay = body.classList.contains('day-mode');
    
    if (isDay) {
        icon.classList.replace('bi-sun-fill', 'bi-moon-fill');
    } else {
        icon.classList.replace('bi-moon-fill', 'bi-sun-fill');
    }
    toggleBtn.classList.toggle('active', isDay);
    localStorage.setItem('dayMode', isDay);
});
</script>

</body>
</html>

