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

// Log action (safe – won't break if table is missing)
try {
    $log = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, ip, created_at) VALUES (?, 'password_change', ?, NOW())");
    $log->execute([$admin_id, $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
} catch (PDOException $e) {
    // Silently ignore if table doesn't exist or any DB error occurs
    // Optional: log to PHP error log for debugging (uncomment if needed)
    // error_log("Admin log failed (table may not exist): " . $e->getMessage());
    // Do nothing – this prevents fatal error
}
            $success = "Password changed successfully!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password - Admin</title>
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

<main class="min-h-screen">
<!-- Header -->
<div class="bg-[#2C3A44] border-b border-[#4A5A66] shadow-xl">
  <div class="container mx-auto px-6 py-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
      <h2 class="text-2xl font-bold flex items-center gap-3">
        <i class="bi bi-shield-lock text-yellow-500"></i> Change Password
      </h2>
      <a href="index.php" class="bg-[#36454F] hover:bg-[#4A5A66] text-white font-bold py-3 px-6 rounded-xl flex items-center gap-2 border border-[#4A5A66] transition">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
      </a>
    </div>
  </div>
</div>

<div class="container mx-auto px-6 py-10 max-w-2xl">
  <div class="bg-[#2C3A44] rounded-2xl shadow-2xl border border-[#4A5A66] p-8">
    <div class="text-center mb-6">
      <h3 class="text-2xl font-bold text-white mb-2">Change Admin Password</h3>
      <p class="text-gray-400">
        Logged in as: <strong class="text-yellow-500"><?= htmlspecialchars($admin['username']) ?></strong>
      </p>
    </div>

    <?php if ($success): ?>
      <div class="mb-6 p-4 bg-green-600/20 border border-green-500 rounded-xl text-green-400">
        <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="mb-6 p-4 bg-red-600/20 border border-red-500 rounded-xl text-red-400">
        <ul class="list-disc list-inside space-y-1">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
      <input type="hidden" name="csrf" value="<?= $csrf ?>">

      <div>
        <label class="block text-gray-300 font-semibold mb-2">
          <i class="bi bi-lock-fill text-yellow-500"></i> Current Password
        </label>
        <input type="password" name="current_password" 
               class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition" 
               required>
      </div>

      <div>
        <label class="block text-gray-300 font-semibold mb-2">
          <i class="bi bi-key-fill text-yellow-500"></i> New Password
        </label>
        <input type="password" name="new_password" 
               class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition" 
               required minlength="8">
        <small class="text-gray-400 text-sm mt-1 block">Minimum 8 characters</small>
      </div>

      <div>
        <label class="block text-gray-300 font-semibold mb-2">
          <i class="bi bi-key-fill text-yellow-500"></i> Confirm New Password
        </label>
        <input type="password" name="confirm_password" 
               class="w-full p-4 bg-[#36454F] border border-[#4A5A66] text-white rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition" 
               required>
      </div>

      <div class="flex gap-4 pt-4">
        <button type="submit" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-4 px-6 rounded-xl flex items-center justify-center gap-2 transition shadow-lg">
          <i class="bi bi-check-circle"></i> Change Password
        </button>
        <a href="index.php" class="bg-[#36454F] hover:bg-[#4A5A66] text-white font-bold py-4 px-6 rounded-xl flex items-center justify-center border border-[#4A5A66] transition">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>
</main>
</body>
</html>