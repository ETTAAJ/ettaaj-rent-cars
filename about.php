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
  </style>
</head>
<body class="min-h-screen">

<?php include 'header.php'; ?>

<main class="min-h-screen">
  <!-- Hero Section -->
  <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-black py-20 lg:py-32">
    <div class="absolute inset-0 opacity-20">
      <div class="absolute top-0 left-0 w-96 h-96 bg-gold rounded-full filter blur-3xl"></div>
      <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-500 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center" data-aos="fade-up">
        <div class="inline-block mb-6">
          <img src="pub_img/ettaaj-rent-cars.jpeg" 
               alt="ETTAAJ Rent Cars Logo" 
               class="h-24 md:h-32 w-auto mx-auto">
        </div>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-6 leading-tight">
          <?= $text['about_hero_title'] ?? ($lang === 'ar' ? 'من نحن' : ($lang === 'fr' ? 'À Propos' : 'About Us')) ?>
        </h1>
        <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
          <?= $text['about_hero_desc'] ?? ($lang === 'ar' ? 'نحن نقدم أفضل خدمات تأجير السيارات في المغرب مع التزام بالتميز والشفافية' : ($lang === 'fr' ? 'Nous offrons les meilleurs services de location de voitures au Maroc avec un engagement envers l\'excellence et la transparence' : 'We provide the best car rental services in Morocco with a commitment to excellence and transparency')) ?>
        </p>
      </div>
    </div>
    
    <!-- SEO Keywords (Hidden) -->
    <div style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border-width: 0;">
      <h2>Rental Cars in Morocco - Car Rental Morocco - Rent a Car Morocco</h2>
      <p>Best car rental in Morocco. Rent a car in Marrakech, Casablanca, and all Morocco. Luxury and economy car rental with no deposit, free delivery 24/7.</p>
    </div>
  </section>

  <!-- Our Story Section -->
  <section class="py-16 lg:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <!-- Image Side -->
      <div data-aos="fade-right" class="relative">
        <div class="relative rounded-3xl overflow-hidden shadow-2xl">
          <?php
            $storyStmt = $pdo->prepare("SELECT * FROM cars ORDER BY RAND() LIMIT 1");
            $storyStmt->execute();
            $storyCar = $storyStmt->fetch(PDO::FETCH_ASSOC);
            $storyImg = !empty($storyCar['image']) 
              ? 'uploads/' . basename($storyCar['image']) 
              : 'pub_img/ettaaj-rent-cars.jpeg';
          ?>
          <img src="<?= htmlspecialchars($storyImg) ?>" 
               alt="ETTAAJ Rent Cars Story" 
               class="w-full h-[400px] lg:h-[500px] object-cover">
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>
        <!-- Floating Badge -->
        <div class="absolute -bottom-6 -right-6 bg-gold text-black rounded-2xl p-6 shadow-2xl">
          <div class="text-4xl font-black">2024</div>
          <div class="text-sm font-semibold"><?= $lang === 'ar' ? 'سنة التأسيس' : ($lang === 'fr' ? 'Année de fondation' : 'Since') ?></div>
        </div>
      </div>
      
      <!-- Content Side -->
      <div data-aos="fade-left" class="space-y-6">
        <h2 class="text-4xl lg:text-5xl font-black text-primary leading-tight">
          <?= $text['our_story_title'] ?? ($lang === 'ar' ? 'قصتنا' : ($lang === 'fr' ? 'Notre Histoire' : 'Our Story')) ?>
        </h2>
        <div class="w-20 h-1.5 bg-gradient-to-r from-gold to-yellow-500 rounded-full"></div>
        <p class="text-lg text-muted leading-relaxed">
          <?= $text['our_story_desc'] ?? ($lang === 'ar' ? 'تأسست ETTAAJ Rent Cars لتكون الوجهة الأولى لتأجير السيارات في المغرب. نحن نؤمن بأن كل رحلة يجب أن تبدأ بسيارة موثوقة وخدمة استثنائية.' : ($lang === 'fr' ? 'ETTAAJ Rent Cars a été fondée pour être la première destination de location de voitures au Maroc. Nous croyons que chaque voyage doit commencer par une voiture fiable et un service exceptionnel.' : 'ETTAAJ Rent Cars was founded to be Morocco\'s premier car rental destination. We believe every journey should start with a reliable vehicle and exceptional service.')) ?>
        </p>
        <p class="text-lg text-muted leading-relaxed">
          <?= $text['our_story_desc2'] ?? ($lang === 'ar' ? 'من مراكش إلى الدار البيضاء، نخدم الآلاف من العملاء كل عام بأسطول متنوع من السيارات وأسعار شفافة وبدون رسوم مخفية.' : ($lang === 'fr' ? 'De Marrakech à Casablanca, nous servons des milliers de clients chaque année avec une flotte diversifiée, des prix transparents et sans frais cachés.' : 'From Marrakech to Casablanca, we serve thousands of customers annually with a diverse fleet, transparent pricing, and no hidden fees.')) ?>
        </p>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="py-16 bg-card-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
        <div data-aos="zoom-in" class="text-center">
          <div class="text-5xl lg:text-6xl font-black text-gold mb-2">5K+</div>
          <div class="text-muted font-semibold"><?= $lang === 'ar' ? 'عميل سعيد' : ($lang === 'fr' ? 'Clients Satisfaits' : 'Happy Clients') ?></div>
        </div>
        <div data-aos="zoom-in" data-aos-delay="100" class="text-center">
          <div class="text-5xl lg:text-6xl font-black text-gold mb-2">20+</div>
          <div class="text-muted font-semibold"><?= $lang === 'ar' ? 'سيارة متاحة' : ($lang === 'fr' ? 'Voitures Disponibles' : 'Cars Available') ?></div>
        </div>
        <div data-aos="zoom-in" data-aos-delay="200" class="text-center">
          <div class="text-5xl lg:text-6xl font-black text-gold mb-2">24/7</div>
          <div class="text-muted font-semibold"><?= $lang === 'ar' ? 'دعم' : ($lang === 'fr' ? 'Support' : 'Support') ?></div>
        </div>
        <div data-aos="zoom-in" data-aos-delay="300" class="text-center">
          <div class="text-5xl lg:text-6xl font-black text-gold mb-2">2</div>
          <div class="text-muted font-semibold"><?= $lang === 'ar' ? 'مدن رئيسية' : ($lang === 'fr' ? 'Villes Principales' : 'Major Cities') ?></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Mission & Vision - Modern Grid -->
  <section class="py-16 lg:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-8">
      <!-- Mission -->
      <div data-aos="fade-up" class="relative group">
        <div class="absolute inset-0 bg-gradient-to-br from-gold/20 to-yellow-500/20 rounded-3xl transform group-hover:scale-105 transition-transform duration-300"></div>
        <div class="relative bg-card/90 backdrop-blur-xl p-10 rounded-3xl border-2 border-border hover:border-gold transition-all duration-300 h-full">
          <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-gold to-yellow-500 rounded-2xl mb-6 shadow-lg">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-3xl font-black text-primary mb-4"><?= $text['our_mission'] ?></h3>
          <p class="text-muted leading-relaxed text-lg">
            <?= $text['mission_desc'] ?>
          </p>
        </div>
      </div>

      <!-- Vision -->
      <div data-aos="fade-up" data-aos-delay="100" class="relative group">
        <div class="absolute inset-0 bg-gradient-to-br from-gold/20 to-yellow-500/20 rounded-3xl transform group-hover:scale-105 transition-transform duration-300"></div>
        <div class="relative bg-card/90 backdrop-blur-xl p-10 rounded-3xl border-2 border-border hover:border-gold transition-all duration-300 h-full">
          <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-gold to-yellow-500 rounded-2xl mb-6 shadow-lg">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </div>
          <h3 class="text-3xl font-black text-primary mb-4"><?= $text['our_vision'] ?></h3>
          <p class="text-muted leading-relaxed text-lg">
            <?= $text['vision_desc'] ?>
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Core Values - 3 Column Grid -->
  <section class="py-16 lg:py-24 bg-card-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-4xl lg:text-5xl font-black text-primary mb-4">
          <?= $lang === 'ar' ? 'قيمنا الأساسية' : ($lang === 'fr' ? 'Nos Valeurs' : 'Our Core Values') ?>
        </h2>
        <div class="w-20 h-1.5 bg-gradient-to-r from-gold to-yellow-500 rounded-full mx-auto"></div>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <!-- Value 1 -->
        <div data-aos="flip-left" class="group">
          <div class="bg-card/80 backdrop-blur-xl p-8 rounded-3xl border-2 border-border hover:border-gold transition-all duration-300 transform hover:-translate-y-2 h-full">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-gold to-yellow-500 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-black text-primary mb-3"><?= $text['no_hidden_fees'] ?></h3>
            <p class="text-muted leading-relaxed"><?= $text['no_hidden_fees_desc'] ?></p>
          </div>
        </div>

        <!-- Value 2 -->
        <div data-aos="flip-left" data-aos-delay="100" class="group">
          <div class="bg-card/80 backdrop-blur-xl p-8 rounded-3xl border-2 border-border hover:border-gold transition-all duration-300 transform hover:-translate-y-2 h-full">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-gold to-yellow-500 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-black text-primary mb-3"><?= $text['support_24_7'] ?></h3>
            <p class="text-muted leading-relaxed"><?= $text['support_24_7_desc'] ?></p>
          </div>
        </div>

        <!-- Value 3 -->
        <div data-aos="flip-left" data-aos-delay="200" class="group">
          <div class="bg-card/80 backdrop-blur-xl p-8 rounded-3xl border-2 border-border hover:border-gold transition-all duration-300 transform hover:-translate-y-2 h-full">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-gold to-yellow-500 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-black text-primary mb-3"><?= $text['fastest_delivery'] ?></h3>
            <p class="text-muted leading-relaxed"><?= $text['fastest_delivery_desc'] ?></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Car Fleet Showcase -->
  <section class="py-16 lg:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12" data-aos="fade-up">
      <h2 class="text-4xl lg:text-5xl font-black text-primary mb-4">
        <?= $lang === 'ar' ? 'أسطولنا' : ($lang === 'fr' ? 'Notre Flotte' : 'Our Fleet') ?>
      </h2>
      <div class="w-20 h-1.5 bg-gradient-to-r from-gold to-yellow-500 rounded-full mx-auto mb-6"></div>
      <p class="text-xl text-muted max-w-2xl mx-auto">
        <?= $lang === 'ar' ? 'اكتشف مجموعتنا المتنوعة من السيارات الفاخرة والاقتصادية' : ($lang === 'fr' ? 'Découvrez notre collection diversifiée de voitures de luxe et économiques' : 'Discover our diverse collection of luxury and economy vehicles') ?>
      </p>
    </div>

    <?php
      $fleetStmt = $pdo->prepare("SELECT * FROM cars ORDER BY RAND() LIMIT 6");
      $fleetStmt->execute();
      $fleetCars = $fleetStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($fleetCars as $idx => $car): 
        $carImg = !empty($car['image']) 
          ? 'uploads/' . basename($car['image']) 
          : 'https://via.placeholder.com/400x300/FFB22C/000000?text=' . urlencode($car['name']);
      ?>
        <div data-aos="zoom-in" data-aos-delay="<?= $idx * 100 ?>" class="group">
          <div class="relative overflow-hidden rounded-3xl shadow-2xl">
            <img src="<?= htmlspecialchars($carImg) ?>" 
                 alt="<?= htmlspecialchars($car['name']) ?>" 
                 class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500"
                 loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
              <h4 class="text-white text-xl font-bold mb-2"><?= htmlspecialchars($car['name']) ?></h4>
              <a href="car-detail.php?id=<?= $car['id'] ?>&lang=<?= $lang ?>" 
                 class="inline-flex items-center gap-2 text-gold font-semibold hover:gap-3 transition-all">
                <?= $lang === 'ar' ? 'عرض التفاصيل' : ($lang === 'fr' ? 'Voir Détails' : 'View Details') ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-20 bg-gradient-to-br from-gold via-yellow-500 to-orange-500">
    <div class="max-w-4xl mx-auto px-4 text-center" data-aos="zoom-in">
      <h2 class="text-4xl lg:text-5xl font-black text-black mb-6">
        <?= $lang === 'ar' ? 'هل أنت مستعد للبدء؟' : ($lang === 'fr' ? 'Prêt à Commencer?' : 'Ready to Get Started?') ?>
      </h2>
      <p class="text-xl text-black/80 mb-10 max-w-2xl mx-auto">
        <?= $lang === 'ar' ? 'احجز سيارتك اليوم واستمتع بأفضل تجربة تأجير سيارات في المغرب' : ($lang === 'fr' ? 'Réservez votre voiture aujourd\'hui et profitez de la meilleure expérience de location au Maroc' : 'Book your car today and enjoy the best rental experience in Morocco') ?>
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="<?= langUrl('index.php') ?>" 
           class="inline-flex items-center justify-center gap-3 bg-black text-white font-bold text-lg py-5 px-10 rounded-full shadow-2xl hover:scale-105 transition-all duration-300">
          <?= $text['view_cars_airport'] ?>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </a>
        <a href="<?= langUrl('contact.php') ?>" 
           class="inline-flex items-center justify-center gap-3 bg-white text-black font-bold text-lg py-5 px-10 rounded-full shadow-2xl hover:scale-105 transition-all duration-300">
          <?= $lang === 'ar' ? 'اتصل بنا' : ($lang === 'fr' ? 'Contactez-Nous' : 'Contact Us') ?>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
        </a>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

<!-- AOS + Scripts (unchanged) -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 1000, easing: 'ease-out-quart' });
  const observer = new MutationObserver(() => AOS.refreshHard());
  observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
</script>

<!-- Modern About Page Styles -->
<style>
  /* Smooth scroll behavior */
  html {
    scroll-behavior: smooth;
  }
  
  /* Gradient text effect */
  .gradient-text {
    background: linear-gradient(135deg, var(--gold) 0%, #FCD34D 50%, var(--gold) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  /* Hover effects for cards */
  .hover-lift {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  
  .hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(255, 178, 44, 0.3), 0 10px 10px -5px rgba(255, 178, 44, 0.2);
  }
  
  /* Pulse animation for icons */
  @keyframes pulse-gold {
    0%, 100% {
      opacity: 1;
      transform: scale(1);
    }
    50% {
      opacity: 0.8;
      transform: scale(1.05);
    }
  }
  
  .animate-pulse {
    animation: pulse-gold 3s ease-in-out infinite;
  }
  
  /* Floating background orbs */
  @keyframes float-slow {
    0%, 100% {
      transform: translateY(0) translateX(0);
    }
    50% {
      transform: translateY(-20px) translateX(10px);
    }
  }
  
  /* Stats counter animation */
  @keyframes countUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  /* Image hover zoom */
  .image-zoom {
    overflow: hidden;
  }
  
  .image-zoom img {
    transition: transform 0.5s ease;
  }
  
  .image-zoom:hover img {
    transform: scale(1.1);
  }
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
    .text-4xl {
      font-size: 2rem;
    }
    .text-5xl {
      font-size: 2.5rem;
    }
    .text-6xl {
      font-size: 3rem;
    }
  }
</style>
</body>
</html>