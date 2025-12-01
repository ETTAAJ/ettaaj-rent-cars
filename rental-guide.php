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
<section class="relative w-full h-[60vh] sm:h-[70vh] lg:min-h-[80vh] xl:min-h-[85vh] overflow-hidden bg-[#353333] hero-section">
  <div class="hero-image-wrapper w-full h-full flex items-center justify-center">
    <img src="pub_img/ettaaj-rent-cars.jpeg" 
         alt="ETTAAJ Rent Cars - Premium Car Rental in Morocco" 
         class="hero-image w-full h-full object-cover object-center"
         style="display: block;">
  </div>
  
  <!-- Gradient Overlay with Logo -->
  <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70 flex items-center justify-center pointer-events-none">
    <div class="logo-3d-container z-10 pointer-events-auto">
      <div class="logo-3d">
        <img src="pub_img/ettaaj-rent-cars.jpeg" 
             alt="ETTAAJ Rent Cars Logo" 
             class="w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 lg:w-56 lg:h-56 xl:w-72 xl:h-72 rounded-full ring-4 ring-[var(--gold)]/60 shadow-2xl object-cover backdrop-blur-sm">
      </div>
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
      <section id="rental-guidelines">
        <div class="inline-flex items-center gap-3 mb-6">
          <span class="relative inline-flex items-center justify-center w-10 h-10 rounded-full bg-gold/15 text-gold shadow-gold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m3 5H6a2 2 0 01-2-2V5a2 2 0 012-2h8l6 6v10a2 2 0 01-2 2z" />
            </svg>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted"><?= $text['section_01'] ?></p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gold"><?= $text['rental_guidelines'] ?></h2>
          </div>
        </div>

        <!-- cards -->
        <div class="grid gap-6 md:grid-cols-2">
          <article class="p-6 bg-card rounded-3xl border border-border shadow-lg hover:shadow-gold/40 transition">
            <div class="flex items-center gap-3 mb-5">
              <span class="flex items-center justify-center w-12 h-12 rounded-full bg-gold/10 text-gold border border-gold/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 7V3m8 4V3m-9 8h10m-6 4h6m5-6H5a2 2 0 00-2 2v7a2 2 0 002 2h5" />
                </svg>
              </span>
              <div>
                <h3 class="text-xl font-semibold text-primary"><?= $text['pickup_guidelines'] ?></h3>
                <p class="text-muted text-sm"><?= $text['grace_period_docs'] ?></p>
              </div>
            </div>

            <ul class="space-y-3 text-primary">
              <li class="flex items-start gap-3">
                <span class="text-gold mt-1">•</span>
                <div>
                  <p class="font-medium"><?= $text['required_documents'] ?></p>
                  <p class="text-sm text-muted"><?= $text['required_docs_desc'] ?></p>
                </div>
              </li>

              <li class="flex items-start gap-3">
                <span class="text-gold mt-1">•</span>
                <div>
                  <p class="font-medium"><?= $text['vehicle_inspection'] ?></p>
                  <p class="text-sm text-muted"><?= $text['vehicle_inspection_desc'] ?></p>
                </div>
              </li>

              <li class="flex items-start gap-3">
                <span class="text-gold mt-1">•</span>
                <div>
                  <p class="font-medium"><?= $text['grace_duration'] ?></p>
                  <p class="text-sm text-muted"><?= $text['grace_duration_desc'] ?></p>
                </div>
              </li>
            </ul>
          </article>

          <article class="p-6 bg-card rounded-3xl border border-border shadow-lg hover:shadow-gold/40 transition">
            <div class="flex items-center gap-3 mb-5">
              <span class="flex items-center justify-center w-12 h-12 rounded-full bg-gold/10 text-gold border border-gold/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 12H9m12 0c0 6-6 9-9 9s-9-3-9-9a9 9 0 1118 0z" />
                </svg>
              </span>
              <div>
                <h3 class="text-xl font-semibold text-primary"><?= $text['return_guidelines'] ?></h3>
                <p class="text-muted text-sm"><?= $text['fuel_inspection_window'] ?></p>
              </div>
            </div>

            <ul class="space-y-3 text-primary">
              <li class="flex items-start gap-3"><span class="text-gold mt-1">•</span><div><p class="font-medium"><?= $text['fuel_requirement'] ?></p><p class="text-sm text-muted"><?= $text['fuel_requirement_desc'] ?></p></div></li>
              <li class="flex items-start gap-3"><span class="text-gold mt-1">•</span><div><p class="font-medium"><?= $text['final_inspection'] ?></p><p class="text-sm text-muted"><?= $text['final_inspection_desc'] ?></p></div></li>
              <li class="flex items-start gap-3"><span class="text-gold mt-1">•</span><div><p class="font-medium"><?= $text['return_window'] ?></p><p class="text-sm text-muted"><?= $text['return_window_desc'] ?></p></div></li>
              <li class="flex items-start gap-3"><span class="text-gold mt-1">•</span><div><p class="font-medium"><?= $text['documentation'] ?></p><p class="text-sm text-muted"><?= $text['documentation_desc'] ?></p></div></li>
            </ul>
          </article>

          <article class="p-6 bg-card rounded-3xl border border-border shadow-lg hover:shadow-gold/40 transition md:col-span-2">
            <div class="flex items-center gap-3 mb-5">
              <span class="flex items-center justify-center w-12 h-12 rounded-full bg-gold/10 text-gold border border-gold/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17.657 16.657L13.414 12l4.243-4.243m-5.657 8.485H5a2 2 0 01-2-2V8a2 2 0 012-2h7.586a2 2 0 011.414.586l4.414 4.414a2 2 0 010 2.828l-4.414 4.414a2 2 0 01-1.414.586z" />
                </svg>
              </span>
              <div>
                <h3 class="text-xl font-semibold text-primary"><?= $text['extension_policies'] ?></h3>
                <p class="text-muted text-sm"><?= $text['extend_journey'] ?></p>
              </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4 text-primary">
              <div class="space-y-1">
                <p class="font-medium text-gold"><?= $text['requesting_extension'] ?></p>
                <p class="text-sm text-muted"><?= $text['requesting_extension_desc'] ?></p>
              </div>
              <div class="space-y-1">
                <p class="font-medium text-gold"><?= $text['fees'] ?></p>
                <p class="text-sm text-muted"><?= $text['fees_desc'] ?></p>
              </div>
              <div class="space-y-1">
                <p class="font-medium text-gold"><?= $text['conditions'] ?></p>
                <p class="text-sm text-muted"><?= $text['conditions_desc'] ?></p>
              </div>
            </div>
          </article>
        </div>
      </section>

      <!-- pricing & payment -->
      <section id="pricing-payment">
        <div class="inline-flex items-center gap-3 mb-6">
          <span class="relative inline-flex items-center justify-center w-10 h-10 rounded-full bg-gold/15 text-gold shadow-gold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-3.866 0-7 2.239-7 5s3.134 5 7 5 7-2.239 7-5-3.134-5-7-5zm0 0V5m0 15v-3" />
            </svg>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted"><?= $text['section_02'] ?></p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gold"><?= $text['pricing_payment'] ?></h2>
          </div>
        </div>

        <div class="bg-card rounded-3xl p-6 lg:p-10 border border-border shadow-lg">
          <div class="grid lg:grid-cols-3 gap-8">
            <div class="space-y-3">
              <h3 class="text-lg font-semibold text-primary"><?= $text['daily_rates'] ?></h3>
              <p class="text-muted text-sm"><?= $text['daily_rates_desc'] ?></p>
              <div class="p-4 rounded-2xl bg-card-dark border border-border text-gold text-sm uppercase tracking-[0.2em] text-center"><?= $text['rate_charts_available'] ?></div>
            </div>

            <div class="space-y-3">
              <h3 class="text-lg font-semibold text-primary"><?= $text['additional_fees'] ?></h3>
              <ul class="text-primary text-sm space-y-2">
                <li>• <?= $text['late_return'] ?></li>
                <li>• <?= $text['refuelling_service'] ?></li>
                <li>• <?= $text['cleaning_beyond'] ?></li>
              </ul>
              <p class="text-xs text-muted"><?= $text['fees_disclosed'] ?></p>
            </div>

            <div class="space-y-3">
              <h3 class="text-lg font-semibold text-primary"><?= $text['long_term_discounts'] ?></h3>
              <p class="text-muted text-sm"><?= $text['long_term_desc'] ?></p>
            </div>
          </div>

          <div class="mt-10 border-t border-border pt-8">
            <h3 class="text-lg font-semibold text-primary mb-4"><?= $text['payment_options'] ?></h3>
            <div class="grid sm:grid-cols-2 gap-5 text-sm text-primary">
              <div class="flex items-center gap-3 py-3 px-4 rounded-2xl border border-border bg-card-dark">
                <span class="text-gold text-xl">•</span>
                <?= $text['visa_mastercard'] ?>
              </div>
              <div class="flex items-center gap-3 py-3 px-4 rounded-2xl border border-border bg-card-dark">
                <span class="text-gold text-xl">•</span>
                <?= $text['cash'] ?>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- emergency procedures -->
      <section id="emergency-procedures">
        <div class="inline-flex items-center gap-3 mb-6">
          <span class="relative inline-flex items-center justify-center w-10 h-10 rounded-full bg-gold/15 text-gold shadow-gold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 00-8 16.32L12 22l8-3.68A10 10 0 0012 2z" />
            </svg>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted"><?= $text['section_03'] ?></p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gold"><?= $text['emergency_procedures'] ?></h2>
          </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
          <!-- Accident Procedures -->
          <article class="p-6 md:p-8 rounded-3xl bg-card-dark border border-gold/30 shadow-lg">
            <div class="flex items-center gap-3 mb-5">
              <span class="flex items-center justify-center w-12 h-12 rounded-full bg-gold/15 text-gold border border-gold/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9.75 17L8 10l-2 7h11l-2-7-1.75 7M12 6V4m0 2a2 2 0 100-4 2 2 0 000 4zm5 4V8a5 5 0 00-10 0v2H5l1 8h12l1-8h-2z" />
                </svg>
              </span>
              <div>
                <p class="text-sm uppercase tracking-[0.2em] text-muted"><?= $text['procedure_one'] ?></p>
                <h3 class="text-2xl font-bold text-gold"><?= $text['accident_procedures'] ?></h3>
              </div>
            </div>

            <ol class="space-y-3 text-primary text-sm list-decimal list-inside">
              <li><?= $text['accident_step1'] ?></li>
              <li><?= $text['accident_step2'] ?> <span class="text-gold font-semibold">15</span> <?= $text['accident_step3'] ?> <span class="text-gold font-semibold">177</span> <?= $text['accident_step4'] ?></li>
              <li><?= $text['accident_step5'] ?></li>
              <li><?= $text['accident_step6'] ?></li>
              <li><?= $text['accident_step7'] ?> <span class="text-gold font-semibold">+212 653 330 752</span>.</li>
            </ol>

            <div class="mt-6 p-4 rounded-2xl bg-card border border-border text-center text-sm text-muted">
              <?= $text['required_info'] ?>
            </div>
          </article>

          <!-- Breakdown assistance -->
          <article class="p-6 md:p-8 rounded-3xl bg-card-dark border border-gold/30 shadow-lg">
            <div class="flex items-center gap-3 mb-5">
              <span class="flex items-center justify-center w-12 h-12 rounded-full bg-gold/15 text-gold border border-gold/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M13 6L4 10v7h5v-4h6v4h5v-7l-9-4z" />
                </svg>
              </span>
              <div>
                <p class="text-sm uppercase tracking-[0.2em] text-muted"><?= $text['procedure_two'] ?></p>
                <h3 class="text-2xl font-bold text-gold"><?= $text['breakdown_assistance'] ?></h3>
              </div>
            </div>

            <ol class="space-y-3 text-primary text-sm list-decimal list-inside">
              <li><?= $text['breakdown_step1'] ?></li>
              <li><?= $text['breakdown_step2'] ?></li>
              <li><?= $text['breakdown_step3'] ?></li>
              <li><?= $text['breakdown_step4'] ?></li>
            </ol>

            <div class="mt-6 grid sm:grid-cols-2 gap-4 text-sm">
              <div class="p-4 rounded-2xl border border-gold/30 bg-gold/10 text-gold text-center font-semibold">
                <?= $text['towing_included'] ?>
              </div>
              <div class="p-4 rounded-2xl border border-border bg-card text-primary text-center font-semibold">
                <?= $text['replacement_vehicle'] ?>
              </div>
            </div>
          </article>
        </div>
      </section>

      <!-- insurance coverage -->
      <section id="insurance-coverage">
        <div class="inline-flex items-center gap-3 mb-6">
          <span class="relative inline-flex items-center justify-center w-10 h-10 rounded-full bg-gold/15 text-gold shadow-gold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-3.866 0-7 2.239-7 5s3.134 5 7 5 7-2.239 7-5-3.134-5-7-5zm0-6v6m0 10v4" />
            </svg>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted"><?= $text['section_04'] ?></p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gold"><?= $text['insurance_coverage'] ?></h2>
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
          <article class="p-6 md:p-8 rounded-3xl bg-card border border-border shadow-lg">
            <div class="flex items-center gap-3 mb-4">
              <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gold/15 text-gold border border-gold/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 8c-3.866 0-7 2.239-7 5s3.134 5 7 5 7-2.239 7-5-3.134-5-7-5zm0-6v6m0 10v4" />
                </svg>
              </span>
              <div>
                <h3 class="text-2xl font-bold text-primary"><?= $text['basic_insurance'] ?></h3>
                <p class="text-sm text-green-400 font-semibold"><?= $text['free'] ?> - <?= $text['standard_coverage'] ?></p>
              </div>
            </div>

            <p class="text-sm text-muted mb-3"><?= $text['deposit'] ?>: <strong class="text-primary" dir="ltr"><?= formatPrice(5880) ?></strong></p>
            <ul class="space-y-2 text-primary text-sm">
              <li>• <?= $text['third_party_liability'] ?></li>
              <li>• <?= $text['basic_collision'] ?></li>
              <li>• <?= $text['standard_theft'] ?></li>
              <li>• <?= $text['support_24_7'] ?> ET TAAJ assistance</li>
            </ul>
          </article>

          <article class="p-6 md:p-8 rounded-3xl bg-card border border-border shadow-lg">
            <div class="flex items-center gap-3 mb-4">
              <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gold/15 text-gold border border-gold/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </span>
              <div>
                <h3 class="text-2xl font-bold text-primary"><?= $text['smart_insurance'] ?></h3>
                <p class="text-sm text-amber-400 font-semibold" dir="ltr">+<?= formatPrice(89) ?>/<?= $text['day'] ?></p>
              </div>
            </div>

            <p class="text-sm text-muted mb-3"><?= $text['deposit'] ?>: <strong class="text-primary" dir="ltr"><?= formatPrice(2940) ?></strong></p>
            <ul class="space-y-2 text-primary text-sm">
              <li>• <?= $text['all_basic_coverage'] ?></li>
              <li>• <?= $text['reduced_excess'] ?></li>
              <li>• <?= $text['window_tire'] ?></li>
              <li>• <?= $text['personal_accident'] ?></li>
            </ul>
          </article>

          <article class="p-6 md:p-8 rounded-3xl bg-gradient-to-br from-gold/20 via-gold/10 to-transparent border border-gold/40 shadow-lg">
            <div class="flex items-center gap-3 mb-4">
              <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gold/20 text-[#67511c] border border-gold/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 12v-6m0 0L9 9m3-3l3 3m-3 6v6m0 0l3-3m-3 3l-3-3" />
                </svg>
              </span>
              <div>
                <h3 class="text-2xl font-bold text-primary"><?= $text['premium_insurance'] ?></h3>
                <p class="text-sm text-amber-400 font-semibold" dir="ltr">+<?= formatPrice(144) ?>/<?= $text['day'] ?></p>
              </div>
            </div>

            <p class="text-sm text-muted mb-3"><?= $text['deposit'] ?>: <strong class="text-primary" dir="ltr"><?= formatPrice(980) ?></strong></p>
            <ul class="space-y-2 text-primary text-sm">
              <li>• <?= $text['all_basic_coverage'] ?></li>
              <li>• <?= $text['zero_excess'] ?></li>
              <li>• <?= $text['premium_roadside'] ?></li>
              <li>• <?= $text['personal_effects'] ?></li>
              <li>• <?= $text['extended_liability'] ?></li>
            </ul>
          </article>
        </div>
        
        <div class="mt-6 p-4 rounded-2xl bg-card-dark border border-border text-center">
          <p class="text-sm text-muted">
            <?= $text['insurance_note'] ?>
          </p>
        </div>
      </section>

      <!-- Travel Essentials -->
      <section id="travel-essentials">
        <div class="inline-flex items-center gap-3 mb-6">
          <span class="relative inline-flex items-center justify-center w-10 h-10 rounded-full bg-gold/15 text-gold shadow-gold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted"><?= $text['section_05'] ?></p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gold"><?= $text['travel_essentials'] ?></h2>
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
              
              $priceText = number_format($essential['price'], 2);
              $unitText = $essential['per_day'] ? '/' . $text['day'] : '/rental';
            ?>
            <article class="p-6 rounded-3xl bg-card border border-border shadow-lg hover:shadow-gold/30 transition">
              <div class="flex items-center gap-4 mb-4">
                <?php if ($essential['icon']): ?>
                  <i class="bi <?= htmlspecialchars($essential['icon']) ?> text-3xl text-gold"></i>
                <?php else: ?>
                  <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                <?php endif; ?>
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-primary"><?= htmlspecialchars($essentialName) ?></h3>
                  <?php if ($essentialDesc): ?>
                    <p class="text-sm text-muted"><?= htmlspecialchars($essentialDesc) ?></p>
                  <?php endif; ?>
                </div>
                <span class="text-lg font-bold text-gold" dir="ltr"><?= formatPrice($essential['price'], 2) ?><?= $unitText ?></span>
              </div>
            </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

      <section id="insurance-claims" class="pb-10">
        <div class="inline-flex items-center gap-3 mb-6">
          <span class="relative inline-flex items-center justify-center w-10 h-10 rounded-full bg-gold/15 text-gold shadow-gold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" />
            </svg>
          </span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted">Section 06</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gold">Insurance Claims Process</h2>
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
          <article class="p-6 rounded-3xl bg-card border border-border shadow-lg hover:shadow-gold/30 transition">
            <h3 class="text-xl font-semibold mb-3 text-gold"><?= $text['how_to_file_claim'] ?></h3>
            <p class="text-sm text-primary"><?= $text['file_claim_desc'] ?></p>
          </article>

          <article class="p-6 rounded-3xl bg-card border border-border shadow-lg hover:shadow-gold/30 transition">
            <h3 class="text-xl font-semibold mb-3 text-gold"><?= $text['required_documents_claims'] ?></h3>
            <ul class="text-sm text-primary space-y-2">
              <li>• <?= $text['rental_agreement'] ?></li>
              <li>• <?= $text['id_license'] ?></li>
              <li>• <?= $text['official_report'] ?></li>
              <li>• <?= $text['photos_damages'] ?></li>
            </ul>
          </article>

          <article class="p-6 rounded-3xl bg-card border border-border shadow-lg hover:shadow-gold/30 transition">
            <h3 class="text-xl font-semibold mb-3 text-gold"><?= $text['coverage_exclusions'] ?></h3>
            <p class="text-sm text-primary"><?= $text['exclusions_desc'] ?></p>
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