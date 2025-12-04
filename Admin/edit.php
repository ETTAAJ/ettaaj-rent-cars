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
   2. CSRF TOKEN
   ------------------------------------------------- */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

/* -------------------------------------------------
   3. GET CAR BY ID
   ------------------------------------------------- */
// Get and validate ID from URL
$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if (empty($id) || !is_numeric($id)) {
    header('Location: index.php?error=1');
    exit;
}
$id = (int)$id;

if ($id <= 0) {
    header('Location: index.php?error=1');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Edit car error (ID: $id): " . $e->getMessage());
    header('Location: index.php?error=1');
    exit;
}

if (!$car || empty($car) || !isset($car['id']) || (int)$car['id'] !== $id) {
    error_log("Car not found or ID mismatch (ID: $id)");
    header('Location: index.php?error=1');
    exit;
}

/* -------------------------------------------------
   4. HANDLE FORM SUBMISSION
   ------------------------------------------------- */
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!hash_equals($csrf, $_POST['csrf'] ?? '')) {
        $errors[] = "Invalid request. Please try again.";
    } else {
        // Ensure ID from URL matches POST ID (if provided)
        $postId = (int)($_POST['id'] ?? 0);
        if ($postId > 0 && $postId !== $id) {
            $errors[] = "Invalid car ID. Please try again.";
        }
        $name        = trim($_POST['name'] ?? '');
        $seats       = (int)($_POST['seats'] ?? 0);
        $bags        = (int)($_POST['bags'] ?? 0);
        $gear        = $_POST['gear'] ?? '';
        $fuel        = $_POST['fuel'] ?? '';
        $price_day   = (float)($_POST['price_day'] ?? 0);
        $price_week  = (float)($_POST['price_week'] ?? 0);
        $price_month = (float)($_POST['price_month'] ?? 0);
        $discount    = (int)($_POST['discount'] ?? 0);
        $insurance_basic_price    = (float)($_POST['insurance_basic_price'] ?? 0);
        $insurance_smart_price    = (float)($_POST['insurance_smart_price'] ?? 0);
        $insurance_premium_price  = (float)($_POST['insurance_premium_price'] ?? 0);
        $insurance_basic_deposit  = (float)($_POST['insurance_basic_deposit'] ?? 0);
        $insurance_smart_deposit  = (float)($_POST['insurance_smart_deposit'] ?? 0);
        $insurance_premium_deposit = (float)($_POST['insurance_premium_deposit'] ?? 0);

        // Validation
        if (empty($name)) $errors[] = "Car name is required.";
        if ($seats < 1) $errors[] = "Seats must be at least 1.";
        if ($bags < 0) $errors[] = "Bags cannot be negative.";
        if (!in_array($gear, ['Manual', 'Automatic'])) $errors[] = "Invalid gear type.";
        if (!in_array($fuel, ['Petrol', 'Diesel'])) $errors[] = "Invalid fuel type.";
        if ($price_day <= 0) $errors[] = "Price per day must be positive.";
        if ($discount < 0 || $discount > 100) $errors[] = "Discount must be between 0 and 100.";

        $image = $car['image']; // Default: keep old

        // === NEW IMAGE UPLOAD ===
        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {
                $errors[] = "Only JPG, JPEG, PNG allowed.";
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $errors[] = "Image must be under 2 MB.";
            } elseif (!getimagesize($file['tmp_name'])) {
                $errors[] = "Invalid image file.";
            } else {
                // Delete old image
                if ($car['image']) {
                    $oldPath = __DIR__ . '/../uploads/' . $car['image'];
                    if (file_exists($oldPath)) @unlink($oldPath);
                }

                $baseName = preg_replace('/[^a-zA-Z0-9\s-]/', '', $name);
                $baseName = trim(preg_replace('/\s+/', ' ', $baseName));
                $fileName = $baseName . '.' . $ext;

                $counter = 1;
                $targetPath = __DIR__ . '/../uploads/' . $fileName;
                while (file_exists($targetPath)) {
                    $fileName = $baseName . " ($counter)." . $ext;
                    $targetPath = __DIR__ . '/../uploads/' . $fileName;
                    $counter++;
                }

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $image = $fileName;
                } else {
                    $errors[] = "Failed to upload image.";
                }
            }
        }
        // === NO NEW IMAGE → RENAME OLD ONE TO MATCH NAME ===
        else {
            if ($car['image']) {
                $oldPath = __DIR__ . '/../uploads/' . $car['image'];
                if (file_exists($oldPath)) {
                    $ext = pathinfo($car['image'], PATHINFO_EXTENSION);
                    $baseName = preg_replace('/[^a-zA-Z0-9\s-]/', '', $name);
                    $baseName = trim(preg_replace('/\s+/', ' ', $baseName));
                    $newName = $baseName . '.' . $ext;

                    $counter = 1;
                    $newPath = __DIR__ . '/../uploads/' . $newName;
                    while (file_exists($newPath)) {
                        $newName = $baseName . " ($counter)." . $ext;
                        $newPath = __DIR__ . '/../uploads/' . $newName;
                        $counter++;
                    }

                    if (rename($oldPath, $newPath)) {
                        $image = $newName;
                    }
                }
            }
        }

        // === SAVE TO DB IF NO ERRORS ===
        if (empty($errors)) {
            $stmt = $pdo->prepare("
                UPDATE cars SET 
                    name = ?, image = ?, seats = ?, bags = ?, gear = ?, fuel = ?,
                    price_day = ?, price_week = ?, price_month = ?, discount = ?,
                    insurance_basic_price = ?, insurance_smart_price = ?, insurance_premium_price = ?,
                    insurance_basic_deposit = ?, insurance_smart_deposit = ?, insurance_premium_deposit = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $name, $image, $seats, $bags, $gear, $fuel,
                $price_day, $price_week, $price_month, $discount,
                $insurance_basic_price, $insurance_smart_price, $insurance_premium_price,
                $insurance_basic_deposit, $insurance_smart_deposit, $insurance_premium_deposit, $id
            ]);
            header("Location: index.php?success=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Car</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
        --dark-bg: #36454F;
        --darker-bg: #2C3A44;
        --border: #4A5A66;
        --text: #FFFFFF;
        --text-muted: #D1D5DB;
        --gold: #FFD700;
        --gold-dark: #e6c200;
    }
    * { font-family: 'Inter', sans-serif; }
    body {
        background: var(--dark-bg);
        color: var(--text);
        min-height: 100vh;
    }
    .page-header {
        background: var(--darker-bg);
        padding: 1rem 0;
        border-bottom: 1px solid var(--border);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        margin-top: 64px; /* Account for sticky header */
    }
    @media (min-width: 768px) {
        .page-header {
            padding: 1.5rem 0;
        }
    }
    .container { 
        max-width: 1000px; 
        padding-left: 1rem;
        padding-right: 1rem;
    }
    @media (min-width: 576px) {
        .container {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
    }
    .card {
        background: var(--darker-bg);
        border: 1px solid var(--border);
        border-radius: 1rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    }
    .form-control, .form-select {
        background: var(--darker-bg);
        border: 1px solid var(--border);
        color: var(--text);
    }
    .form-control:focus, .form-select:focus {
        background: var(--darker-bg);
        border-color: var(--gold);
        box-shadow: 0 0 0 0.2rem rgba(255,215,0,.25);
        color: var(--text);
    }
    .form-control::placeholder { color: #9CA3AF; }
    .form-label { color: var(--text-muted); font-weight: 500; }
    .btn-primary {
        background: var(--gold);
        border-color: var(--gold);
        color: #000;
        font-weight: 600;
    }
    .btn-primary:hover {
        background: var(--gold-dark);
        border-color: var(--gold-dark);
    }
    .btn-secondary {
        background: #4A5A66;
        border-color: #4A5A66;
        color: var(--text);
    }
    .btn-secondary:hover {
        background: #5A6B77;
        border-color: #5A6B77;
    }
    .alert-danger {
        background: rgba(239,68,68,.15);
        border: 1px solid #ef4444;
        color: #fca5a5;
    }
    .current-img {
        max-width: 150px;
        border-radius: .75rem;
        border: 1px solid var(--border);
    }
    .text-gold { color: var(--gold); }
    .small-muted { color: var(--text-muted); font-size: .875rem; }

    /* ---------- DAY MODE OVERRIDES ---------- */
    body.day-mode {
        --dark-bg: #f8fafc;
        --darker-bg: #ffffff;
        --border: #e2e8f0;
        --text: #1e293b;
        --text-muted: #64748b;
        background: var(--dark-bg) !important;
    }
    body.day-mode .page-header,
    body.day-mode .card {
        background: var(--darker-bg) !important;
        border-color: var(--border) !important;
    }
    body.day-mode .form-control,
    body.day-mode .form-select {
        background: #ffffff !important;
        color: #1e293b !important;
        border-color: var(--border) !important;
    }
    body.day-mode .form-control:focus,
    body.day-mode .form-select:focus {
        background: #ffffff !important;
        border-color: var(--gold) !important;
        color: #1e293b !important;
    }
    body.day-mode .form-label { 
        color: #64748b !important; 
    }
    body.day-mode .btn-secondary { 
        background: #e2e8f0 !important; 
        color: #1e293b !important;
        border-color: #cbd5e1 !important;
    }
    body.day-mode .btn-secondary:hover {
        background: #cbd5e1 !important;
        border-color: #94a3b8 !important;
    }
    body.day-mode .alert-danger { 
        background: rgba(239,68,68,.1) !important; 
        color: #ef4444 !important;
        border-color: #ef4444 !important;
    }
    body.day-mode .current-img {
        border-color: var(--border) !important;
    }
    body.day-mode hr {
        border-color: var(--border) !important;
    }

    /* ---------- TOGGLE BUTTON ---------- */
    .day-mode-toggle {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--gold);
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
    .day-mode-toggle:hover { transform: scale(1.1); box-shadow: 0 12px 30px rgba(255,215,0,.5); }
    .day-mode-toggle i { transition: transform .3s; }
    .day-mode-toggle.active i { transform: rotate(180deg); }
  </style>
</head>
<body class="min-h-screen">
<?php include 'header.php'; ?>

<main class="min-h-screen">
<!-- Header -->
<div class="bg-[#2C3A44] border-b border-[#4A5A66] shadow-xl">
  <div class="container mx-auto px-6 py-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
      <h2 class="text-2xl font-bold flex items-center gap-3">
        <i class="bi bi-pencil-fill text-yellow-500"></i> Edit Car
      </h2>
      <div class="flex gap-3">
        <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-xl flex items-center gap-2">
          <i class="bi bi-arrow-left"></i> Back to List
        </a>
      </div>
    </div>
  </div>
</div>

<div class="container mx-auto px-6 py-10 max-w-7xl">
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="card p-4 p-md-5">
    <form method="POST" enctype="multipart/form-data" action="edit.php?id=<?= $id ?>">
      <input type="hidden" name="csrf" value="<?= $csrf ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="row g-4">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Car Name *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? $car['name']) ?>" required>
            <small class="small-muted">Image will be renamed to match this name.</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <?php if ($car['image']): ?>
              <img src="../uploads/<?= htmlspecialchars($car['image']) ?>?v=<?= time() ?>"
                   alt="Current" class="current-img img-thumbnail mt-2">
              <p class="small-muted mt-1">File: <strong><?= htmlspecialchars($car['image']) ?></strong></p>
            <?php else: ?>
              <p class="small-muted">No image</p>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">Replace Image (optional)</label>
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png">
            <small class="small-muted">JPG/PNG only, max 2MB</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Seats *</label>
            <input type="number" name="seats" class="form-control" value="<?= $_POST['seats'] ?? $car['seats'] ?>" min="1" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Bags *</label>
            <input type="number" name="bags" class="form-control" value="<?= $_POST['bags'] ?? $car['bags'] ?>" min="0" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Discount (%)</label>
            <input type="number" name="discount" class="form-control" min="0" max="100" value="<?= $_POST['discount'] ?? ($car['discount'] ?? 0) ?>">
            <small class="small-muted">Enter discount percentage (0 = no discount)</small>
          </div>
        </div>

        <div class="col-12">
          <hr class="my-4" style="border-color: var(--border);">
          <h5 class="text-gold mb-4">Insurance Plans</h5>
        </div>

        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">Basic Insurance - Price per Day (MAD)</label>
            <input type="number" step="0.01" name="insurance_basic_price" class="form-control"
                   value="<?= $_POST['insurance_basic_price'] ?? ($car['insurance_basic_price'] ?? '0') ?>" min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Basic Insurance - Deposit (MAD)</label>
            <input type="number" step="0.01" name="insurance_basic_deposit" class="form-control"
                   value="<?= $_POST['insurance_basic_deposit'] ?? ($car['insurance_basic_deposit'] ?? '0') ?>" min="0">
          </div>
        </div>

        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">Smart Insurance - Price per Day (MAD)</label>
            <input type="number" step="0.01" name="insurance_smart_price" class="form-control"
                   value="<?= $_POST['insurance_smart_price'] ?? ($car['insurance_smart_price'] ?? '0') ?>" min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Smart Insurance - Deposit (MAD)</label>
            <input type="number" step="0.01" name="insurance_smart_deposit" class="form-control"
                   value="<?= $_POST['insurance_smart_deposit'] ?? ($car['insurance_smart_deposit'] ?? '0') ?>" min="0">
          </div>
        </div>

        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">Premium Insurance - Price per Day (MAD)</label>
            <input type="number" step="0.01" name="insurance_premium_price" class="form-control"
                   value="<?= $_POST['insurance_premium_price'] ?? ($car['insurance_premium_price'] ?? '0') ?>" min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Premium Insurance - Deposit (MAD)</label>
            <input type="number" step="0.01" name="insurance_premium_deposit" class="form-control"
                   value="<?= $_POST['insurance_premium_deposit'] ?? ($car['insurance_premium_deposit'] ?? '0') ?>" min="0">
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Gear *</label>
            <select name="gear" class="form-select" required>
              <?php $selectedGear = $_POST['gear'] ?? $car['gear']; ?>
              <option value="Manual"   <?= $selectedGear == 'Manual' ? 'selected' : '' ?>>Manual</option>
              <option value="Automatic" <?= $selectedGear == 'Automatic' ? 'selected' : '' ?>>Automatic</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Fuel *</label>
            <select name="fuel" class="form-select" required>
              <?php $selectedFuel = $_POST['fuel'] ?? $car['fuel']; ?>
              <option value="Petrol" <?= $selectedFuel == 'Petrol' ? 'selected' : '' ?>>Petrol</option>
              <option value="Diesel" <?= $selectedFuel == 'Diesel' ? 'selected' : '' ?>>Diesel</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Price per Day (MAD)*</label>
            <input type="number" step="0.01" name="price_day" class="form-control" value="<?= $_POST['price_day'] ?? $car['price_day'] ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Price per Week (MAD)*</label>
            <input type="number" step="0.01" name="price_week" class="form-control" value="<?= $_POST['price_week'] ?? $car['price_week'] ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Price per Month (MAD)*</label>
            <input type="number" step="0.01" name="price_month" class="form-control" value="<?= $_POST['price_month'] ?? $car['price_month'] ?>" required>
          </div>
        </div>
      </div>

      <div class="text-center mt-5">
        <button type="submit" class="btn btn-primary btn-lg px-5">
          Update Car
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Day/Night Mode Toggle Button -->
<button class="day-mode-toggle" id="dayModeToggle" title="Toggle Day/Night Mode">
  <i class="bi bi-sun-fill"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Day/Night Mode Toggle - same as index.php
  const toggleBtn = document.getElementById('dayModeToggle');
  if (toggleBtn) {
    const body = document.body;
    const icon = toggleBtn.querySelector('i');

    // Load saved preference
    if (localStorage.getItem('dayMode') === 'true') {
        body.classList.add('day-mode');
        if (icon) {
          icon.classList.replace('bi-sun-fill', 'bi-moon-fill');
        }
        toggleBtn.classList.add('active');
    }

    toggleBtn.addEventListener('click', () => {
        body.classList.toggle('day-mode');
        const isDay = body.classList.contains('day-mode');

        if (icon) {
          if (isDay) {
              icon.classList.replace('bi-sun-fill', 'bi-moon-fill');
          } else {
              icon.classList.replace('bi-moon-fill', 'bi-sun-fill');
          }
        }
        toggleBtn.classList.toggle('active', isDay);
        localStorage.setItem('dayMode', isDay);
    });
  }
</script>
</main>
</body>
</html>