<?php
require_once 'config.php';

/* -------------------------------------------------
   1. ONLY ALLOW LOGGED-IN ADMIN
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
   3. GET CURRENT ADMIN
   ------------------------------------------------- */
$admin_id = $_SESSION['admin_id'] ?? 0;
$stmt = $pdo->prepare("SELECT username, password FROM admins WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();

if (!$admin) {
    session_destroy();
    header('Location: login.php');
    exit;
}

/* -------------------------------------------------
   4. HANDLE FORM
   ------------------------------------------------- */
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($csrf, $_POST['csrf'] ?? '')) {
        $errors[] = "Invalid request.";
    } else {
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // Validate current password
        if (!password_verify($current, $admin['password'])) {
            $errors[] = "Current password is incorrect.";
        }

        // Validate new password
        if (strlen($new) < 8) {
            $errors[] = "New password must be at least 8 characters.";
        } elseif ($new !== $confirm) {
            $errors[] = "Passwords do not match.";
        }

        // Update if no errors
        if (empty($errors)) {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $stmt->execute([$hash, $admin_id]);

            // Log action
            $log = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, ip, created_at) VALUES (?, 'password_change', ?, NOW())");
            $log->execute([$admin_id, $_SERVER['REMOTE_ADDR'] ?? 'unknown']);

            $success = "Password changed successfully!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password – Admin</title>
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
        --success: #10b981;
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
    .container { max-width: 500px; }
    .card {
        background: var(--darker-bg)/90;
        backdrop-filter: blur(12px);
        border: 1px solid var(--border);
        border-radius: 1rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    }
    .card-header {
        background: var(--gold);
        color: #000;
        font-weight: 600;
        border-bottom: 1px solid var(--border);
        border-radius: 1rem 1rem 0 0;
        padding: 1.25rem 1.5rem;
    }
    .form-control {
        background: var(--darker-bg);
        border: 1px solid var(--border);
        color: var(--text);
        border-radius: .5rem;
    }
    .form-control:focus {
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
    .alert-success {
        background: rgba(16,185,129,.15);
        border: 1px solid var(--success);
        color: #a7f3d0;
        border-radius: .75rem;
    }
    .alert-danger {
        background: rgba(239,68,68,.15);
        border: 1px solid var(--danger);
        color: #fca5a5;
        border-radius: .75rem;
    }
    .text-gold { color: var(--gold); }
    .small-muted { color: var(--text-muted); font-size: .875rem; }
  </style>
</head>
<body>

<!-- Header (same as edit.php / create.php) -->
<div class="page-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <h2 class="h4 mb-0 fw-bold d-flex align-items-center gap-2">
        <i class="bi bi-shield-lock text-gold"></i>
        Change Password
      </h2>
      <a href="index.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
      </a>
    </div>
  </div>
</div>

<div class="container mt-5 pb-5">
  <div class="card p-4">
    <div class="card-header text-center">
      <h4 class="mb-0">Change Admin Password</h4>
    </div>
    <div class="card-body">
      <p class="small-muted text-center mb-4">
        Logged in as: <strong><?= htmlspecialchars($admin['username']) ?></strong>
      </p>

      <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST">
        <input type="hidden" name="csrf" value="<?= $csrf ?>">

        <div class="mb-3">
          <label class="form-label">Current Password</label>
          <input type="password" name="current_password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" name="new_password" class="form-control" required minlength="8">
          <small class="small-muted">Minimum 8 characters</small>
        </div>

        <div class="mb-4">
          <label class="form-label">Confirm New Password</label>
          <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-circle"></i> Change Password
          </button>
          <a href="index.php" class="btn btn-secondary">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>