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
        $name        = trim($_POST['name'] ?? '');
        $seats       = (int)($_POST['seats'] ?? 0);
        $bags        = (int)($_POST['bags'] ?? 0);
        $gear        = $_POST['gear'] ?? '';
        $fuel        = $_POST['fuel'] ?? '';
        $price_day   = (float)($_POST['price_day'] ?? 0);
        $price_week  = (float)($_POST['price_week'] ?? 0);
        $price_month = (float)($_POST['price_month'] ?? 0);
        $discount    = (int)($_POST['discount'] ?? 0);

        if (empty($name)) $errors['name'] = "Car name is required.";
        if ($seats < 1) $errors['seats'] = "Seats must be at least 1.";
        if ($bags < 0) $errors['bags'] = "Bags cannot be negative.";
        if (!in_array($gear, ['Manual', 'Automatic'])) $errors['gear'] = "Invalid gear.";
        if (!in_array($fuel, ['Petrol', 'Diesel'])) $errors['fuel'] = "Invalid fuel.";
        if ($price_day <= 0) $errors['price_day'] = "Price per day must be positive.";
        if ($discount < 0 || $discount > 100) $errors['discount'] = "Discount must be between 0 and 100.";

        $image = '';
        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {
                $errors['image'] = "Only JPG/PNG allowed.";
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $errors['image'] = "Image too large (max 2MB).";
            } elseif (!getimagesize($file['tmp_name'])) {
                $errors['image'] = "Not a valid image.";
            } else {
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
                    $errors['image'] = "Upload failed.";
                }
            }
        } else {
            $errors['image'] = "Image is required.";
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("
                INSERT INTO cars (name, image, seats, bags, gear, fuel, price_day, price_week, price_month, discount)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $image, $seats, $bags, $gear, $fuel, $price_day, $price_week, $price_month, $discount]);
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
  <title>Add New Car – Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    :root {
        --dark-bg: #36454F;
        --darker-bg: #2C3A44;
        --border: #4A5A66;
        --text: #FFFFFF;
        --text-muted: #D1D5DB;
        --gold: #FFD700;
        --gold-dark: #e6c200;
        --danger: #ef4444;
    }
    * { font-family: 'Inter', sans-serif; }
    body {
        background: var(--dark-bg);
        color: var(--text);
        min-height: 100vh;
    }
    .page-header {
        background: var(--darker-bg);
        padding: 1.5rem 0;
        border-bottom: 1px solid var(--border);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .container { max-width: 1000px; }
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
        border: 1px solid var(--danger);
        color: #fca5a5;
    }
    .text-gold { color: var(--gold); }
    .small-muted { color: var(--text-muted); font-size: .875rem; }
    .text-danger { color: #fca5a5; }
    .image-preview {
        width: 100%;
        height: 200px;
        border: 2px dashed var(--border);
        border-radius: .75rem;
        overflow: hidden;
        background: var(--darker-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: .5rem;
    }
    .image-preview img { max-width: 100%; max-height: 100%; object-fit: cover; }
    .image-preview .placeholder { color: var(--text-muted); text-align: center; }

    /* DAY MODE OVERRIDES */
    body.day-mode {
        --dark-bg: #f8fafc;
        --darker-bg: #ffffff;
        --border: #e2e8f0;
        --text: #1e293b;
        --text-muted: #64748b;
    }
    body.day-mode .page-header,
    body.day-mode .card,
    body.day-mode .image-preview {
        background: var(--darker-bg);
        border-color: var(--border);
    }
    body.day-mode .form-control,
    body.day-mode .form-select {
        background: #f8fafc;
        color: #1e293b;
    }
    body.day-mode .form-label,
    body.day-mode .small-muted,
    body.day-mode .image-preview .placeholder { color: #64748b; }
    body.day-mode .btn-secondary { background: #e2e8f0; color: #1e293b; }
    body.day-mode .alert-danger { background: rgba(239,68,68,.1); color: #ef4444; }
    body.day-mode .text-danger { color: #ef4444; }

    /* TOGGLE BUTTON */
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
<body>

<!-- DAY MODE TOGGLE -->
<button class="day-mode-toggle" id="dayModeToggle" title="Toggle Day/Night Mode">
    <i class="bi bi-sun-fill"></i>
</button>

<!-- Header -->
<div class="page-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <h2 class="h4 mb-0 fw-bold d-flex align-items-center gap-2">
        Add New Car
      </h2>
      <a href="index.php" class="btn btn-secondary">
        Back to List
      </a>
    </div>
  </div>
</div>

<div class="container mt-5 pb-5">
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $msg): ?>
          <li><?= htmlspecialchars($msg) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="card p-4 p-md-5">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= $csrf ?>">

      <div class="row g-4">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Car Name *</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            <?php if (isset($errors['name'])): ?>
              <div class="text-danger small"><?= htmlspecialchars($errors['name']) ?></div>
            <?php endif; ?>
            <small class="small-muted">Image will be renamed to match this name.</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Image (JPG/PNG, max 2MB) *</label>
            <input type="file" name="image" class="form-control"
                   accept="image/jpeg,image/png" required onchange="previewImage(this)">
            <?php if (isset($errors['image'])): ?>
              <div class="text-danger small"><?= htmlspecialchars($errors['image']) ?></div>
            <?php endif; ?>
            <div class="image-preview" id="imagePreview">
              <div class="placeholder">
                No image selected
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Seats *</label>
            <input type="number" name="seats" class="form-control" min="1"
                   value="<?= $_POST['seats'] ?? '4' ?>" required>
            <?php if (isset($errors['seats'])): ?>
              <div class="text-danger small"><?= htmlspecialchars($errors['seats']) ?></div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">Bags *</label>
            <input type="number" name="bags" class="form-control" min="0"
                   value="<?= $_POST['bags'] ?? '2' ?>" required>
            <?php if (isset($errors['bags'])): ?>
              <div class="text-danger small"><?= htmlspecialchars($errors['bags']) ?></div>
            <?php endif; ?>
          </div>

          <!-- NEW: Discount Field -->
          <div class="mb-3">
            <label class="form-label">Discount (%)</label>
            <input type="number" name="discount" class="form-control" min="0" max="100"
                   value="<?= $_POST['discount'] ?? '0' ?>">
            <?php if (isset($errors['discount'])): ?>
              <div class="text-danger small"><?= htmlspecialchars($errors['discount']) ?></div>
            <?php endif; ?>
            <small class="small-muted">Enter discount percentage (0 = no discount)</small>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Gear *</label>
            <select name="gear" class="form-select" required>
              <option value="">-- Select --</option>
              <option value="Manual"   <?= ($_POST['gear'] ?? '') === 'Manual' ? 'selected' : '' ?>>Manual</option>
              <option value="Automatic"<?= ($_POST['gear'] ?? '') === 'Automatic' ? 'selected' : '' ?>>Automatic</option>
            </select>
            <?php if (isset($errors['gear'])): ?>
              <div class="text-danger small"><?= htmlspecialchars($errors['gear']) ?></div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">Fuel *</label>
            <select name="fuel" class="form-select" required>
              <option value="">-- Select --</option>
              <option value="Petrol" <?= ($_POST['fuel'] ?? '') === 'Petrol' ? 'selected' : '' ?>>Petrol</option>
              <option value="Diesel" <?= ($_POST['fuel'] ?? '') === 'Diesel' ? 'selected' : '' ?>>Diesel</option>
            </select>
            <?php if (isset($errors['fuel'])): ?>
              <div class="text-danger small"><?= htmlspecialchars($errors['fuel']) ?></div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">Price per Day (MAD)*</label>
            <input type="number" step="0.01" name="price_day" class="form-control"
                   value="<?= $_POST['price_day'] ?? '' ?>" required>
            <?php if (isset($errors['price_day'])): ?>
              <div class="text-danger small"><?= htmlspecialchars($errors['price_day']) ?></div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">Price per Week (MAD)*</label>
            <input type="number" step="0.01" name="price_week" class="form-control"
                   value="<?= $_POST['price_week'] ?? '' ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Price per Month (MAD)*</label>
            <input type="number" step="0.01" name="price_month" class="form-control"
                   value="<?= $_POST['price_month'] ?? '' ?>" required>
          </div>
        </div>
      </div>

      <div class="text-center mt-5">
        <button type="submit" class="btn btn-primary btn-lg px-5">
          Add Car
        </button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Image Preview
  function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const file = input.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
      reader.readAsDataURL(file);
    } else {
      preview.innerHTML = `
        <div class="placeholder">
          No image selected
        </div>`;
    }
  }

  // Focus on name field
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('input[name="name"]').focus();
  });

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