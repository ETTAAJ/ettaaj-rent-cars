<?php 
require_once 'init.php';
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" class="scroll-smooth" dir="<?= getDir() ?>" style="scroll-behavior: smooth;">
<head>
  <!-- Primary Meta Tags - OPTIMIZED FOR MARRAKECH & CASABLANCA -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <?php
  // SEO Meta Tags - Language & Location Specific for About Page
  $baseUrl = 'https://www.ettaajrentcars.com';
  $currentUrl = $baseUrl . '/about.php' . (isset($_GET['lang']) ? '?lang=' . $lang : '');
  
  // Language-specific titles and descriptions for About page
  $seoData = [
    'en' => [
      'title' => 'About ETTAAJ Rent Cars | Best Car Rental Marrakech & Casablanca Airport - No Deposit',
      'description' => 'Discover ETTAAJ Rent Cars - Morocco\'s trusted car rental agency in Marrakech & Casablanca. Free airport delivery, no deposit, luxury & economy cars, 24/7 WhatsApp support. +212 772 331 080',
      'keywords' => 'about ettaaj rent cars, car rental Marrakech, car rental Casablanca, car rental Morocco, car rental Marrakech airport, car rental Casablanca airport, car rental Marrakech no deposit, car rental Casablanca no deposit, best car rental Marrakech, best car rental Casablanca, car rental agency Marrakech, car rental agency Casablanca'
    ],
    'fr' => [
      'title' => 'À propos ETTAAJ Rent Cars | Meilleure Location Voiture Aéroport Marrakech & Casablanca - Sans Caution',
      'description' => 'Découvrez ETTAAJ Rent Cars - agence de location de voiture de confiance au Maroc à Marrakech et Casablanca. Livraison gratuite à l\'aéroport, sans caution, voitures de luxe et économiques, support WhatsApp 24/7. +212 772 331 080',
      'keywords' => 'à propos ettaaj rent cars, location voiture Marrakech, location voiture Casablanca, location voiture Maroc, location voiture aéroport Marrakech, location voiture aéroport Casablanca, location voiture sans caution Marrakech, location voiture sans caution Casablanca'
    ],
    'ar' => [
      'title' => 'من نحن ETTAAJ Rent Cars | أفضل تأجير سيارات مطار مراكش والدار البيضاء - بدون وديعة',
      'description' => 'اكتشف ETTAAJ Rent Cars - وكالة تأجير سيارات موثوقة في المغرب في مراكش والدار البيضاء. توصيل مجاني للمطار، بدون وديعة، سيارات فاخرة واقتصادية، دعم واتساب 24/7. +212 772 331 080',
      'keywords' => 'من نحن ettaaj rent cars، تأجير سيارات مراكش، تأجير سيارات الدار البيضاء، تأجير سيارات المغرب، تأجير سيارات مطار مراكش، تأجير سيارات مطار الدار البيضاء'
    ]
  ];
  
  $currentSeo = $seoData[$lang] ?? $seoData['en'];
  ?>
  
  <title><?= htmlspecialchars($currentSeo['title']) ?></title>
  <meta name="description" content="<?= htmlspecialchars($currentSeo['description']) ?>">
  <meta name="keywords" content="<?= htmlspecialchars($currentSeo['keywords']) ?>">
  <meta name="author" content="ETTAAJ Rent Cars" />
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
  <meta name="language" content="<?= $lang ?>" />
  <meta name="geo.region" content="MA" />
  <meta name="geo.placename" content="Marrakech, Casablanca" />
  <meta name="geo.position" content="31.6069;-8.0363, 33.5731;-7.5898" />
  <meta name="ICBM" content="31.6069, -8.0363" />

  <!-- Canonical URL -->
  <link rel="canonical" href="<?= htmlspecialchars($currentUrl) ?>" />
  
  <!-- Hreflang Tags -->
  <link rel="alternate" hreflang="en" href="<?= $baseUrl ?>/about.php?lang=en" />
  <link rel="alternate" hreflang="fr" href="<?= $baseUrl ?>/about.php?lang=fr" />
  <link rel="alternate" hreflang="ar" href="<?= $baseUrl ?>/about.php?lang=ar" />
  <link rel="alternate" hreflang="x-default" href="<?= $baseUrl ?>/about.php?lang=en" />

  <!-- Favicon -->
  <link rel="icon" href="pub_img/ettaaj-rent-cars.jpeg" type="image/jpeg" sizes="512x512">
  <link rel="icon" href="pub_img/favicon.ico" type="image/x-icon">

  <!-- Open Graph -->
  <meta property="og:type" content="website" />
  <meta property="og:title" content="<?= htmlspecialchars($currentSeo['title']) ?>" />
  <meta property="og:description" content="<?= htmlspecialchars($currentSeo['description']) ?>" />
  <meta property="og:url" content="<?= htmlspecialchars($currentUrl) ?>" />
  <meta property="og:site_name" content="ETTAAJ Rent Cars" />
  <meta property="og:image" content="<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg" />
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:locale" content="<?= $lang === 'fr' ? 'fr_FR' : ($lang === 'ar' ? 'ar_MA' : 'en_US') ?>" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= htmlspecialchars($currentSeo['title']) ?>" />
  <meta name="twitter:description" content="<?= htmlspecialchars($currentSeo['description']) ?>" />
  <meta name="twitter:image" content="<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg" />

  <!-- Updated Schema - Marrakech & Casablanca -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "CarRentalService",
    "name": "ETTAAJ Rent Cars",
    "alternateName": "ETTAAJ RENT CARS",
    "image": "<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg",
    "url": "<?= $baseUrl ?>",
    "telephone": "+212772331080",
    "description": "<?= htmlspecialchars($currentSeo['description']) ?>",
    "priceRange": "250 MAD - 2000 MAD",
    "areaServed": [
      {
        "@type": "City",
        "name": "Marrakech",
        "addressCountry": "MA"
      },
      {
        "@type": "City",
        "name": "Casablanca",
        "addressCountry": "MA"
      }
    ],
    "address": [
      {
        "@type": "PostalAddress",
        "streetAddress": "Marrakech Menara Airport (RAK) & Gueliz Office",
        "addressLocality": "Marrakech",
        "addressRegion": "Marrakech-Safi",
        "postalCode": "40000",
        "addressCountry": "MA"
      },
      {
        "@type": "PostalAddress",
        "streetAddress": "Casablanca Mohammed V Airport (CMN)",
        "addressLocality": "Casablanca",
        "addressRegion": "Casablanca-Settat",
        "postalCode": "20000",
        "addressCountry": "MA"
      }
    ],
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
      "opens": "00:00",
      "closes": "23:59"
    }
  }
  </script>

  <!-- Tailwind + Fonts + AOS + Bootstrap Icons -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: { extend: { colors: { gold: '#FFB22C' } } }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="format-detection" content="telephone=yes" />
  <meta name="theme-color" content="#36454F" />

  <style>
    :root {
      --primary-color: #FFB22C;
      --secondary-color: #000000;
      --therde: #854836;
      --text-color: #F7F7F7;
      --light-bg: #353333;
      --shadow: 0 5px 15px rgba(246, 176, 0, 0.496);
      --bg: var(--light-bg); --bg-dark: var(--light-bg); --card: var(--light-bg); --card-dark: var(--light-bg);
      --border: #4A5A66; --primary: var(--text-color); --muted: #D1D5DB; --gold: var(--primary-color);
      --text-primary: var(--text-color); --text-muted: var(--muted);
      --card-dark-gradient: linear-gradient(135deg, #0B0B0C 0%, #121212 55%, var(--therde) 120%);
    }
    .car-card-bg { background: #000000 !important; }
    .light {
      --bg: #f8fafc; --bg-dark: #e2e8f0; --card: #EFECE3; --card-dark: #EFECE3;
      --border: #cbd5e1; --primary: #1e293b; --muted: #64748b; --gold: #d97706;
      --text-primary: var(--primary); --text-muted: var(--muted);
    }
    body { background-color: var(--bg); color: var(--primary); font-family: 'Inter', sans-serif; }
    .bg-card { background-color: var(--card); }
    .bg-card-dark { background-color: var(--card-dark); }
    .car-card-bg { background: #000000 !important; }
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
  </style>
</head>
<body class="min-h-screen">

<?php include 'header.php'; ?>

<main class="max-w-7xl mx-auto px-4 py-16 text-center">
  <!-- Hero Section with Logo and Car Slider -->
  <section class="relative overflow-hidden py-16 lg:py-24 mb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Logo -->
      <div class="text-center mb-12" data-aos="fade-down">
        <img src="pub_img/ettaaj-rent-cars.jpeg" 
             alt="ETTAAJ Rent Cars - Rental Cars in Morocco" 
             class="max-w-xs sm:max-w-sm md:max-w-lg lg:max-w-xl mx-auto">
      </div>
      
      <!-- SEO Keywords (Hidden but accessible to search engines) -->
      <div style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border-width: 0;">
        <h1>Rental Cars in Morocco - Car Rental Morocco - Rent a Car Morocco</h1>
        <p>Best car rental in Morocco. Rent a car in Marrakech, Casablanca, and all Morocco. Luxury and economy car rental with no deposit, free delivery 24/7. ETTAAJ Rent Cars offers the best rental cars in Morocco with competitive prices and excellent service.</p>
      </div>
      
      <!-- Infinite Car Images Slider -->
      <?php
        $sliderStmt = $pdo->prepare("SELECT * FROM cars ORDER BY RAND() LIMIT 10");
        $sliderStmt->execute();
        $sliderCars = $sliderStmt->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <?php if (!empty($sliderCars)): ?>
      <div class="relative mt-8" data-aos="fade-up">
        <div class="car-slider-container overflow-hidden py-8">
          <div class="car-slider-track">
            <?php 
            // Duplicate cars multiple times for seamless infinite loop
            $sliderCarsDuplicated = array_merge($sliderCars, $sliderCars, $sliderCars, $sliderCars);
            foreach ($sliderCarsDuplicated as $car): 
              $carImg = !empty($car['image']) 
                ? 'uploads/' . basename($car['image']) 
                : 'https://via.placeholder.com/300x200/000000/FFFFFF?text=' . urlencode($car['name']);
            ?>
              <div class="car-slide-item">
                <div class="relative group">
                  <img src="<?= htmlspecialchars($carImg) ?>" 
                       alt="<?= htmlspecialchars($car['name']) ?> - Rental Cars in Morocco"
                       class="w-full h-48 object-cover rounded-xl border-2 border-[var(--primary-color)]/30 group-hover:border-[var(--primary-color)] transition-all duration-300"
                       loading="lazy"
                       onerror="this.src='https://via.placeholder.com/300x200/000000/FFFFFF?text=<?= urlencode($car['name']) ?>'">
                  <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 rounded-b-xl">
                    <p class="text-white text-sm font-bold text-center"><?= htmlspecialchars($car['name']) ?></p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section id="mission" class="grid md:grid-cols-2 gap-8 mb-16">
    <!-- Our Mission - FIXED ICON -->
    <div data-aos="fade-right" class="bg-card/90 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-border hover:shadow-gold/20 transition transform hover:-translate-y-2">
      <div class="w-16 h-16 bg-gradient-to-br from-gold to-yellow-500 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg animate-pulse">
        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" 
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
      </div>
      <h3 class="font-bold text-2xl mb-4 text-primary"><?= $text['our_mission'] ?></h3>
      <p class="text-muted leading-relaxed">
        <?= $text['mission_desc'] ?>
      </p>
    </div>

    <div data-aos="fade-left" class="bg-card/90 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-border hover:shadow-gold/20 transition transform hover:-translate-y-2">
      <div class="w-16 h-16 bg-gradient-to-br from-gold to-yellow-500 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg animate-pulse">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      </div>
      <h3 class="font-bold text-2xl mb-4 text-primary"><?= $text['our_vision'] ?></h3>
      <p class="text-muted leading-relaxed">
        <?= $text['vision_desc'] ?>
      </p>
    </div>
  </section>

  <!-- Values Grid -->
  <section class="grid md:grid-cols-3 gap-8 mb-16">
    <div data-aos="zoom-in" class="bg-card/90 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-border hover:shadow-gold/20 transition transform hover:-translate-y-2">
      <div class="w-16 h-16 bg-gradient-to-br from-gold to-yellow-500 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg animate-pulse">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
      </div>
      <h3 class="font-bold text-xl mb-2 text-primary"><?= $text['no_hidden_fees'] ?></h3>
      <p class="text-muted"><?= $text['no_hidden_fees_desc'] ?></p>
    </div>

    <div data-aos="zoom-in" data-aos-delay="200" class="bg-card/90 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-border hover:shadow-gold/20 transition transform hover:-translate-y-2">
      <div class="w-16 h-16 bg-gradient-to-br from-gold to-yellow-500 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg animate-pulse">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <h3 class="font-bold text-xl mb-2 text-primary"><?= $text['support_24_7'] ?></h3>
      <p class="text-muted"><?= $text['support_24_7_desc'] ?></p>
    </div>

    <div data-aos="zoom-in" data-aos-delay="400" class="bg-card/90 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-border hover:shadow-gold/20 transition transform hover:-translate-y-2">
      <div class="w-16 h-16 bg-gradient-to-br from-gold to-yellow-500 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg animate-pulse">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      </div>
      <h3 class="font-bold text-xl mb-2 text-primary"><?= $text['fastest_delivery'] ?></h3>
      <p class="text-muted"><?= $text['fastest_delivery_desc'] ?></p>
    </div>
  </section>

  <!-- Final CTA -->
  <div data-aos="fade-up" class="mt-16">
    <a href="<?= langUrl('index.php') ?>" class="inline-flex items-center gap-3 bg-gradient-to-r from-gold to-yellow-500 hover:from-yellow-500 hover:to-orange-400 text-black font-bold text-lg py-5 px-12 rounded-full shadow-2xl transform transition-all duration-300 hover:scale-110">
      <?= $text['view_cars_airport'] ?>
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
    </a>
  </div>
</main>

<?php include 'footer.php'; ?>

<!-- AOS + Scripts (unchanged) -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 1000, easing: 'ease-out-quart' });
  const observer = new MutationObserver(() => AOS.refreshHard());
  observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
</script>

<!-- Original Styles (100% unchanged) -->
<style>
  .particle {
    position: absolute; width: 4px; height: 4px; background: #FFB22C; border-radius: 50%;
    opacity: 0.6; animation: float 6s infinite ease-in-out;
  }
  .particle:nth-child(1) { top: 20%; left: 15%; animation-delay: 0s; }
  .particle:nth-child(2) { top: 60%; left: 80%; animation-delay: 1s; width: 6px; height: 6px; }
  .particle:nth-child(3) { top: 40%; left: 50%; animation-delay: 2s; }
  .particle:nth-child(4) { top: 80%; left: 30%; animation-delay: 3s; width: 5px; height: 5px; }
  .particle:nth-child(5) { top: 30%; left: 70%; animation-delay: 4s; }
  @keyframes float { 0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.6; } 50% { transform: translateY(-20px) rotate(180deg); opacity: 1; } }
  .animate-pulse { animation: pulse 2s infinite; }
  @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
</style>
</body>
</html>