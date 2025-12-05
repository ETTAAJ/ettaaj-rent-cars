<?php 
require_once 'init.php';
require_once 'config.php';

// Get Travel Essentials from database
$stmt_essentials = $pdo->query("SELECT * FROM travel_essentials WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
$travelEssentials = $stmt_essentials->fetchAll(PDO::FETCH_ASSOC);

// Get all cars with images for slider
$sliderCarsStmt = $pdo->prepare("SELECT id, name, image FROM cars WHERE image IS NOT NULL AND image != '' ORDER BY id ASC");
$sliderCarsStmt->execute();
$sliderCars = $sliderCarsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" class="scroll-smooth" <?= $lang === 'ar' ? 'dir="rtl"' : 'dir="ltr"' ?>>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rental Guide – ET TAAJ RENT CARS</title>
  <meta name="keywords" content="rental cars in Morocco, car rental Morocco, rent a car Morocco, car rental Marrakech, car rental Casablanca, Morocco car hire, luxury car rental Morocco, cheap car rental Morocco, car rental Marrakech airport, Morocco vehicle rental" />
  <link rel="icon" href="pub_img/ettaaj-rent-cars.jpeg">

  <!-- Tailwind + Fonts -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: { extend: { colors: { gold: '#FFD700' } } }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    :root {
      --bg: #36454F; --bg-dark: #2C3A44; --card: #36454F; --card-dark: #2C3A44;
      --border: #4A5A66; --primary: #FFFFFF; --muted: #D1D5DB; --gold: #FFD700;
      --text-primary: var(--primary); --text-muted: var(--muted);
    }
    .light {
      --bg: #f8fafc; --bg-dark: #e2e8f0; --card: #ffffff; --card-dark: #f1f5f9;
      --border: #cbd5e1; --primary: #1e293b; --muted: #64748b; --gold: #d97706;
      --text-primary: var(--primary); --text-muted: var(--muted);
    }
    body { background-color: var(--bg); color: var(--primary); font-family: 'Inter', sans-serif; }
    .bg-card { background-color: var(--card); }
    .bg-card-dark { background-color: var(--card-dark); }
    .border-border { border-color: var(--border); }
    .text-primary { color: var(--primary); }
    .text-muted { color: var(--muted); }
    .text-gold { color: var(--gold); }
    
    /* Infinite Car Slider Styles */
    .car-slider-container {
      position: relative;
      overflow: hidden;
      mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
      -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    }
    .car-slider-track {
      display: flex;
      gap: 1.5rem;
      animation: slideCars 40s linear infinite;
      width: fit-content;
      will-change: transform;
    }
    .car-slider-track:hover {
      animation-play-state: paused;
    }
    .car-slide-item {
      flex: 0 0 280px;
      min-width: 280px;
    }
    @keyframes slideCars {
      0% {
        transform: translateX(0);
      }
      100% {
        transform: translateX(-50%);
      }
    }
    @media (max-width: 768px) {
      .car-slide-item {
        flex: 0 0 240px;
        min-width: 240px;
      }
    }
    @media (max-width: 640px) {
      .car-slide-item {
        flex: 0 0 200px;
        min-width: 200px;
      }
    }

    /* 3D Logo Animation - Professional */
    .logo-3d-container {
      perspective: 2000px;
      perspective-origin: center center;
      display: inline-block;
      position: relative;
      padding: 2rem;
    }
    @media (min-width: 1024px) {
      .logo-3d-container {
        padding: 3rem 4rem;
      }
    }
    @media (min-width: 1280px) {
      .logo-3d-container {
        padding: 4rem 5rem;
      }
    }
    @media (min-width: 1536px) {
      .logo-3d-container {
        padding: 5rem 6rem;
      }
    }
    .logo-3d {
      transform-style: preserve-3d;
      animation: logo3dFloat 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
      transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1), filter 0.6s ease;
      position: relative;
      will-change: transform;
    }
    .logo-3d::before {
      content: '';
      position: absolute;
      inset: -20px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255, 178, 44, 0.3) 0%, transparent 70%);
      opacity: 0;
      animation: logoGlow 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
      z-index: -1;
      filter: blur(20px);
    }
    .logo-3d:hover {
      animation-play-state: paused;
      transform: rotateY(20deg) rotateX(10deg) scale(1.1) translateZ(30px);
    }
    .logo-3d:hover::before {
      opacity: 1;
      animation-play-state: paused;
    }
    @keyframes logo3dFloat {
      0% {
        transform: rotateY(0deg) rotateX(0deg) rotateZ(0deg) translateZ(0px) scale(1);
      }
      12.5% {
        transform: rotateY(8deg) rotateX(-5deg) rotateZ(2deg) translateZ(15px) scale(1.02);
      }
      25% {
        transform: rotateY(15deg) rotateX(-8deg) rotateZ(-2deg) translateZ(25px) scale(1.03);
      }
      37.5% {
        transform: rotateY(8deg) rotateX(-5deg) rotateZ(2deg) translateZ(20px) scale(1.02);
      }
      50% {
        transform: rotateY(0deg) rotateX(0deg) rotateZ(0deg) translateZ(30px) scale(1.05);
      }
      62.5% {
        transform: rotateY(-8deg) rotateX(5deg) rotateZ(-2deg) translateZ(20px) scale(1.02);
      }
      75% {
        transform: rotateY(-15deg) rotateX(8deg) rotateZ(2deg) translateZ(25px) scale(1.03);
      }
      87.5% {
        transform: rotateY(-8deg) rotateX(5deg) rotateZ(-2deg) translateZ(15px) scale(1.02);
      }
      100% {
        transform: rotateY(0deg) rotateX(0deg) rotateZ(0deg) translateZ(0px) scale(1);
      }
    }
    @keyframes logoGlow {
      0%, 100% {
        opacity: 0.3;
        transform: scale(0.9);
      }
      25% {
        opacity: 0.5;
        transform: scale(1.1);
      }
      50% {
        opacity: 0.7;
        transform: scale(1.2);
      }
      75% {
        opacity: 0.5;
        transform: scale(1.1);
      }
    }
    .logo-3d img {
      transform-style: preserve-3d;
      filter: drop-shadow(0 15px 40px rgba(255, 178, 44, 0.4)) 
              drop-shadow(0 5px 15px rgba(255, 178, 44, 0.3))
              brightness(1.05) contrast(1.05);
      transition: filter 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s ease;
      animation: logoShadowPulse 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
      will-change: filter, transform;
    }
    .logo-3d:hover img {
      filter: drop-shadow(0 25px 60px rgba(255, 178, 44, 0.6)) 
              drop-shadow(0 10px 25px rgba(255, 178, 44, 0.5))
              brightness(1.15) contrast(1.1);
      transform: scale(1.05);
    }
    @keyframes logoShadowPulse {
      0%, 100% {
        filter: drop-shadow(0 15px 40px rgba(255, 178, 44, 0.4)) 
                drop-shadow(0 5px 15px rgba(255, 178, 44, 0.3))
                brightness(1.05) contrast(1.05);
      }
      25% {
        filter: drop-shadow(0 18px 45px rgba(255, 178, 44, 0.5)) 
                drop-shadow(0 7px 18px rgba(255, 178, 44, 0.4))
                brightness(1.08) contrast(1.06);
      }
      50% {
        filter: drop-shadow(0 20px 50px rgba(255, 178, 44, 0.6)) 
                drop-shadow(0 8px 20px rgba(255, 178, 44, 0.5))
                brightness(1.1) contrast(1.08);
      }
      75% {
        filter: drop-shadow(0 18px 45px rgba(255, 178, 44, 0.5)) 
                drop-shadow(0 7px 18px rgba(255, 178, 44, 0.4))
                brightness(1.08) contrast(1.06);
      }
    }

    /* Hero Image Desktop Fix */
    .hero-section {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .hero-image-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    @media (min-width: 1024px) {
      .hero-section {
        height: auto !important;
        min-height: 80vh;
      }
      .hero-image-wrapper {
        height: auto !important;
        min-height: 80vh;
      }
      .hero-image {
        object-fit: contain !important;
        width: 100% !important;
        height: auto !important;
        min-height: 80vh;
        max-width: 100% !important;
        max-height: none !important;
      }
    }
    @media (min-width: 1280px) {
      .hero-section {
        min-height: 85vh;
      }
      .hero-image-wrapper {
        min-height: 85vh;
      }
      .hero-image {
        min-height: 85vh;
      }
    }

    /* Infinite Car Slider */
    .car-slider-container {
      position: relative;
      overflow: hidden;
      background: var(--bg);
      padding: 2rem 0;
      mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
      -webkit-mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
    }
    .car-slider-track {
      display: flex;
      gap: 1.5rem;
      animation: slideCars 30s linear infinite;
      width: fit-content;
    }
    .car-slider-track:hover {
      animation-play-state: paused;
    }
    .car-slide-item {
      flex: 0 0 280px;
      min-width: 280px;
      height: 180px;
      border-radius: 1rem;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }
    .car-slide-item:hover {
      transform: translateY(-8px) scale(1.05);
      box-shadow: 0 12px 30px rgba(255, 178, 44, 0.4);
    }
    .car-slide-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
    }
    @media (max-width: 768px) {
      .car-slide-item {
        flex: 0 0 220px;
        min-width: 220px;
        height: 150px;
      }
      .car-slider-container {
        padding: 1.5rem 0;
      }
    }
    @media (max-width: 640px) {
      .car-slide-item {
        flex: 0 0 180px;
        min-width: 180px;
        height: 120px;
      }
    }
  </style>
</head>
<body class="min-h-screen">

<?php include 'header.php'; ?>

<!-- HERO SECTION -->
<section class="relative w-full h-[60vh] sm:h-[70vh] lg:min-h-[80vh] xl:min-h-[85vh] overflow-hidden bg-gradient-to-br from-[#1a1a1a] via-[#2d2d2d] to-[#1a1a1a] hero-section">
  <!-- Animated Background -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23FFD700" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-gold/5 via-transparent to-gold/5 animate-pulse"></div>
  </div>
  
  <div class="hero-image-wrapper w-full h-full flex items-center justify-center relative z-10">
    <img src="pub_img/ettaaj-rent-cars.jpeg" 
         alt="ETTAAJ Rent Cars - Premium Car Rental in Morocco" 
         class="hero-image w-full h-full object-cover object-center opacity-30"
         style="display: block;">
  </div>
  
  <!-- Enhanced Gradient Overlay with Content -->
  <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/80 flex flex-col items-center justify-center pointer-events-none z-20">
    <div class="logo-3d-container z-10 pointer-events-auto mb-8" data-aos="zoom-in" data-aos-duration="1000">
      <div class="logo-3d">
        <img src="pub_img/ettaaj-rent-cars.jpeg" 
             alt="ETTAAJ Rent Cars Logo" 
             class="w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 lg:w-56 lg:h-56 xl:w-72 xl:h-72 rounded-full ring-4 ring-gold/60 shadow-2xl object-cover backdrop-blur-sm">
      </div>
    </div>
    <div class="text-center px-4" data-aos="fade-up" data-aos-delay="300">
      <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-white mb-4 drop-shadow-2xl">
        <span class="bg-gradient-to-r from-gold via-yellow-300 to-gold bg-clip-text text-transparent animate-pulse">
          <?= $text['rental_guide'] ?? 'Rental Guide' ?>
        </span>
      </h1>
      <p class="text-lg sm:text-xl md:text-2xl text-gray-300 max-w-2xl mx-auto">
        <?= $text['rental_guide_subtitle'] ?? 'Everything you need to know for a smooth rental experience' ?>
      </p>
    </div>
  </div>
</section>

<!-- INFINITE CAR SLIDER -->
<?php if (!empty($sliderCars)): ?>
<section class="car-slider-container bg-[#353333] border-y border-[#4A5A66]">
  <div class="car-slider-track">
    <?php 
    // Duplicate cars for seamless infinite loop
    $duplicatedCars = array_merge($sliderCars, $sliderCars);
    foreach ($duplicatedCars as $car): 
      $imgUrl = 'https://via.placeholder.com/600x338/36454F/FFFFFF?text=' . urlencode($car['name']);
      if (!empty($car['image']) && is_string($car['image'])) {
        $filename = basename($car['image']);
        $relative = 'uploads/' . $filename;
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $relative;
        if (file_exists($fullPath)) {
          $imgUrl = $relative . '?v=' . filemtime($fullPath);
        } else {
          $imgUrl = $relative;
        }
      }
    ?>
    <div class="car-slide-item">
      <a href="<?= langUrl('car-detail.php', ['id' => (int)$car['id']]) ?>">
        <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES) ?>" 
             alt="<?= htmlspecialchars($car['name']) ?> - ETTAAJ Rent Cars"
             loading="lazy"
             onerror="this.onerror=null;this.src='https://via.placeholder.com/600x338/36454F/FFFFFF?text=<?= urlencode($car['name']) ?>';">
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

    <main class="relative z-10 px-4 sm:px-6 lg:px-8 py-16 space-y-20 max-w-6xl mx-auto">
      <section id="rental-guidelines" data-aos="fade-up">
        <div class="inline-flex items-center gap-4 mb-8 p-4 rounded-2xl bg-gradient-to-r from-gold/10 via-gold/5 to-transparent border border-gold/20">
          <span class="relative inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 text-gold shadow-lg shadow-gold/30 border-2 border-gold/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m3 5H6a2 2 0 01-2-2V5a2 2 0 012-2h8l6 6v10a2 2 0 01-2 2z" />
            </svg>
            <span class="absolute -top-1 -right-1 w-4 h-4 bg-gold rounded-full animate-ping"></span>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-gold/70 font-semibold mb-1"><?= $text['section_01'] ?></p>
            <h2 class="text-3xl sm:text-4xl font-black bg-gradient-to-r from-gold via-yellow-300 to-gold bg-clip-text text-transparent">
              <?= $text['rental_guidelines'] ?>
            </h2>
          </div>
        </div>

        <!-- cards -->
        <div class="grid gap-6 md:grid-cols-2">
          <article class="group p-6 md:p-8 bg-gradient-to-br from-card via-card-dark to-card rounded-3xl border-2 border-border shadow-xl hover:shadow-2xl hover:shadow-gold/30 hover:border-gold/50 transition-all duration-500 hover:scale-[1.02]" data-aos="zoom-in" data-aos-delay="100">
            <div class="flex items-center gap-4 mb-6">
              <span class="flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 text-gold border-2 border-gold/40 shadow-lg group-hover:scale-110 group-hover:shadow-gold/50 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-6 4h6m5-6H5a2 2 0 00-2 2v7a2 2 0 002 2h5" />
                </svg>
              </span>
              <div>
                <h3 class="text-2xl font-bold text-primary group-hover:text-gold transition-colors"><?= $text['pickup_guidelines'] ?></h3>
                <p class="text-muted text-sm mt-1"><?= $text['grace_period_docs'] ?></p>
              </div>
            </div>

            <ul class="space-y-4 text-primary">
              <li class="flex items-start gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold mt-1 text-xl font-bold">•</span>
                <div>
                  <p class="font-semibold text-base"><?= $text['required_documents'] ?></p>
                  <p class="text-sm text-muted mt-1"><?= $text['required_docs_desc'] ?></p>
                </div>
              </li>

              <li class="flex items-start gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold mt-1 text-xl font-bold">•</span>
                <div>
                  <p class="font-semibold text-base"><?= $text['vehicle_inspection'] ?></p>
                  <p class="text-sm text-muted mt-1"><?= $text['vehicle_inspection_desc'] ?></p>
                </div>
              </li>

              <li class="flex items-start gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold mt-1 text-xl font-bold">•</span>
                <div>
                  <p class="font-semibold text-base"><?= $text['grace_duration'] ?></p>
                  <p class="text-sm text-muted mt-1"><?= $text['grace_duration_desc'] ?></p>
                </div>
              </li>
            </ul>
          </article>

          <article class="group p-6 md:p-8 bg-gradient-to-br from-card via-card-dark to-card rounded-3xl border-2 border-border shadow-xl hover:shadow-2xl hover:shadow-gold/30 hover:border-gold/50 transition-all duration-500 hover:scale-[1.02]" data-aos="zoom-in" data-aos-delay="200">
            <div class="flex items-center gap-4 mb-6">
              <span class="flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 text-gold border-2 border-gold/40 shadow-lg group-hover:scale-110 group-hover:shadow-gold/50 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0c0 6-6 9-9 9s-9-3-9-9a9 9 0 1118 0z" />
                </svg>
              </span>
              <div>
                <h3 class="text-2xl font-bold text-primary group-hover:text-gold transition-colors"><?= $text['return_guidelines'] ?></h3>
                <p class="text-muted text-sm mt-1"><?= $text['fuel_inspection_window'] ?></p>
              </div>
            </div>

            <ul class="space-y-4 text-primary">
              <li class="flex items-start gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors"><span class="text-gold mt-1 text-xl font-bold">•</span><div><p class="font-semibold text-base"><?= $text['fuel_requirement'] ?></p><p class="text-sm text-muted mt-1"><?= $text['fuel_requirement_desc'] ?></p></div></li>
              <li class="flex items-start gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors"><span class="text-gold mt-1 text-xl font-bold">•</span><div><p class="font-semibold text-base"><?= $text['final_inspection'] ?></p><p class="text-sm text-muted mt-1"><?= $text['final_inspection_desc'] ?></p></div></li>
              <li class="flex items-start gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors"><span class="text-gold mt-1 text-xl font-bold">•</span><div><p class="font-semibold text-base"><?= $text['return_window'] ?></p><p class="text-sm text-muted mt-1"><?= $text['return_window_desc'] ?></p></div></li>
              <li class="flex items-start gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors"><span class="text-gold mt-1 text-xl font-bold">•</span><div><p class="font-semibold text-base"><?= $text['documentation'] ?></p><p class="text-sm text-muted mt-1"><?= $text['documentation_desc'] ?></p></div></li>
            </ul>
          </article>

          <article class="group p-6 md:p-8 bg-gradient-to-br from-gold/10 via-gold/5 to-transparent rounded-3xl border-2 border-gold/30 shadow-xl hover:shadow-2xl hover:shadow-gold/40 hover:border-gold/50 transition-all duration-500 hover:scale-[1.02] md:col-span-2" data-aos="zoom-in" data-aos-delay="300">
            <div class="flex items-center gap-4 mb-6">
              <span class="flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/30 to-gold/20 text-gold border-2 border-gold/50 shadow-lg group-hover:scale-110 group-hover:shadow-gold/60 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12l4.243-4.243m-5.657 8.485H5a2 2 0 01-2-2V8a2 2 0 012-2h7.586a2 2 0 011.414.586l4.414 4.414a2 2 0 010 2.828l-4.414 4.414a2 2 0 01-1.414.586z" />
                </svg>
              </span>
              <div>
                <h3 class="text-2xl font-bold text-primary group-hover:text-gold transition-colors"><?= $text['extension_policies'] ?></h3>
                <p class="text-muted text-sm mt-1"><?= $text['extend_journey'] ?></p>
              </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4 text-primary">
              <div class="space-y-2 p-4 rounded-xl bg-card-dark/50 border border-gold/20 hover:bg-card-dark hover:border-gold/40 transition-all">
                <p class="font-bold text-lg text-gold"><?= $text['requesting_extension'] ?></p>
                <p class="text-sm text-muted"><?= $text['requesting_extension_desc'] ?></p>
              </div>
              <div class="space-y-2 p-4 rounded-xl bg-card-dark/50 border border-gold/20 hover:bg-card-dark hover:border-gold/40 transition-all">
                <p class="font-bold text-lg text-gold"><?= $text['fees'] ?></p>
                <p class="text-sm text-muted"><?= $text['fees_desc'] ?></p>
              </div>
              <div class="space-y-2 p-4 rounded-xl bg-card-dark/50 border border-gold/20 hover:bg-card-dark hover:border-gold/40 transition-all">
                <p class="font-bold text-lg text-gold"><?= $text['conditions'] ?></p>
                <p class="text-sm text-muted"><?= $text['conditions_desc'] ?></p>
              </div>
            </div>
          </article>
        </div>
      </section>

      <!-- pricing & payment -->
      <section id="pricing-payment" data-aos="fade-up">
        <div class="inline-flex items-center gap-4 mb-8 p-4 rounded-2xl bg-gradient-to-r from-gold/10 via-gold/5 to-transparent border border-gold/20">
          <span class="relative inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 text-gold shadow-lg shadow-gold/30 border-2 border-gold/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.866 0-7 2.239-7 5s3.134 5 7 5 7-2.239 7-5-3.134-5-7-5zm0 0V5m0 15v-3" />
            </svg>
            <span class="absolute -top-1 -right-1 w-4 h-4 bg-gold rounded-full animate-ping"></span>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-gold/70 font-semibold mb-1"><?= $text['section_02'] ?></p>
            <h2 class="text-3xl sm:text-4xl font-black bg-gradient-to-r from-gold via-yellow-300 to-gold bg-clip-text text-transparent">
              <?= $text['pricing_payment'] ?>
            </h2>
          </div>
        </div>

        <div class="bg-gradient-to-br from-card via-card-dark to-card rounded-3xl p-6 lg:p-10 border-2 border-border shadow-2xl" data-aos="zoom-in">
          <div class="grid lg:grid-cols-3 gap-8">
            <div class="space-y-4 p-6 rounded-2xl bg-card-dark/50 border border-gold/20 hover:border-gold/40 transition-all">
              <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
              </div>
              <h3 class="text-xl font-bold text-primary"><?= $text['daily_rates'] ?></h3>
              <p class="text-muted text-sm"><?= $text['daily_rates_desc'] ?></p>
              <div class="p-4 rounded-xl bg-gradient-to-r from-gold/20 to-gold/10 border border-gold/30 text-gold font-bold text-sm uppercase tracking-[0.2em] text-center"><?= $text['rate_charts_available'] ?></div>
            </div>

            <div class="space-y-4 p-6 rounded-2xl bg-card-dark/50 border border-gold/20 hover:border-gold/40 transition-all">
              <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="text-xl font-bold text-primary"><?= $text['additional_fees'] ?></h3>
              <ul class="text-primary text-sm space-y-2">
                <li class="flex items-center gap-2"><span class="text-gold font-bold">•</span> <?= $text['late_return'] ?></li>
                <li class="flex items-center gap-2"><span class="text-gold font-bold">•</span> <?= $text['refuelling_service'] ?></li>
                <li class="flex items-center gap-2"><span class="text-gold font-bold">•</span> <?= $text['cleaning_beyond'] ?></li>
              </ul>
              <p class="text-xs text-muted mt-3"><?= $text['fees_disclosed'] ?></p>
            </div>

            <div class="space-y-4 p-6 rounded-2xl bg-card-dark/50 border border-gold/20 hover:border-gold/40 transition-all">
              <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
              </div>
              <h3 class="text-xl font-bold text-primary"><?= $text['long_term_discounts'] ?></h3>
              <p class="text-muted text-sm"><?= $text['long_term_desc'] ?></p>
            </div>
          </div>

          <div class="mt-10 border-t-2 border-gold/20 pt-8">
            <h3 class="text-2xl font-bold text-primary mb-6 flex items-center gap-3">
              <span class="w-10 h-10 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
              </span>
              <?= $text['payment_options'] ?>
            </h3>
            <div class="grid sm:grid-cols-2 gap-5 text-sm text-primary">
              <div class="flex items-center gap-4 py-4 px-6 rounded-2xl border-2 border-gold/30 bg-gradient-to-r from-gold/10 to-transparent hover:from-gold/20 hover:border-gold/50 transition-all group">
                <span class="text-gold text-2xl font-bold group-hover:scale-110 transition-transform">•</span>
                <span class="font-semibold"><?= $text['visa_mastercard'] ?></span>
              </div>
              <div class="flex items-center gap-4 py-4 px-6 rounded-2xl border-2 border-gold/30 bg-gradient-to-r from-gold/10 to-transparent hover:from-gold/20 hover:border-gold/50 transition-all group">
                <span class="text-gold text-2xl font-bold group-hover:scale-110 transition-transform">•</span>
                <span class="font-semibold"><?= $text['cash'] ?></span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- emergency procedures -->
      <section id="emergency-procedures" data-aos="fade-up">
        <div class="inline-flex items-center gap-4 mb-8 p-4 rounded-2xl bg-gradient-to-r from-gold/10 via-gold/5 to-transparent border border-gold/20">
          <span class="relative inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 text-gold shadow-lg shadow-gold/30 border-2 border-gold/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 00-8 16.32L12 22l8-3.68A10 10 0 0012 2z" />
            </svg>
            <span class="absolute -top-1 -right-1 w-4 h-4 bg-gold rounded-full animate-ping"></span>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-gold/70 font-semibold mb-1"><?= $text['section_03'] ?></p>
            <h2 class="text-3xl sm:text-4xl font-black bg-gradient-to-r from-gold via-yellow-300 to-gold bg-clip-text text-transparent">
              <?= $text['emergency_procedures'] ?>
            </h2>
          </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
          <!-- Accident Procedures -->
          <article class="group p-6 md:p-8 rounded-3xl bg-gradient-to-br from-red-900/20 via-card-dark to-card-dark border-2 border-red-500/30 shadow-xl hover:shadow-2xl hover:shadow-red-500/30 hover:border-red-500/50 transition-all duration-500 hover:scale-[1.02]" data-aos="zoom-in" data-aos-delay="100">
            <div class="flex items-center gap-4 mb-6">
              <span class="flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-red-500/20 to-red-500/10 text-red-400 border-2 border-red-500/40 shadow-lg group-hover:scale-110 group-hover:shadow-red-500/50 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L8 10l-2 7h11l-2-7-1.75 7M12 6V4m0 2a2 2 0 100-4 2 2 0 000 4zm5 4V8a5 5 0 00-10 0v2H5l1 8h12l1-8h-2z" />
                </svg>
              </span>
              <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-400/70 font-semibold mb-1"><?= $text['procedure_one'] ?></p>
                <h3 class="text-2xl font-bold text-red-400"><?= $text['accident_procedures'] ?></h3>
              </div>
            </div>

            <ol class="space-y-3 text-primary text-sm">
              <li class="flex gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-red-400 font-bold min-w-[24px]">1.</span>
                <span><?= $text['accident_step1'] ?></span>
              </li>
              <li class="flex gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-red-400 font-bold min-w-[24px]">2.</span>
                <span><?= $text['accident_step2'] ?> <span class="text-gold font-bold">15</span> <?= $text['accident_step3'] ?> <span class="text-gold font-bold">177</span> <?= $text['accident_step4'] ?></span>
              </li>
              <li class="flex gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-red-400 font-bold min-w-[24px]">3.</span>
                <span><?= $text['accident_step5'] ?></span>
              </li>
              <li class="flex gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-red-400 font-bold min-w-[24px]">4.</span>
                <span><?= $text['accident_step6'] ?></span>
              </li>
              <li class="flex gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-red-400 font-bold min-w-[24px]">5.</span>
                <span><?= $text['accident_step7'] ?> <span class="text-gold font-bold">+212 653 330 752</span>.</span>
              </li>
            </ol>

            <div class="mt-6 p-4 rounded-xl bg-gradient-to-r from-red-500/10 to-transparent border border-red-500/30 text-center text-sm text-muted">
              <?= $text['required_info'] ?>
            </div>
          </article>

          <!-- Breakdown assistance -->
          <article class="group p-6 md:p-8 rounded-3xl bg-gradient-to-br from-gold/10 via-card-dark to-card-dark border-2 border-gold/30 shadow-xl hover:shadow-2xl hover:shadow-gold/30 hover:border-gold/50 transition-all duration-500 hover:scale-[1.02]" data-aos="zoom-in" data-aos-delay="200">
            <div class="flex items-center gap-4 mb-6">
              <span class="flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 text-gold border-2 border-gold/40 shadow-lg group-hover:scale-110 group-hover:shadow-gold/50 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 6L4 10v7h5v-4h6v4h5v-7l-9-4z" />
                </svg>
              </span>
              <div>
                <p class="text-sm uppercase tracking-[0.2em] text-gold/70 font-semibold mb-1"><?= $text['procedure_two'] ?></p>
                <h3 class="text-2xl font-bold text-gold"><?= $text['breakdown_assistance'] ?></h3>
              </div>
            </div>

            <ol class="space-y-3 text-primary text-sm">
              <li class="flex gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold font-bold min-w-[24px]">1.</span>
                <span><?= $text['breakdown_step1'] ?></span>
              </li>
              <li class="flex gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold font-bold min-w-[24px]">2.</span>
                <span><?= $text['breakdown_step2'] ?></span>
              </li>
              <li class="flex gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold font-bold min-w-[24px]">3.</span>
                <span><?= $text['breakdown_step3'] ?></span>
              </li>
              <li class="flex gap-3 p-3 rounded-xl bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold font-bold min-w-[24px]">4.</span>
                <span><?= $text['breakdown_step4'] ?></span>
              </li>
            </ol>

            <div class="mt-6 grid sm:grid-cols-2 gap-4 text-sm">
              <div class="p-4 rounded-xl border-2 border-gold/40 bg-gradient-to-br from-gold/20 to-gold/10 text-gold text-center font-bold shadow-lg">
                <?= $text['towing_included'] ?>
              </div>
              <div class="p-4 rounded-xl border-2 border-border bg-card-dark text-primary text-center font-bold shadow-lg">
                <?= $text['replacement_vehicle'] ?>
              </div>
            </div>
          </article>
        </div>
      </section>

      <!-- insurance coverage -->
      <section id="insurance-coverage" data-aos="fade-up">
        <div class="inline-flex items-center gap-4 mb-8 p-4 rounded-2xl bg-gradient-to-r from-gold/10 via-gold/5 to-transparent border border-gold/20">
          <span class="relative inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 text-gold shadow-lg shadow-gold/30 border-2 border-gold/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.866 0-7 2.239-7 5s3.134 5 7 5 7-2.239 7-5-3.134-5-7-5zm0-6v6m0 10v4" />
            </svg>
            <span class="absolute -top-1 -right-1 w-4 h-4 bg-gold rounded-full animate-ping"></span>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-gold/70 font-semibold mb-1"><?= $text['section_04'] ?></p>
            <h2 class="text-3xl sm:text-4xl font-black bg-gradient-to-r from-gold via-yellow-300 to-gold bg-clip-text text-transparent">
              <?= $text['insurance_coverage'] ?>
            </h2>
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
          <article class="group p-6 md:p-8 rounded-3xl bg-gradient-to-br from-card via-card-dark to-card border-2 border-green-500/30 shadow-xl hover:shadow-2xl hover:shadow-green-500/20 hover:border-green-500/50 transition-all duration-500 hover:scale-[1.02]" data-aos="zoom-in" data-aos-delay="100">
            <div class="flex items-center gap-4 mb-6">
              <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-green-500/20 to-green-500/10 text-green-400 border-2 border-green-500/40 shadow-lg group-hover:scale-110 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.866 0-7 2.239-7 5s3.134 5 7 5 7-2.239 7-5-3.134-5-7-5zm0-6v6m0 10v4" />
                </svg>
              </span>
              <div>
                <h3 class="text-2xl font-bold text-primary"><?= $text['basic_insurance'] ?></h3>
                <p class="text-sm text-green-400 font-bold mt-1"><?= $text['free'] ?> - <?= $text['standard_coverage'] ?></p>
              </div>
            </div>

            <ul class="space-y-3 text-primary text-sm">
              <li class="flex items-center gap-2"><span class="text-green-400 font-bold text-lg">•</span> <?= $text['third_party_liability'] ?></li>
              <li class="flex items-center gap-2"><span class="text-green-400 font-bold text-lg">•</span> <?= $text['basic_collision'] ?></li>
              <li class="flex items-center gap-2"><span class="text-green-400 font-bold text-lg">•</span> <?= $text['standard_theft'] ?></li>
              <li class="flex items-center gap-2"><span class="text-green-400 font-bold text-lg">•</span> <?= $text['support_24_7'] ?> ET TAAJ assistance</li>
            </ul>
          </article>

          <article class="group p-6 md:p-8 rounded-3xl bg-gradient-to-br from-card via-card-dark to-card border-2 border-amber-500/30 shadow-xl hover:shadow-2xl hover:shadow-amber-500/20 hover:border-amber-500/50 transition-all duration-500 hover:scale-[1.02]" data-aos="zoom-in" data-aos-delay="200">
            <div class="flex items-center gap-4 mb-6">
              <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-amber-500/20 to-amber-500/10 text-amber-400 border-2 border-amber-500/40 shadow-lg group-hover:scale-110 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </span>
              <div>
                <h3 class="text-2xl font-bold text-primary"><?= $text['smart_insurance'] ?></h3>
                <p class="text-sm text-amber-400 font-bold mt-1"><?= $text['citadine_rate'] ?></p>
              </div>
            </div>

            <ul class="space-y-3 text-primary text-sm">
              <li class="flex items-center gap-2"><span class="text-amber-400 font-bold text-lg">•</span> <?= $text['all_basic_coverage'] ?></li>
              <li class="flex items-center gap-2"><span class="text-amber-400 font-bold text-lg">•</span> <?= $text['reduced_excess'] ?></li>
              <li class="flex items-center gap-2"><span class="text-amber-400 font-bold text-lg">•</span> <?= $text['window_tire'] ?></li>
              <li class="flex items-center gap-2"><span class="text-amber-400 font-bold text-lg">•</span> <?= $text['personal_accident'] ?></li>
            </ul>
          </article>

          <article class="group p-6 md:p-8 rounded-3xl bg-gradient-to-br from-gold/30 via-gold/20 to-gold/10 border-2 border-gold/50 shadow-2xl hover:shadow-gold/40 hover:border-gold/60 transition-all duration-500 hover:scale-[1.02] relative overflow-hidden" data-aos="zoom-in" data-aos-delay="300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gold/10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
              <div class="flex items-center gap-4 mb-6">
                <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/40 to-gold/30 text-[#67511c] border-2 border-gold/60 shadow-lg group-hover:scale-110 transition-all">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v-6m0 0L9 9m3-3l3 3m-3 6v6m0 0l3-3m-3 3l-3-3" />
                  </svg>
                </span>
                <div>
                  <h3 class="text-2xl font-bold text-primary"><?= $text['premium_insurance'] ?></h3>
                  <p class="text-sm text-amber-400 font-bold mt-1"><?= $text['citadine_rate'] ?></p>
                </div>
              </div>

              <ul class="space-y-3 text-primary text-sm">
                <li class="flex items-center gap-2"><span class="text-gold font-bold text-lg">•</span> <?= $text['all_basic_coverage'] ?></li>
                <li class="flex items-center gap-2"><span class="text-gold font-bold text-lg">•</span> <?= $text['zero_excess'] ?></li>
                <li class="flex items-center gap-2"><span class="text-gold font-bold text-lg">•</span> <?= $text['premium_roadside'] ?></li>
                <li class="flex items-center gap-2"><span class="text-gold font-bold text-lg">•</span> <?= $text['personal_effects'] ?></li>
                <li class="flex items-center gap-2"><span class="text-gold font-bold text-lg">•</span> <?= $text['extended_liability'] ?></li>
              </ul>
            </div>
          </article>
        </div>
        
        <div class="mt-8 p-6 rounded-2xl bg-gradient-to-r from-card-dark via-card to-card-dark border-2 border-gold/20 text-center shadow-lg" data-aos="fade-up">
          <p class="text-sm text-muted">
            <?= $text['insurance_note'] ?>
          </p>
        </div>
      </section>

      <!-- Travel Essentials -->
      <section id="travel-essentials" data-aos="fade-up">
        <div class="inline-flex items-center gap-4 mb-8 p-4 rounded-2xl bg-gradient-to-r from-gold/10 via-gold/5 to-transparent border border-gold/20">
          <span class="relative inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 text-gold shadow-lg shadow-gold/30 border-2 border-gold/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
            <span class="absolute -top-1 -right-1 w-4 h-4 bg-gold rounded-full animate-ping"></span>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-gold/70 font-semibold mb-1"><?= $text['section_05'] ?></p>
            <h2 class="text-3xl sm:text-4xl font-black bg-gradient-to-r from-gold via-yellow-300 to-gold bg-clip-text text-transparent">
              <?= $text['travel_essentials'] ?>
            </h2>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
          <?php if (empty($travelEssentials)): ?>
            <div class="col-span-2 text-center text-muted py-8">
              <p><?= $text['no_travel_essentials'] ?? 'No travel essentials available at the moment.' ?></p>
            </div>
          <?php else: ?>
            <?php foreach ($travelEssentials as $essential): 
              // Get language-specific name and description
              $nameKey = 'name_' . $lang;
              $descKey = 'description_' . $lang;
              $essentialName = !empty($essential[$nameKey]) ? $essential[$nameKey] : ($essential['name_en'] ?? $essential['name'] ?? '');
              $essentialDesc = !empty($essential[$descKey]) ? $essential[$descKey] : ($essential['description_en'] ?? $essential['description'] ?? '');
            ?>
            <article class="group p-6 md:p-8 rounded-3xl bg-gradient-to-br from-card via-card-dark to-card border-2 border-border shadow-xl hover:shadow-2xl hover:shadow-gold/30 hover:border-gold/50 transition-all duration-500 hover:scale-[1.02]" data-aos="zoom-in">
              <div class="flex items-center gap-4 mb-4">
                <div class="flex-shrink-0 w-16 h-16 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 flex items-center justify-center border-2 border-gold/40 group-hover:scale-110 group-hover:shadow-gold/50 transition-all">
                  <?php if ($essential['icon']): ?>
                    <i class="bi <?= htmlspecialchars($essential['icon']) ?> text-3xl text-gold"></i>
                  <?php else: ?>
                    <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  <?php endif; ?>
                </div>
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-primary group-hover:text-gold transition-colors"><?= htmlspecialchars($essentialName) ?></h3>
                  <?php if ($essentialDesc): ?>
                    <p class="text-sm text-muted mt-1"><?= htmlspecialchars($essentialDesc) ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

      <section id="insurance-claims" class="pb-10" data-aos="fade-up">
        <div class="inline-flex items-center gap-4 mb-8 p-4 rounded-2xl bg-gradient-to-r from-gold/10 via-gold/5 to-transparent border border-gold/20">
          <span class="relative inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 text-gold shadow-lg shadow-gold/30 border-2 border-gold/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="absolute -top-1 -right-1 w-4 h-4 bg-gold rounded-full animate-ping"></span>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-gold/70 font-semibold mb-1">Section 06</p>
            <h2 class="text-3xl sm:text-4xl font-black bg-gradient-to-r from-gold via-yellow-300 to-gold bg-clip-text text-transparent">
              Insurance Claims Process
            </h2>
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
          <article class="group p-6 md:p-8 rounded-3xl bg-gradient-to-br from-card via-card-dark to-card border-2 border-border shadow-xl hover:shadow-2xl hover:shadow-gold/30 hover:border-gold/50 transition-all duration-500 hover:scale-[1.02]" data-aos="zoom-in" data-aos-delay="100">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 flex items-center justify-center mb-4 border-2 border-gold/40">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-3 text-gold group-hover:text-yellow-300 transition-colors"><?= $text['how_to_file_claim'] ?></h3>
            <p class="text-sm text-primary leading-relaxed"><?= $text['file_claim_desc'] ?></p>
          </article>

          <article class="group p-6 md:p-8 rounded-3xl bg-gradient-to-br from-card via-card-dark to-card border-2 border-border shadow-xl hover:shadow-2xl hover:shadow-gold/30 hover:border-gold/50 transition-all duration-500 hover:scale-[1.02]" data-aos="zoom-in" data-aos-delay="200">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 flex items-center justify-center mb-4 border-2 border-gold/40">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-4 text-gold group-hover:text-yellow-300 transition-colors"><?= $text['required_documents_claims'] ?></h3>
            <ul class="text-sm text-primary space-y-3">
              <li class="flex items-center gap-2 p-2 rounded-lg bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold font-bold">•</span>
                <?= $text['rental_agreement'] ?>
              </li>
              <li class="flex items-center gap-2 p-2 rounded-lg bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold font-bold">•</span>
                <?= $text['id_license'] ?>
              </li>
              <li class="flex items-center gap-2 p-2 rounded-lg bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold font-bold">•</span>
                <?= $text['official_report'] ?>
              </li>
              <li class="flex items-center gap-2 p-2 rounded-lg bg-card-dark/50 hover:bg-card-dark transition-colors">
                <span class="text-gold font-bold">•</span>
                <?= $text['photos_damages'] ?>
              </li>
            </ul>
          </article>

          <article class="group p-6 md:p-8 rounded-3xl bg-gradient-to-br from-card via-card-dark to-card border-2 border-border shadow-xl hover:shadow-2xl hover:shadow-gold/30 hover:border-gold/50 transition-all duration-500 hover:scale-[1.02]" data-aos="zoom-in" data-aos-delay="300">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold/20 to-gold/10 flex items-center justify-center mb-4 border-2 border-gold/40">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-3 text-gold group-hover:text-yellow-300 transition-colors"><?= $text['coverage_exclusions'] ?></h3>
            <p class="text-sm text-primary leading-relaxed"><?= $text['exclusions_desc'] ?></p>
          </article>
        </div>
      </section>
    </main>

<?php include 'footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 800 });
</script>
</body>
</html>