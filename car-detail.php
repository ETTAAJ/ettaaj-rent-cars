<?php
require 'config.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: index.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$car) { header("Location: index.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM cars WHERE id != ? ORDER BY RAND() LIMIT 3");
$stmt->execute([$id]);
$similarCars = $stmt->fetchAll(PDO::FETCH_ASSOC);

function carImageUrl($filename): string
{
    if (empty($filename)) return '';
    $path = 'uploads/' . basename($filename);
    $full = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
    $v = file_exists($full) ? '?v=' . filemtime($full) : '';
    return $path . $v;
}

function renderCarCard($car, $index = 0): string
{
    $imgUrl = 'https://via.placeholder.com/600x338/36454F/FFFFFF?text=' . urlencode($car['name']);
    if (!empty($car['image']) && is_string($car['image'])) {
        $filename = basename($car['image']);
        $relative = 'uploads/' . $filename;
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $relative;
        $imgUrl = file_exists($fullPath) ? $relative . '?v=' . filemtime($fullPath) : $relative;
    }
    $delay = 100 + ($index % 8) * 80;
    ob_start(); ?>
    <div data-aos="fade-up" data-aos-delay="<?= $delay ?>" class="group relative bg-card/90 backdrop-blur-md rounded-3xl overflow-hidden shadow-2xl hover:shadow-gold/20 transition-all duration-500 transform hover:-translate-y-2 hover:scale-[1.02] border border-border flex flex-col h-full">
        <div class="relative w-full pt-[56.25%] bg-card-dark overflow-hidden border-b border-border">
            <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($car['name']) ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        </div>
        <div class="p-6 flex-1 flex flex-col">
            <h3 class="text-2xl font-extrabold text-center mb-4"><?= htmlspecialchars($car['name']) ?></h3>
            <div class="flex justify-center gap-8 text-sm mb-4">
                <div class="text-center"><svg class="w-6 h-6 mx-auto mb-1 text-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg><span><?= $car['seats'] ?> Seats</span></div>
                <div class="text-center"><svg class="w-6 h-6 mx-auto mb-1 text-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg><span><?= $car['bags'] ?> Bags</span></div>
            </div>
            <div class="flex justify-center gap-4 mb-6">
                <span class="px-4 py-1 bg-card-dark rounded-full text-sm border border-border"><?= htmlspecialchars($car['gear']) ?></span>
                <span class="px-4 py-1 bg-card-dark rounded-full text-sm border border-border"><?= htmlspecialchars($car['fuel']) ?></span>
            </div>
            <div class="text-center">
                <div class="text-5xl font-black"><?= number_format($car['price_day']) ?></div>
                <span class="inline-block px-4 py-2 bg-gradient-to-r from-gold to-yellow-500 text-black font-bold rounded-full text-sm">MAD/day</span>
            </div>
            <div class="mt-auto pt-6">
                <a href="car-detail.php?id=<?= $car['id'] ?>" class="block text-center bg-gradient-to-r from-gold to-yellow-500 hover:from-yellow-500 hover:to-orange-400 text-black font-bold py-3 rounded-2xl transition transform hover:scale-105">
                    View Details
                </a>
            </div>
        </div>
    </div>
    <?php return ob_get_clean();
}
?>

<?php include 'header.php'; ?>

<style>
  /* SMOOTH 5PX GOLD TAB BAR — ONLY 2 TABS */
  .tab-bar {
    position: relative;
    background: rgba(30, 30, 30, 0.4);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 8px;
    border: 1px solid rgba(255, 215, 0, 0.2);
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
  }
  .tab-bar::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50%;                    /* ← Now 50% (2 tabs only) */
    height: 5px;
    background: linear-gradient(90deg, #FFD700, #FFA500);
    border-radius: 3px;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateX(0%);
    box-shadow: 0 0 15px rgba(255, 215, 0, 0.6);
  }
  .tab-bar.active-details::before { transform: translateX(0%); }
  .tab-bar.active-booking::before { transform: translateX(100%); }

  .tab-item {
    flex: 1;
    padding: 18px 12px;
    text-align: center;
    font-weight: 700;
    font-size: 1.15rem;
    border-radius: 12px;
    transition: all 0.4s ease;
    position: relative;
    z-index: 10;
  }
  .tab-item svg { width: 26px; height: 26px; margin-right: 10px; }
  .tab-item.active { color: #000; }
  .tab-item:not(.active) { color: rgba(255,255,255,0.75); }
  .tab-item:hover:not(.active) { color: #FFD700; }

  @media (max-width: 640px) {
    .tab-item { padding: 14px 8px; font-size: 1rem; }
    .tab-item svg { width: 22px; height: 22px; }
    .tab-item span { display: block; margin-top: 6px; font-size: 0.8rem; }
  }
</style>

<main class="max-w-7xl mx-auto px-4 py-12 bg-[var(--bg)]">

  <!-- 2-TAB GOLD BAR (Add-ons REMOVED) -->
  <div class="max-w-3xl mx-auto mb-16">
    <div class="tab-bar active-details">
      <div class="flex">
        <a href="car-detail.php?id=<?= $car['id'] ?>" class="tab-item flex items-center justify-center active">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5"/></svg>
          <span>Car Details</span>
        </a>
        <a href="booking.php?id=<?= $car['id'] ?>" class="tab-item flex items-center justify-center">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          <span>Booking Details</span>
        </a>
      </div>
    </div>
  </div>

  <!-- Rest of your content (unchanged) -->
  <div class="grid md:grid-cols-2 gap-10 mb-16">
    <div data-aos="fade-right">
      <?php $src = carImageUrl($car['image']) ?: 'https://via.placeholder.com/800x450/36454F/FFFFFF?text=' . urlencode($car['name']); ?>
      <div class="relative w-full pt-[56.25%] bg-card-dark rounded-3xl overflow-hidden shadow-2xl border border-border">
        <img src="<?= $src ?>" alt="<?= htmlspecialchars($car['name']) ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 hover:scale-105">
      </div>
    </div>
    <div data-aos="fade-left" class="flex flex-col justify-center">
      <h1 class="text-4xl font-extrabold mb-6"><?= htmlspecialchars($car['name']) ?></h1>
      <div class="grid grid-cols-2 gap-6 text-lg mb-8">
        <div class="flex items-center gap-3"><svg class="w-8 h-8 text-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg><span><?= $car['seats'] ?> Seats</span></div>
        <div class="flex items-center gap-3"><svg class="w-8 h-8 text-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg><span><?= $car['bags'] ?> Bags</span></div>
        <div class="px-6 py-2 bg-card-dark rounded-full text-center border border-border"><?= $car['gear'] ?></div>
        <div class="px-6 py-2 bg-card-dark rounded-full text-center border border-border"><?= $car['fuel'] ?></div>
      </div>
      <div class="bg-card/90 backdrop-blur-md p-8 rounded-3xl shadow-lg border border-border text-center mb-8">
        <div class="text-6xl font-black mb-4"><?= number_format($car['price_day']) ?></div>
        <span class="px-8 py-3 bg-gradient-to-r from-gold to-yellow-500 text-black font-bold rounded-full text-xl">MAD/day</span>
      </div>
      <a href="booking.php?id=<?= $car['id'] ?>" class="block text-center bg-gradient-to-r from-gold to-yellow-500 hover:from-yellow-500 hover:to-orange-400 text-black font-bold text-xl py-5 rounded-2xl shadow-xl transition transform hover:scale-105">
        Book Now
      </a>
    </div>
  </div>

  <?php if ($similarCars): ?>
  <section class="mt-20">
    <h2 class="text-3xl font-bold text-center mb-10">Other Cars You Might Like</h2>
    <div class="overflow-x-auto pb-4 -mx-4 px-4 scrollbar-hide">
      <div class="flex gap-6 min-w-max items-stretch">
        <?php foreach ($similarCars as $i => $c): ?>
          <div class="w-80 flex-shrink-0"><?= renderCarCard($c, $i) ?></div>
        <?php endforeach; ?>
        <div class="w-80 flex-shrink-0">
          <a href="index.php" class="group block h-full">
            <div class="bg-gradient-to-br from-[var(--gold)]/20 to-card-dark backdrop-blur-md rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-border flex flex-col justify-center items-center p-8 h-full text-center">
              <div class="w-20 h-20 mb-4 rounded-full bg-[var(--gold)]/30 flex items-center justify-center group-hover:bg-[var(--gold)]/40 transition">
                <svg class="w-10 h-10 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </div>
              <h3 class="text-xl font-bold text-primary mb-2">Browse All Cars</h3>
              <p class="text-sm text-muted">Explore our full premium fleet</p>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>
</main>

<?php include 'footer.php'; ?>

<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 800, easing: 'ease-out-quart' });
</script>
</body>
</html>