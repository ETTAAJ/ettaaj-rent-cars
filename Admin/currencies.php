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
   3. HANDLE FORM SUBMISSIONS
   ------------------------------------------------- */
$alert = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $csrf) {
        $alert = ['type' => 'danger', 'msg' => 'Invalid security token.'];
    } else {
        $action = $_POST['action'];
        
        if ($action === 'update') {
            $id = (int)$_POST['id'];
            $rate = (float)$_POST['rate_to_mad'];
            
            if ($rate > 0) {
                $stmt = $pdo->prepare("UPDATE currencies SET rate_to_mad = ? WHERE id = ?");
                $stmt->execute([$rate, $id]);
                $alert = ['type' => 'success', 'msg' => 'Currency rate updated successfully!'];
            } else {
                $alert = ['type' => 'danger', 'msg' => 'Rate must be greater than 0.'];
            }
        } elseif ($action === 'toggle') {
            $id = (int)$_POST['id'];
            $stmt = $pdo->prepare("UPDATE currencies SET is_active = NOT is_active WHERE id = ?");
            $stmt->execute([$id]);
            $alert = ['type' => 'success', 'msg' => 'Currency status updated!'];
        }
    }
}

/* -------------------------------------------------
   4. FETCH ALL CURRENCIES
   ------------------------------------------------- */
$stmt = $pdo->query("SELECT * FROM currencies ORDER BY code ASC");
$currencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Management - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-color: #FFB22C;
            --bg-dark: #1a1a1a;
            --card: #2a2a2a;
            --border: #3a3a3a;
        }
        body {
            background: #36454F;
            color: #fff;
            font-family: 'Inter', sans-serif;
        }
        
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
            <i class="bi bi-currency-exchange text-yellow-500"></i> Currency Management
          </h2>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 py-10 max-w-7xl">

        <!-- Alert -->
        <?php if ($alert): ?>
        <div class="mb-6 p-4 rounded-lg <?= $alert['type'] === 'success' ? 'bg-green-600' : 'bg-red-600' ?>">
            <?= htmlspecialchars($alert['msg']) ?>
        </div>
        <?php endif; ?>

        <!-- Info Box -->
        <div class="mb-6 p-4 bg-blue-900/50 border border-blue-500 rounded-lg">
            <p class="text-sm">
                <i class="bi bi-info-circle"></i> 
                <strong>Note:</strong> All rates are relative to MAD (Moroccan Dirham). 
                For example, if USD rate is 10.0, it means 1 USD = 10 MAD.
            </p>
        </div>

        <!-- Currencies Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($currencies as $currency): ?>
            <div class="bg-[#2C3A44] rounded-2xl shadow-2xl border border-[#4A5A66] p-6 hover:shadow-yellow-500/20 transition-all duration-300 transform hover:-translate-y-2 hover:scale-[1.02] flex flex-col">
                <!-- Card Header -->
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-[#4A5A66]">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-2xl font-bold text-black"><?= htmlspecialchars($currency['symbol']) ?></span>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-lg"><?= htmlspecialchars($currency['name']) ?></h3>
                            <p class="text-sm text-yellow-400 font-semibold"><?= htmlspecialchars($currency['code']) ?></p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap <?= $currency['is_active'] ? 'bg-green-600' : 'bg-gray-600' ?>">
                        <?= $currency['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </div>

                <!-- Rate Form -->
                <form method="POST" class="mb-4">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $currency['id'] ?>">
                    <div class="mb-3">
                        <label class="block text-sm text-gray-400 mb-2">Rate to MAD</label>
                        <input type="number" 
                               name="rate_to_mad" 
                               value="<?= htmlspecialchars($currency['rate_to_mad']) ?>" 
                               step="0.0001"
                               min="0.0001"
                               class="w-full px-4 py-3 bg-[#36454F] border border-[#4A5A66] rounded-xl text-white focus:outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/50"
                               required>
                        <p class="text-xs text-gray-500 mt-1">1 <?= htmlspecialchars($currency['code']) ?> = <?= htmlspecialchars($currency['rate_to_mad']) ?> MAD</p>
                    </div>
                    <button type="submit" 
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 px-4 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="bi bi-check-lg"></i> Update Rate
                    </button>
                </form>

                <!-- Toggle Status -->
                <form method="POST" class="mt-auto">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <input type="hidden" name="action" value="toggle">
                    <input type="hidden" name="id" value="<?= $currency['id'] ?>">
                    <button type="submit" 
                            class="w-full px-4 py-3 <?= $currency['is_active'] ? 'bg-gray-600 hover:bg-gray-500' : 'bg-green-600 hover:bg-green-500' ?> rounded-xl transition font-bold flex items-center justify-center gap-2">
                        <i class="bi bi-<?= $currency['is_active'] ? 'pause' : 'play' ?>-fill"></i>
                        <?= $currency['is_active'] ? 'Deactivate' : 'Activate' ?>
                    </button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Example Conversion -->
        <div class="mt-8 p-6 bg-gray-800 rounded-lg">
            <h2 class="text-xl font-bold mb-4 text-yellow-400">
                <i class="bi bi-calculator"></i> Example Conversion
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php 
                $exampleAmount = 1000; // 1000 MAD
                foreach ($currencies as $curr): 
                    if (!$curr['is_active']) continue;
                    $converted = $exampleAmount / $curr['rate_to_mad'];
                ?>
                <div class="p-4 bg-gray-700 rounded">
                    <p class="text-sm text-gray-400"><?= $exampleAmount ?> MAD =</p>
                    <p class="text-2xl font-bold text-yellow-400">
                        <?= $curr['symbol'] ?><?= number_format($converted, 2) ?> <?= $curr['code'] ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<script>
</script>
</body>
</html>

