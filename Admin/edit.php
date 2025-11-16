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
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch();

if (!$car) {
    die("Car not found.");
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
        $name        = trim($_POST['name'] ?? '');
        $seats       = (int)($_POST['seats'] ?? 0);
        $bags        = (int)($_POST['bags'] ?? 0);
        $gear        = $_POST['gear'] ?? '';
        $fuel        = $_POST['fuel'] ?? '';
        $price_day   = (float)($_POST['price_day'] ?? 0);
        $price_week  = (float)($_POST['price_week'] ?? 0);
        $price_month = (float)($_POST['price_month'] ?? 0);

        // Validation
        if (empty($name)) $errors[] = "Car name is required.";
        if ($seats < 1) $errors[] = "Seats must be at least 1.";
        if ($bags < 0) $errors[] = "Bags cannot be negative.";
        if (!in_array($gear, ['Manual', 'Automatic'])) $errors[] = "Invalid gear type.";
        if (!in_array($fuel, ['Petrol', 'Diesel'])) $errors[] = "Invalid fuel type.";
        if ($price_day <= 0) $errors[] = "Price per day must be positive.";

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

                // === NAME IMAGE AFTER CAR ===
                $baseName = preg_replace('/[^a-zA-Z0-9\s-]/', '', $name);
                $baseName = trim(preg_replace('/\s+/', ' ', $baseName));
                $fileName = $baseName . '.' . $ext;

                // Prevent overwrite
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
                    price_day = ?, price_week = ?, price_month = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $name, $image, $seats, $bags, $gear, $fuel,
                $price_day, $price_week, $price_month, $id
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
        background: var(--darker-bg)/90;
        backdrop-filter: blur(12px);
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
  </style>
</head>
<body>

<!-- Header -->
<div class="page-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <h2 class="h4 mb-0 fw-bold d-flex align-items-center gap-2">
        <i class="bi bi-pencil-square text-gold"></i>
        Edit Car
      </h2>
      <a href="index.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
      </a>
    </div>
  </div>
</div>

<div class="container mt-5 pb-5">
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
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= $csrf ?>">

      <div class="row g-4">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Car Name *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($car['name']) ?>" required>
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
            <input type="number" name="seats" class="form-control" value="<?= $car['seats'] ?>" min="1" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Bags *</label>
            <input type="number" name="bags" class="form-control" value="<?= $car['bags'] ?>" min="0" required>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Gear *</label>
            <select name="gear" class="form-select" required>
              <option value="Manual"   <?= $car['gear'] == 'Manual' ? 'selected' : '' ?>>Manual</option>
              <option value="Automatic" <?= $car['gear'] == 'Automatic' ? 'selected' : '' ?>>Automatic</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Fuel *</label>
            <select name="fuel" class="form-select" required>
              <option value="Petrol" <?= $car['fuel'] == 'Petrol' ? 'selected' : '' ?>>Petrol</option>
              <option value="Diesel" <?= $car['fuel'] == 'Diesel' ? 'selected' : '' ?>>Diesel</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Price per Day (MAD)*</label>
            <input type="number" step="0.01" name="price_day" class="form-control" value="<?= $car['price_day'] ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Price per Week (MAD)*</label>
            <input type="number" step="0.01" name="price_week" class="form-control" value="<?= $car['price_week'] ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Price per Month (MAD)*</label>
            <input type="number" step="0.01" name="price_month" class="form-control" value="<?= $car['price_month'] ?>" required>
          </div>
        </div>
      </div>

      <div class="text-center mt-5">
        <button type="submit" class="btn btn-primary btn-lg px-5">
          <i class="bi bi-check-circle"></i> Update Car
        </button>
      </div>
    </form>
  </div>
</div>

</body>
</html>