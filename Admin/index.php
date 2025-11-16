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
   2. REGENERATE SESSION ID every 10 min
   ------------------------------------------------- */
if (empty($_SESSION['last_regen']) || time() - $_SESSION['last_regen'] > 600) {
    session_regenerate_id(true);
    $_SESSION['last_regen'] = time();
}

/* -------------------------------------------------
   3. CSRF TOKEN
   ------------------------------------------------- */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

/* -------------------------------------------------
   4. ALERT LOGIC
   ------------------------------------------------- */
$alert = null;
if (isset($_GET['success'])) {
    $alert = ['type' => 'success', 'msg' => 'Car saved successfully!'];
} elseif (isset($_GET['deleted'])) {
    $alert = ['type' => 'danger', 'msg' => 'Car deleted permanently.'];
} elseif (isset($_GET['error'])) {
    $alert = ['type' => 'warning', 'msg' => 'An error occurred. Please try again.'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin – Car Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
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
    body { background: var(--dark-bg); color: var(--text); }

    .page-header {
      background: var(--darker-bg);
      padding: 1.5rem 0;
      border-bottom: 1px solid var(--border);
      margin-bottom: 2rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .car-card {
      background: var(--darker-bg)/90;
      backdrop-filter: blur(12px);
      border-radius: 1rem;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      transition: all 0.25s ease;
      border: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .car-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 30px rgba(255,215,0,0.15);
      border-color: var(--gold);
    }

    .car-image {
      position: relative;
      overflow: hidden;
      background: var(--darker-bg);
    }

    .car-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .car-card:hover .car-image img {
      transform: scale(1.05);
    }

    .car-info {
      padding: 1.25rem;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .car-name {
      font-size: 1.125rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--text);
      display: -webkit-box;
      -webkit-line-clamp: 1;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .meta-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 0.75rem;
      font-size: 0.875rem;
      color: var(--text-muted);
    }

    .tag {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      background: var(--darker-bg);
      padding: 0.25rem 0.5rem;
      border-radius: 0.5rem;
      font-weight: 500;
      color: var(--text-muted);
    }

    .price-section {
      margin-top: auto;
      padding-top: 1rem;
      border-top: 1px dashed var(--border);
    }

    .price-main {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--gold);
      margin-bottom: 0.25rem;
    }

    .price-sub {
      font-size: 0.8rem;
      color: var(--text-muted);
    }

    .empty-state {
      text-align: center;
      padding: 4rem 1rem;
      color: var(--text-muted);
    }

    .empty-state i {
      font-size: 3rem;
      color: #6b7280;
      margin-bottom: 1rem;
    }

    /* === TOAST ALERTS === */
    .toast-container {
      position: fixed;
      top: 1rem;
      right: 1rem;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .toast {
      min-width: 300px;
      max-width: 400px;
      background: var(--darker-bg);
      border-radius: 0.75rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
      overflow: hidden;
      border-left: 5px solid;
      animation: slideIn 0.4s ease, fadeOut 0.5s ease 4.5s forwards;
      opacity: 0;
    }

    @keyframes slideIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    @keyframes fadeOut {
      to { transform: translateX(120%); opacity: 0; }
    }

    .toast.success { border-left-color: #10b981; }
    .toast.danger { border-left-color: #ef4444; }
    .toast.warning { border-left-color: #f59e0b; }

    .toast-header {
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 600;
      font-size: 0.95rem;
    }

    .toast.success .toast-header { color: #10b981; }
    .toast.danger .toast-header { color: #ef4444; }
    .toast.warning .toast-header { color: #f59e0b; }

    .toast-body {
      padding: 0 1rem 1rem 1rem;
      font-size: 0.875rem;
      color: var(--text-muted);
    }

    .toast-close {
      margin-left: auto;
      background: none;
      border: none;
      font-size: 1.2rem;
      color: var(--text-muted);
      cursor: pointer;
      padding: 0;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: all 0.2s;
    }

    .toast-close:hover {
      background: var(--border);
      color: var(--text);
    }

    @media (max-width: 576px) {
      .toast-container { top: 0.5rem; right: 0.5rem; left: 0.5rem; }
      .toast { min-width: auto; }
    }

    /* ---------- ACTION AREA (IMPROVED) ---------- */
    .action-area .btn {
      font-weight: 600;
      min-height: 44px;
      border-radius: .75rem;
      transition: all .2s ease;
    }
    .action-area .btn-primary {
      background: var(--gold);
      border-color: var(--gold);
      color: #000;
    }
    .action-area .btn-primary:hover,
    .action-area .btn-primary:focus {
      background: var(--gold-dark);
      border-color: var(--gold-dark);
      box-shadow: 0 0 0 3px rgba(255,215,0,.3);
    }
    .action-area .btn-outline-danger {
      color: #ef4444;
      border-color: #ef4444;
    }
    .action-area .btn-outline-danger:hover,
    .action-area .btn-outline-danger:focus {
      background: #ef4444;
      color: #fff;
      box-shadow: 0 0 0 3px rgba(239,68,68,.3);
    }

    /* Modal tweaks */
    .modal-content {
      background: var(--darker-bg);
      border: 1px solid var(--border);
      border-radius: 1rem;
    }
    .modal-header .modal-title {
      font-weight: 600;
    }
    .modal-footer .btn {
      min-height: 44px;
      border-radius: .75rem;
    }
    .modal-body, .modal-header, .modal-footer { color: var(--text); }

    /* DAY MODE TOGGLE */
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
    .day-mode-toggle:hover {
      transform: scale(1.1);
      box-shadow: 0 12px 30px rgba(255,215,0,.5);
    }
    .day-mode-toggle i { transition: transform .3s; }
    .day-mode-toggle.active i { transform: rotate(180deg); }

    /* LIGHT MODE (DAY) */
    body.day-mode {
      --dark-bg: #f8fafc;
      --darker-bg: #ffffff;
      --border: #e2e8f0;
      --text: #1e293b;
      --text-muted: #64748b;
    }
    body.day-mode .page-header,
    body.day-mode .car-card,
    body.day-mode .modal-content,
    body.day-mode .toast {
      background: var(--darker-bg);
      border-color: var(--border);
    }
    body.day-mode .car-card:hover {
      box-shadow: 0 20px 30px rgba(0,0,0,0.08);
      border-color: var(--gold);
    }
    body.day-mode .tag,
    body.day-mode .car-info {
      background: transparent;
    }
    body.day-mode .price-main { color: var(--gold); }
    body.day-mode .btn-primary {
      background: var(--gold);
      color: #000;
    }
    body.day-mode .btn-outline-danger {
      color: #ef4444;
      border-color: #ef4444;
    }
    body.day-mode .btn-outline-danger:hover {
      background: #ef4444;
      color: #fff;
    }
    .price-main {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--gold);
  margin-bottom: 0.25rem;
  display: flex;
  align-items: baseline;
  gap: 0.4rem;
  line-height: 1.2;
}

.price-main .currency {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--gold);
}

.price-main .amount {
  font-size: 2rem;
  font-weight: 800;
  letter-spacing: -0.5px;
}

.price-main .per-day {
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--text-muted);
  margin-left: 0.25rem;
}

/* Day Mode Adjustment */
body.day-mode .price-main .currency,
body.day-mode .price-main .amount {
  color: var(--gold);
}
body.day-mode .price-main .per-day {
  color: var(--text-muted);
}
  </style>
</head>
<body>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- DAY MODE TOGGLE -->
<button class="day-mode-toggle" id="dayModeToggle" title="Toggle Day/Night Mode">
  <i class="bi bi-sun-fill"></i>
</button>

<!-- Header -->
<div class="page-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <h2 class="h4 mb-0 fw-bold d-flex align-items-center gap-2">
        <i class="bi bi-car-front-fill" style="color: var(--gold);"></i>
        Car Management
      </h2>
      <div class="d-flex gap-2 flex-wrap">
        <a href="create.php" class="btn btn-success btn-sm">
          <i class="bi bi-plus-circle"></i> Add New Car
        </a>
        <a href="change_password.php" class="btn btn-warning btn-sm text-white">
          <i class="bi bi-shield-lock"></i> Password
        </a>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </div>
    </div>
  </div>
</div>

<div class="container pb-5">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php
    function getCarImage(string $filename): string {
        if (empty($filename)) return '';
        $safe = basename($filename);
        $path = '../uploads/' . $safe;
        $full = __DIR__ . '/' . $path;
        if (file_exists($full)) {
            return $path . '?v=' . filemtime($full);
        }
        return '';
    }

    $stmt = $pdo->query("SELECT * FROM cars ORDER BY id DESC");
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cars)): ?>
      <div class="col-12">
        <div class="empty-state">
          <i class="bi bi-inbox"></i>
          <h5>No cars added yet</h5>
          <p class="text-muted">Click "Add New Car" to get started.</p>
        </div>
      </div>
    <?php else: ?>
      <?php foreach ($cars as $row):
        $img = getCarImage($row['image']);
        $placeholder = 'https://via.placeholder.com/400x300/2C3A44/D1D5DB?text=' . urlencode($row['name']);
        $src = $img ?: $placeholder;
      ?>
        <div class="col">
          <article data-aos="fade-up" class="car-card">
            <!-- Image -->
            <div class="car-image ratio ratio-4x3">
              <img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>"
                   alt="<?= htmlspecialchars($row['name']) ?>"
                   onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300/2C3A44/D1D5DB?text=No+Image';">
            </div>

            <!-- Info -->
            <div class="car-info">
              <h3 class="car-name"><?= htmlspecialchars($row['name']) ?></h3>

              <div class="meta-tags">
                <span class="tag"><i class="bi bi-person"></i> <?= (int)$row['seats'] ?> Seats</span>
                <span class="tag"><i class="bi bi-briefcase"></i> <?= (int)$row['bags'] ?> Bags</span>
              </div>

              <div class="d-flex justify-content-between text-muted small mb-3">
                <span class="tag"><i class="bi bi-gear"></i> <?= htmlspecialchars($row['gear']) ?></span>
                <span class="tag"><i class="bi bi-fuel-pump"></i> <?= htmlspecialchars($row['fuel']) ?></span>
              </div>

              <!-- Price -->
              <div class="price-section">
<div class="price-main">
  <span class="currency">MAD</span>
  <span class="amount"><?= number_format((float)$row['price_day']) ?></span>
  <span class="per-day">/day</span>
</div>                <div class="price-sub">
                  Week: <strong>MAD <?= number_format((float)$row['price_week']) ?></strong> |
                  Month: <strong>MAD <?= number_format((float)$row['price_month']) ?></strong>
                </div>
              </div>

              <!-- ==== ACTION AREA ==== -->
              <div class="action-area mt-3">
                <!-- Edit – primary -->
                <a href="edit.php?id=<?= (int)$row['id'] ?>"
                   class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2"
                   aria-label="Edit <?= htmlspecialchars($row['name']) ?>">
                  <i class="bi bi-pencil-fill"></i> Edit
                </a>

                <!-- Delete – secondary + modal -->
                <button type="button"
                        class="btn btn-outline-danger w-100 mt-2 d-flex align-items-center justify-content-center gap-2"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal<?= $row['id'] ?>"
                        aria-label="Delete <?= htmlspecialchars($row['name']) ?>">
                  <i class="bi bi-trash-fill"></i> Delete
                </button>
              </div>
            </div>
          </article>
        </div>

        <!-- ==== DELETE CONFIRMATION MODAL ==== -->
        <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body pt-2">
                <p class="mb-0">Permanently delete <strong><?= htmlspecialchars($row['name']) ?></strong>?</p>
              </div>
              <div class="modal-footer border-0 pt-0">
                <form action="delete.php" method="POST" class="d-inline w-100">
                  <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                  <input type="hidden" name="csrf" value="<?= $csrf ?>">
                  <button type="submit"
                          class="btn btn-danger w-100"
                          onclick="this.disabled=true; this.closest('form').submit();">
                    <i class="bi bi-trash"></i> Yes, Delete
                  </button>
                </form>
                <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">Cancel</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- Scripts -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  AOS.init({ once: true, duration: 600, easing: 'ease-out-quart' });

  // Image loading
  document.addEventListener('DOMContentLoaded', () => {
    const images = document.querySelectorAll('.car-image img');
    images.forEach(img => {
      img.onload = () => img.closest('.car-image').style.background = 'transparent';
    });
  });

  // === TOAST ALERT SYSTEM ===
  <?php if ($alert): ?>
  (function() {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast <?= $alert['type'] ?>`;
    toast.innerHTML = `
      <div class="toast-header">
        <i class="bi ${getIcon(<?= json_encode($alert['type']) ?>)}"></i>
        <span>${getTitle(<?= json_encode($alert['type']) ?>)}</span>
        <button type="button" class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
      </div>
      <div class="toast-body">
        <?= htmlspecialchars($alert['msg']) ?>
      </div>
    `;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);

    function getIcon(type) {
      return type === 'success' ? 'bi-check-circle-fill' :
             type === 'danger' ? 'bi-x-circle-fill' :
             'bi-exclamation-triangle-fill';
    }
    function getTitle(type) {
      return type === 'success' ? 'Success' :
             type === 'danger' ? 'Deleted' :
             'Warning';
    }
  })();
  <?php endif; ?>

  // === DAY/NIGHT MODE TOGGLE ===
  const toggleBtn = document.getElementById('dayModeToggle');
  const body = document.body;
  const icon = toggleBtn.querySelector('i');

  // Load saved preference
  if (localStorage.getItem('dayMode') === 'true') {
    body.classList.add('day-mode');
    icon.classList.replace('bi-sun-fill', 'bi-moon-fill');
    toggleBtn.classList.add('active');
  }

  toggleBtn.addEventListener('click', () => {
    body.classList.toggle('day-mode');
    const isDay = body.classList.contains('day-mode');
    icon.classList.toggle('bi-sun-fill', !isDay);
    icon.classList.toggle('bi-moon-fill', isDay);
    toggleBtn.classList.toggle('active', isDay);
    localStorage.setItem('dayMode', isDay);
  });
</script>
</body>
</html>
