<?php
  require_once 'init.php';
  require_once 'config.php';

  // ================================================
  // 1. Build Query
  // ================================================
  $search = trim($_GET['search'] ?? '');
  $gear   = $_GET['gear'] ?? '';
  $fuel   = $_GET['fuel'] ?? '';
  $sort   = $_GET['sort'] ?? 'low';

  $where  = [];
  $params = [];

  if ($search !== '') {
      $where[]  = "name LIKE ?";
      $params[] = "%$search%";
  }
  // Map translated gear/fuel values to database values
  $gearMap = [$text['manual'] => 'Manual', $text['automatic'] => 'Automatic'];
  $fuelMap = [$text['diesel'] => 'Diesel', $text['petrol'] => 'Petrol'];
  
  if ($gear !== '' && in_array($gear, ['Manual', 'Automatic'])) {
      $where[]  = "gear = ?";
      $params[] = $gear;
  }
  if ($fuel !== '' && in_array($fuel, ['Diesel', 'Petrol'])) {
      $where[]  = "fuel = ?";
      $params[] = $fuel;
  }

  $order = ($sort === 'high') ? 'price_day DESC' : 'price_day ASC';
  $sql   = "SELECT * FROM cars";
  if (!empty($where)) {
      $sql .= " WHERE " . implode(' AND ', $where);
  }
  $sql .= " ORDER BY $order";

  // ================================================
  // 2. Render Car Card Function — UPDATED
  // ================================================
  function renderCarCard($car, $index = 0): string
  {
      global $text;
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

      $delay = 100 + ($index % 8) * 80;
      $discount = (int)($car['discount'] ?? 0);
      $originalPrice = (float)$car['price_day'];
      $discountedPrice = $discount > 0 ? $originalPrice * (1 - $discount / 100) : $originalPrice;
      $hasDiscount = $discount > 0;

      ob_start(); ?>
      <div data-aos="fade-up" data-aos-delay="<?= $delay ?>" data-aos-duration="700"
           class="group relative car-card-bg backdrop-blur-md rounded-3xl overflow-hidden shadow-2xl hover:shadow-[0_20px_50px_rgba(255,178,44,0.4)]
                  transition-all duration-500 transform hover:-translate-y-2 hover:scale-[1.02]
                  border border-border flex flex-col h-full opacity-100" style="direction: ltr; visibility: visible; box-shadow: 0 10px 30px rgba(255, 178, 44, 0.3);">

          <!-- Car Image -->
          <div class="relative w-full pt-[56.25%] car-card-bg overflow-hidden border-b border-border">
              <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES) ?>"
                   alt="<?= htmlspecialchars($car['name']) ?> - ETTAAJ Rent Cars Marrakech"
                   class="car-image-clear absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                   loading="lazy"
                   decoding="async"
                   style="opacity: 1; visibility: visible; display: block;"
                   onerror="this.onerror=null;this.src='https://via.placeholder.com/600x338/36454F/FFFFFF?text=No+Image';this.classList.add('object-contain','p-8');this.style.opacity='1';this.style.visibility='visible';this.style.display='block';">

              <!-- Discount Badge (Top Right) -->
              <?php if ($hasDiscount): ?>
              <div class="absolute top-3 right-3 z-10 bg-green-600 text-white font-bold text-xs px-3 py-1.5 rounded-full shadow-lg animate-pulse" style="direction: ltr;">
                -<?= $discount ?>%
              </div>
              <?php endif; ?>
          </div>

          <div class="px-5 pb-5 sm:px-6 sm:pb-6 flex-1 flex flex-col car-card-bg">
              <h3 class="text-xl sm:text-2xl font-extrabold text-white mb-2 text-center line-clamp-1">
                  <?= htmlspecialchars($car['name']) ?>
              </h3>

              <!-- Seats & Bags (Fixed Icons) -->
              <div class="flex justify-center gap-6 sm:gap-8 text-muted mb-4 text-xs sm:text-sm">
                  <div class="flex flex-col items-center">
                      <i class="bi bi-person-fill w-5 h-5 mb-1 text-gold"></i>
                      <span class="font-medium text-white"><?= (int)$car['seats'] ?> <?= $text['seats'] ?></span>
                  </div>
                  <div class="flex flex-col items-center">
                      <i class="bi bi-briefcase-fill w-5 h-5 mb-1 text-gold"></i>
                      <span class="font-medium text-white"><?= (int)$car['bags'] ?> <?= $text['bags'] ?></span>
                  </div>
              </div>

              <!-- Gear & Fuel -->
              <div class="flex justify-center gap-4 text-xs text-muted mb-5 font-medium" style="direction: ltr;">
                  <span class="px-3 py-1 bg-card-dark rounded-full text-primary border border-border"><?= $car['gear'] === 'Manual' ? $text['manual'] : ($car['gear'] === 'Automatic' ? $text['automatic'] : htmlspecialchars($car['gear'])) ?></span>
                  <span class="px-3 py-1 bg-card-dark rounded-full text-primary border border-border"><?= $car['fuel'] === 'Diesel' ? $text['diesel'] : ($car['fuel'] === 'Petrol' ? $text['petrol'] : htmlspecialchars($car['fuel'])) ?></span>
              </div>

              <!-- Price Section -->
              <div class="flex flex-col items-center mt-4 mb-3">
                  <div class="flex items-center justify-center gap-3 flex-wrap">
                      <?php if ($hasDiscount): ?>
                        <span class="text-2xl text-muted line-through opacity-70" dir="ltr">
                          <?= formatPrice($originalPrice) ?>
                        </span>
                      <?php endif; ?>

                      <div class="text-4xl sm:text-5xl font-extrabold <?= $hasDiscount ? 'text-green-400' : 'text-white' ?>" dir="ltr">
                        <?= formatPrice($discountedPrice) ?>
                      </div>
                  </div>
                  <span class="inline-block px-4 py-2 bg-gradient-to-r from-gold to-yellow-500 text-black font-bold rounded-full text-sm mt-2">
                    <span dir="ltr"><?= htmlspecialchars($currentCurrencyData['symbol'] ?? 'MAD') ?></span>/<?= $text['day'] ?>
                  </span>

                  <div class="flex gap-3 mt-3 text-xs font-medium">
                      <span class="px-3 py-1 bg-card-dark rounded-full border border-border text-muted">
                          <?= $text['week'] ?>: <strong class="text-primary" dir="ltr"><?= formatPrice((float)$car['price_week']) ?></strong>
                      </span>
                      <span class="px-3 py-1 bg-card-dark rounded-full border border-border text-muted">
                          <?= $text['month'] ?>: <strong class="text-primary" dir="ltr"><?= formatPrice((float)$car['price_month']) ?></strong>
                      </span>
                  </div>
              </div>

              <!-- View Details Button -->
              <div class="mt-auto">
                  <a href="<?= langUrl('car-detail.php', ['id' => (int)$car['id']]) ?>"
                     class="block w-full text-center bg-gradient-to-r from-gold to-yellow-500 hover:from-yellow-500 hover:to-orange-400
                            text-black font-bold py-3 px-6 rounded-2xl shadow-lg transition-all duration-300
                            transform hover:scale-105 active:scale-95">
                      <?= $text['view_details'] ?>
                  </a>
              </div>
          </div>
      </div>
      <?php
      return ob_get_clean();
  }

  // ================================================
  // 3. AJAX Response
  // ================================================
  if (isset($_GET['ajax']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
      $stmt = $pdo->prepare($sql);
      $stmt->execute($params);
      $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $html = '';
      foreach ($cars as $i => $c) {
          $html .= renderCarCard($c, $i);
      }

      // Get counts for filter options based on current search
      $countWhere = [];
      $countParams = [];
      if ($search !== '') {
          $countWhere[] = "name LIKE ?";
          $countParams[] = "%$search%";
      }
      $countWhereClause = !empty($countWhere) ? " WHERE " . implode(' AND ', $countWhere) : "";
      
      $gearCountStmt = $pdo->prepare("SELECT gear, COUNT(*) as count FROM cars $countWhereClause GROUP BY gear");
      $gearCountStmt->execute($countParams);
      $gearCounts = [];
      while ($row = $gearCountStmt->fetch(PDO::FETCH_ASSOC)) {
          $gearCounts[$row['gear']] = (int)$row['count'];
      }
      
      $fuelCountStmt = $pdo->prepare("SELECT fuel, COUNT(*) as count FROM cars $countWhereClause GROUP BY fuel");
      $fuelCountStmt->execute($countParams);
      $fuelCounts = [];
      while ($row = $fuelCountStmt->fetch(PDO::FETCH_ASSOC)) {
          $fuelCounts[$row['fuel']] = (int)$row['count'];
      }
      
      $totalCountStmt = $pdo->prepare("SELECT COUNT(*) as total FROM cars $countWhereClause");
      $totalCountStmt->execute($countParams);
      $totalCount = (int)$totalCountStmt->fetch(PDO::FETCH_ASSOC)['total'];

      header('Content-Type: application/json; charset=utf-8');
      echo json_encode([
          'html' => $html, 
          'count' => count($cars),
          'options' => [
              'total' => $totalCount,
              'gear' => $gearCounts,
              'fuel' => $fuelCounts
          ]
      ]);
      exit;
  }

  // ================================================
  // 5. Normal Page Load
  // ================================================
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  // Get all cars for dropdown list
  $allCarsStmt = $pdo->prepare("SELECT id, name FROM cars ORDER BY name ASC");
  $allCarsStmt->execute();
  $allCars = $allCarsStmt->fetchAll(PDO::FETCH_ASSOC);
  
  // Get all cars with images for slider
  $sliderCarsStmt = $pdo->prepare("SELECT id, name, image FROM cars WHERE image IS NOT NULL AND image != '' ORDER BY id ASC");
  $sliderCarsStmt->execute();
  $sliderCars = $sliderCarsStmt->fetchAll(PDO::FETCH_ASSOC);
  
  // Get counts per option for filter dropdowns
  $countWhere = [];
  $countParams = [];
  if ($search !== '') {
    $countWhere[] = "name LIKE ?";
    $countParams[] = "%$search%";
  }
  $countWhereClause = !empty($countWhere) ? " WHERE " . implode(' AND ', $countWhere) : "";
  
  // Count by gear type
  $gearCountStmt = $pdo->prepare("SELECT gear, COUNT(*) as count FROM cars $countWhereClause GROUP BY gear");
  $gearCountStmt->execute($countParams);
  $gearCounts = [];
  while ($row = $gearCountStmt->fetch(PDO::FETCH_ASSOC)) {
    $gearCounts[$row['gear']] = (int)$row['count'];
  }
  
  // Count by fuel type
  $fuelCountStmt = $pdo->prepare("SELECT fuel, COUNT(*) as count FROM cars $countWhereClause GROUP BY fuel");
  $fuelCountStmt->execute($countParams);
  $fuelCounts = [];
  while ($row = $fuelCountStmt->fetch(PDO::FETCH_ASSOC)) {
    $fuelCounts[$row['fuel']] = (int)$row['count'];
  }
  
  // Total count
  $totalCountStmt = $pdo->prepare("SELECT COUNT(*) as total FROM cars $countWhereClause");
  $totalCountStmt->execute($countParams);
  $totalCount = (int)$totalCountStmt->fetch(PDO::FETCH_ASSOC)['total'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" class="scroll-smooth" dir="<?= getDir() ?>" style="scroll-behavior: smooth;">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <?php
  // SEO Meta Tags - Language & Location Specific
  $baseUrl = 'https://www.ettaajrentcars.com';
  $currentUrl = $baseUrl . $_SERVER['REQUEST_URI'];
  
  // Language-specific titles and descriptions
  $seoData = [
    'en' => [
      'title' => 'Car Rental Marrakech & Casablanca Airport | ETTAAJ Rent Cars – , From 250 MAD/day',
      'description' => 'Best car rental in Marrakech & Casablanca airports. , free delivery 24/7, luxury & economy cars. Instant booking via WhatsApp +212 772 331 080. Rent a car in Morocco today!',
      'keywords' => 'car rental Marrakech, car rental Casablanca, rent a car Morocco, car rental Marrakech airport, car rental Casablanca airport, Morocco car hire, luxury car rental Morocco, cheap car rental Morocco, no deposit car rental Marrakech, no deposit car rental Casablanca, car rental Morocco airport, vehicle rental Marrakech, vehicle rental Casablanca, ETTAAJ rent cars, rent car Marrakech, rent car Casablanca, car rental Marrakech Menara, car rental Casablanca Mohammed V'
    ],
    'fr' => [
      'title' => 'Location de Voiture Aéroport Marrakech & Casablanca | ETTAAJ Rent Cars –  À partir de 250 MAD/jour',
      'description' => 'Meilleure location de voiture aux aéroports de Marrakech et Casablanca.  livraison gratuite 24/7, voitures de luxe et économiques. Réservation instantanée via WhatsApp +212 772 331 080. Louez une voiture au Maroc aujourd\'hui !',
      'keywords' => 'location voiture Marrakech, location voiture Casablanca, location voiture Maroc, location voiture aéroport Marrakech, location voiture aéroport Casablanca, location voiture Marrakech Menara, location voiture Casablanca Mohammed V, location voiture sans caution Marrakech, location voiture sans caution Casablanca, ETTAAJ rent cars, louer voiture Marrakech, louer voiture Casablanca'
    ],
    'ar' => [
      'title' => 'تأجير السيارات مطار مراكش والدار البيضاء | ETTAAJ Rent Cars –  من 250 درهم/يوم',
      'description' => 'أفضل تأجير سيارات في مطارات مراكش والدار البيضاء. بدون وديعة، توصيل مجاني 24/7، سيارات فاخرة واقتصادية. حجز فوري عبر واتساب +212 772 331 080. استأجر سيارة في المغرب اليوم!',
      'keywords' => 'تأجير سيارات مراكش، تأجير سيارات الدار البيضاء، تأجير سيارات المغرب، تأجير سيارات مطار مراكش، تأجير سيارات مطار الدار البيضاء، تأجير سيارات بدون وديعة مراكش، تأجير سيارات بدون وديعة الدار البيضاء، ETTAAJ rent cars'
    ]
  ];
  
  $currentSeo = $seoData[$lang] ?? $seoData['en'];
  ?>
  
  <!-- Google Search Console Verification -->
  <!-- Replace YOUR_VERIFICATION_CODE with the code from Google Search Console -->
  <!-- <meta name="google-site-verification" content="YOUR_VERIFICATION_CODE" /> -->

  <!-- Primary Meta Tags -->
  <title><?= htmlspecialchars($currentSeo['title']) ?></title>
  <meta name="description" content="<?= htmlspecialchars($currentSeo['description']) ?>">
  <meta name="keywords" content="<?= htmlspecialchars($currentSeo['keywords']) ?>">
  <meta name="author" content="ETTAAJ Rent Cars">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <meta name="language" content="<?= $lang ?>">
  <meta name="geo.region" content="MA">
  <meta name="geo.placename" content="Marrakech, Casablanca">
  <meta name="geo.position" content="31.6069;-8.0363, 33.5731;-7.5898">
  <meta name="ICBM" content="31.6069, -8.0363">
  
  <!-- Canonical URL -->
  <link rel="canonical" href="<?= htmlspecialchars($currentUrl) ?>">
  
  <!-- Hreflang Tags for Multi-language SEO -->
  <link rel="alternate" hreflang="en" href="<?= $baseUrl ?><?= str_replace(['?lang=fr', '?lang=ar', '&lang=fr', '&lang=ar'], '', preg_replace('/\?lang=en(&|$)/', '?', preg_replace('/&lang=en$/', '', $_SERVER['REQUEST_URI']))) ?><?= strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?' ?>lang=en">
  <link rel="alternate" hreflang="fr" href="<?= $baseUrl ?><?= str_replace(['?lang=en', '?lang=ar', '&lang=en', '&lang=ar'], '', preg_replace('/\?lang=fr(&|$)/', '?', preg_replace('/&lang=fr$/', '', $_SERVER['REQUEST_URI']))) ?><?= strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?' ?>lang=fr">
  <link rel="alternate" hreflang="ar" href="<?= $baseUrl ?><?= str_replace(['?lang=en', '?lang=fr', '&lang=en', '&lang=fr'], '', preg_replace('/\?lang=ar(&|$)/', '?', preg_replace('/&lang=ar$/', '', $_SERVER['REQUEST_URI']))) ?><?= strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?' ?>lang=ar">
  <link rel="alternate" hreflang="x-default" href="<?= $baseUrl ?><?= str_replace(['?lang=fr', '?lang=ar', '&lang=fr', '&lang=ar'], '', preg_replace('/\?lang=en(&|$)/', '?', preg_replace('/&lang=en$/', '', $_SERVER['REQUEST_URI']))) ?><?= strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?' ?>lang=en">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= htmlspecialchars($currentUrl) ?>">
  <meta property="og:title" content="<?= htmlspecialchars($currentSeo['title']) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($currentSeo['description']) ?>">
  <meta property="og:image" content="<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:locale" content="<?= $lang === 'fr' ? 'fr_FR' : ($lang === 'ar' ? 'ar_MA' : 'en_US') ?>">
  <meta property="og:site_name" content="ETTAAJ Rent Cars">
  
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="<?= htmlspecialchars($currentUrl) ?>">
  <meta name="twitter:title" content="<?= htmlspecialchars($currentSeo['title']) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($currentSeo['description']) ?>">
  <meta name="twitter:image" content="<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg">
  
  <!-- Favicon -->
  <link rel="icon" href="pub_img/ettaaj-rent-cars.jpeg">
  <link rel="apple-touch-icon" href="pub_img/ettaaj-rent-cars.jpeg">
  
  <!-- Structured Data (JSON-LD) for Car Rental Service - Both Locations -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "CarRentalService",
    "name": "ETTAAJ Rent Cars",
    "alternateName": "ETTAAJ RENT CARS",
    "url": "<?= $baseUrl ?>",
    "logo": "<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg",
    "image": "<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg",
    "description": "<?= htmlspecialchars($currentSeo['description']) ?>",
    "telephone": "+212772331080",
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
    "serviceArea": {
      "@type": "GeoCircle",
      "geoMidpoint": {
        "@type": "GeoCoordinates",
        "latitude": "32.59",
        "longitude": "-7.81"
      }
    },
    "hasOfferCatalog": {
      "@type": "OfferCatalog",
      "name": "Car Rental Services",
      "itemListElement": [
        {
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "Car Rental Marrakech Airport",
            "description": "Car rental service at Marrakech Menara Airport (RAK)"
          }
        },
        {
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "Car Rental Casablanca Airport",
            "description": "Car rental service at Casablanca Mohammed V Airport (CMN)"
          }
        }
      ]
    },
    "contactPoint": [
      {
        "@type": "ContactPoint",
        "telephone": "+212-772-331-080",
        "contactType": "customer service",
        "availableLanguage": ["English", "French", "Arabic"],
        "areaServed": ["MA"],
        "hoursAvailable": {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
          "opens": "00:00",
          "closes": "23:59"
        }
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
    "sameAs": [
      "https://www.ettaajrentcars.com"
    ]
  }
  </script>
  
  <!-- Local Business Schema for Marrakech -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "ETTAAJ Rent Cars Marrakech",
    "image": "<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg",
    "telephone": "+212772331080",
    "priceRange": "250 MAD - 2000 MAD",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Marrakech Menara Airport (RAK) & Gueliz Office",
      "addressLocality": "Marrakech",
      "addressRegion": "Marrakech-Safi",
      "postalCode": "40000",
      "addressCountry": "MA"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": "31.6069",
      "longitude": "-8.0363"
    },
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
      "opens": "00:00",
      "closes": "23:59"
    }
  }
  </script>
  
  <!-- Local Business Schema for Casablanca -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "ETTAAJ Rent Cars Casablanca",
    "image": "<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg",
    "telephone": "+212772331080",
    "priceRange": "250 MAD - 2000 MAD",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Casablanca Mohammed V Airport (CMN)",
      "addressLocality": "Casablanca",
      "addressRegion": "Casablanca-Settat",
      "postalCode": "20000",
      "addressCountry": "MA"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": "33.5731",
      "longitude": "-7.5898"
    },
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
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
    .light {
      --bg: #f8fafc; --bg-dark: #e2e8f0; --card: #EFECE3; --card-dark: #EFECE3;
      --border: #cbd5e1; --primary: #1e293b; --muted: #64748b; --gold: #d97706;
      --text-primary: var(--primary); --text-muted: var(--muted);
    }
    body { background-color: var(--bg); color: var(--primary); font-family: 'Inter', sans-serif; }
    .bg-card { background-color: var(--card); }
    .bg-card-dark { background-color: var(--card-dark); }
    .car-card-bg { background: #000000 !important; }
    .light .car-card-bg { background: #EFECE3 !important; }
    .light .car-card-bg .text-white { color: #000000 !important; }
    .light .car-card-bg .text-primary { color: #000000 !important; }
    .light .car-card-bg .text-muted { color: #000000 !important; }
    .light .car-card-bg h3 { color: #000000 !important; }
    .light .car-card-bg .bg-card-dark .text-primary { color: #000000 !important; }
    .border-border { border-color: var(--border); }
    .text-primary { color: var(--primary); }
    .text-muted { color: var(--muted); }
    .text-gold { color: var(--gold); }

    /* Search Dropdown Styles */
    #search {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 12px;
    }
    #search option {
      background: white;
      color: #333;
      padding: 8px;
    }

    /* Responsive Filter Bar */
    #filter-form {
      display: grid;
      grid-template-columns: 1fr;
      gap: 0.75rem;
    }
    @media (min-width: 640px) {
      #filter-form {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
      }
      #filter-form > div:first-child {
        grid-column: span 2;
      }
    }
    @media (min-width: 1024px) {
      #filter-form {
        grid-template-columns: repeat(5, 1fr);
      }
      #filter-form > div:first-child {
        grid-column: span 1;
      }
    }

    .spinner { width: 40px; height: 40px; border: 4px solid var(--bg-dark); border-top: 4px solid var(--gold); border-radius: 50%; animation: spin 1s linear infinite; margin: 40px auto; }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes spin-slow { from { transform: translate(-50%,-50%) rotate(0deg); } to { transform: translate(-50%,-50%) rotate(360deg); } }
    .animate-spin-slow { animation: spin-slow 30s linear infinite; }

    /* Image Clarity */
    .car-image-clear {
      image-rendering: -webkit-optimize-contrast;
      image-rendering: crisp-edges;
      image-rendering: high-quality;
      -webkit-backface-visibility: visible;
      backface-visibility: visible;
      transform: translateZ(0);
      will-change: transform;
      filter: brightness(1.05) contrast(1.05);
      opacity: 1 !important;
      visibility: visible !important;
      display: block !important;
    }
    .car-image-clear:hover {
      filter: brightness(1.1) contrast(1.1);
      opacity: 1 !important;
    }

    /* Hero Video Background */
    .hero-section {
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }
    .hero-image-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
    }
    .hero-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
    }
    video.hero-image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
    }
    /* Hero SEO Text Styling */
    .hero-section h1 {
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8), 0 0 20px rgba(0, 0, 0, 0.5);
      line-height: 1.2;
      letter-spacing: -0.02em;
    }
    .hero-section p {
      text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
    }
    /* Phone Number in Hero - Always LTR and Responsive */
    .hero-section .phone-number {
      direction: ltr !important;
      display: inline-block !important;
      unicode-bidi: embed;
      word-break: keep-all;
      white-space: nowrap;
      font-family: 'Inter', monospace;
      letter-spacing: 0.05em;
    }
    @media (max-width: 640px) {
      .hero-section {
        min-height: 70vh;
        padding-top: 1rem;
        padding-bottom: 1rem;
      }
      .hero-section h1 {
        font-size: 1.25rem;
        line-height: 1.3;
        margin-bottom: 0.75rem;
      }
      .hero-section p {
        font-size: 0.75rem;
        line-height: 1.4;
        margin-bottom: 0.75rem;
      }
      .hero-section .phone-number {
        font-size: 0.75rem;
        display: inline-block;
        margin-top: 0.25rem;
      }
      .logo-3d-container {
        padding: 1rem !important;
      }
      .logo-3d img {
        width: 4rem !important;
        height: 4rem !important;
      }
    }
    @media (min-width: 641px) and (max-width: 768px) {
      .hero-section {
        min-height: 70vh;
      }
      .hero-section h1 {
        font-size: 1.5rem;
        line-height: 1.3;
      }
      .hero-section p {
        font-size: 0.875rem;
        line-height: 1.5;
      }
      .hero-section .phone-number {
        font-size: 0.875rem;
      }
    }
    @media (min-width: 769px) {
      .hero-section .phone-number {
        font-size: inherit;
      }
    }
    @media (min-width: 1024px) {
      .hero-section {
        height: auto !important;
        min-height: 90vh;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        overflow: visible;
        padding-bottom: 2rem;
      }
      .hero-image-wrapper {
        height: 100% !important;
        min-height: 90vh;
      }
      .hero-image {
        min-height: 90vh;
      }
      .hero-section h1 {
        font-size: 2.25rem;
        line-height: 1.2;
        letter-spacing: -0.02em;
        margin-bottom: 1.5rem;
      }
      .hero-section p {
        font-size: 1.25rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
      }
      /* Ensure overlay shows all content */
      .hero-section > div[class*="absolute"] {
        align-items: flex-start !important;
        padding-top: 4rem;
        padding-bottom: 4rem;
      }
    }
    @media (min-width: 1280px) {
      .hero-section {
        min-height: 95vh;
        padding-bottom: 3rem;
      }
      .hero-image-wrapper {
        min-height: 95vh;
      }
      .hero-image {
        min-height: 95vh;
      }
      .hero-section h1 {
        font-size: 3rem;
        line-height: 1.2;
        margin-bottom: 2rem;
      }
      .hero-section p {
        font-size: 1.5rem;
        line-height: 1.5;
        margin-bottom: 2rem;
      }
      /* Ensure overlay shows all content */
      .hero-section > div[class*="absolute"] {
        padding-top: 5rem;
        padding-bottom: 5rem;
      }
    }
    @media (min-width: 1536px) {
      .hero-section {
        min-height: 100vh;
        padding-bottom: 4rem;
      }
      .hero-image-wrapper {
        min-height: 100vh;
      }
      .hero-image {
        min-height: 100vh;
      }
      .hero-section h1 {
        font-size: 3.75rem;
        line-height: 1.1;
        margin-bottom: 2.5rem;
      }
      .hero-section p {
        font-size: 1.875rem;
        line-height: 1.4;
        margin-bottom: 2.5rem;
      }
      .logo-3d-container {
        padding: 4rem 5rem;
      }
      /* Ensure overlay shows all content */
      .hero-section > div[class*="absolute"] {
        padding-top: 6rem;
        padding-bottom: 6rem;
      }
    }

    /* Brand Logos Slider */
    .brand-slider-container {
      position: relative;
      overflow: hidden;
      mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
      -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    }
    .brand-slider {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      padding: 2rem 0;
      animation: scroll 40s linear infinite;
      width: fit-content;
    }
    .brand-slider:hover {
      animation-play-state: paused;
    }
    @keyframes scroll {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    .brand-slider-wrapper {
      display: flex;
      align-items: center;
      gap: 3rem;
      flex-shrink: 0;
    }
    .brand-logo {
      flex-shrink: 0;
      opacity: 0.25;
      transition: opacity 0.3s ease, transform 0.3s ease;
      filter: grayscale(100%) brightness(0.8);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .brand-logo:hover {
      opacity: 1;
      transform: scale(1.15);
      filter: grayscale(0%) brightness(1);
    }
    .brand-logo.active {
      opacity: 1;
      filter: grayscale(0%) brightness(1);
    }
    .brand-logo img {
      width: 140px;
      height: 90px;
      object-fit: contain;
      border-radius: 8px;
      display: block;
    }
    @media (max-width: 1024px) {
      .brand-slider-wrapper { gap: 2.5rem; }
      .brand-logo img { width: 120px; height: 75px; }
    }
    @media (max-width: 768px) {
      .brand-slider-wrapper { gap: 2rem; }
      .brand-logo img { width: 100px; height: 65px; }
    }
    @media (max-width: 640px) {
      .brand-slider-wrapper { gap: 1.5rem; }
      .brand-logo img { width: 90px; height: 60px; }
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
<section class="relative w-full min-h-[70vh] sm:h-[70vh] lg:min-h-[90vh] xl:min-h-[95vh] 2xl:min-h-[100vh] overflow-visible bg-[#353333] hero-section mb-[50px]">
  <div class="hero-image-wrapper w-full h-full flex items-center justify-center">
    <!-- Video Background -->
    <video 
      autoplay 
      loop 
      muted 
      playsinline
      class="hero-image w-full h-full object-cover object-center"
      style="display: block;">
      <source src="vidio/vidio-marrakech.mp4" type="video/mp4">
      <!-- Fallback image if video doesn't load -->
      <img src="pub_img/ettaaj-rent-cars.jpeg" 
           alt="ETTAAJ Rent Cars - Premium Car Rental in Morocco" 
           class="w-full h-full object-cover object-center">
    </video>
  </div>
  
  <!-- SEO Text Overlay -->
  <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/80 flex items-start sm:items-center justify-center pointer-events-none z-20 pt-8 sm:pt-12 md:pt-16 lg:pt-16 xl:pt-20 2xl:pt-24 pb-8 sm:pb-12 md:pb-16 lg:pb-20 xl:pb-24 2xl:pb-28 overflow-y-auto overflow-x-hidden">
    <div class="text-center px-4 sm:px-6 md:px-8 lg:px-12 xl:px-16 2xl:px-20 max-w-5xl lg:max-w-6xl xl:max-w-7xl 2xl:max-w-7xl z-30 w-full mx-auto py-4 lg:py-8">
      <!-- Logo Above Text -->
      <div class="mb-4 sm:mb-6 md:mb-8 lg:mb-6 xl:mb-8 2xl:mb-10 flex justify-center">
        <div class="logo-3d-container">
          <div class="logo-3d">
            <img src="pub_img/ettaaj-rent-cars.jpeg" 
                 alt="ETTAAJ Rent Cars Logo" 
                 class="w-16 h-16 sm:w-24 sm:h-24 md:w-32 md:h-32 lg:w-40 lg:h-40 xl:w-48 xl:h-48 2xl:w-56 2xl:h-56 rounded-full ring-2 sm:ring-4 ring-[var(--gold)]/60 shadow-xl sm:shadow-2xl object-cover backdrop-blur-sm">
          </div>
        </div>
      </div>
      <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl 2xl:text-6xl font-extrabold text-white mb-4 sm:mb-5 md:mb-6 lg:mb-6 xl:mb-8 2xl:mb-10 drop-shadow-2xl leading-tight sm:leading-snug lg:leading-tight xl:leading-tight">
        <?php if ($lang === 'en'): ?>
          Car Rental Marrakech & Casablanca Airport
        <?php elseif ($lang === 'fr'): ?>
          Location de Voiture Aéroport Marrakech & Casablanca
        <?php else: ?>
          تأجير السيارات مطار مراكش والدار البيضاء
        <?php endif; ?>
      </h1>
      <p class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-2xl 2xl:text-3xl text-gold font-bold mb-4 sm:mb-5 md:mb-6 lg:mb-6 xl:mb-8 2xl:mb-10 drop-shadow-lg leading-relaxed lg:leading-relaxed">
        <?php if ($lang === 'en'): ?>
          ETTAAJ Rent Cars  • Free Delivery 24/7 • Luxury & Economy Cars
        <?php elseif ($lang === 'fr'): ?>
          ETTAAJ Rent Cars  • Livraison Gratuite 24/7 • Voitures de Luxe et Économiques
        <?php else: ?>
          ETTAAJ Rent Cars -   • توصيل مجاني 24/7 • سيارات فاخرة واقتصادية
        <?php endif; ?>
      </p>
      <div class="flex flex-wrap justify-center gap-2 sm:gap-3 md:gap-4 lg:gap-6 xl:gap-8 2xl:gap-10 text-xs sm:text-sm md:text-base lg:text-base xl:text-lg 2xl:text-xl mb-4 sm:mb-5 md:mb-6 lg:mb-6 xl:mb-8 2xl:mb-10">
        <span class="bg-gold/20 backdrop-blur-sm text-white px-2.5 sm:px-3 md:px-4 lg:px-5 xl:px-6 py-1.5 sm:py-2 lg:py-2.5 xl:py-3 rounded-full border border-gold/50 font-semibold">
          <?php if ($lang === 'en'): ?>
            Marrakech Menara Airport (RAK)
          <?php elseif ($lang === 'fr'): ?>
            Aéroport Marrakech Menara (RAK)
          <?php else: ?>
            مطار مراكش منارة (RAK)
          <?php endif; ?>
        </span>
        <span class="bg-gold/20 backdrop-blur-sm text-white px-2.5 sm:px-3 md:px-4 lg:px-5 xl:px-6 py-1.5 sm:py-2 lg:py-2.5 xl:py-3 rounded-full border border-gold/50 font-semibold">
          <?php if ($lang === 'en'): ?>
            Casablanca Mohammed V Airport (CMN)
          <?php elseif ($lang === 'fr'): ?>
            Aéroport Casablanca Mohammed V (CMN)
          <?php else: ?>
            مطار الدار البيضاء محمد الخامس (CMN)
          <?php endif; ?>
        </span>
        <span class="bg-gold/20 backdrop-blur-sm text-white px-2.5 sm:px-3 md:px-4 lg:px-5 xl:px-6 py-1.5 sm:py-2 lg:py-2.5 xl:py-3 rounded-full border border-gold/50 font-semibold">
          <?php if ($lang === 'en'): ?>
            From 250 MAD/day
          <?php elseif ($lang === 'fr'): ?>
            À partir de 250 MAD/jour
          <?php else: ?>
            من 250 درهم/يوم
          <?php endif; ?>
        </span>
      </div>
      <p class="mt-4 sm:mt-6 md:mt-8 lg:mt-8 xl:mt-10 2xl:mt-12 mb-4 sm:mb-6 md:mb-8 lg:mb-10 xl:mb-12 2xl:mb-16 text-xs sm:text-sm md:text-base lg:text-base xl:text-lg 2xl:text-xl text-white/90 font-medium drop-shadow-md px-2 sm:px-3 lg:px-4 break-words leading-relaxed">
        <?php if ($lang === 'en'): ?>
          <span class="block sm:inline">Best car rental service in Morocco</span> • <span class="block sm:inline">Instant booking via WhatsApp</span> 
          <a href="https://wa.me/212772331080" class="inline-block phone-number text-gold hover:text-yellow-400 font-bold transition-colors whitespace-nowrap mt-1 sm:mt-0" style="direction: ltr; display: inline-block; unicode-bidi: embed;">+212 772 331 080</a>
        <?php elseif ($lang === 'fr'): ?>
          <span class="block sm:inline">Meilleur service de location de voiture au Maroc</span> • <span class="block sm:inline">Réservation instantanée via WhatsApp</span> 
          <a href="https://wa.me/212772331080" class="inline-block phone-number text-gold hover:text-yellow-400 font-bold transition-colors whitespace-nowrap mt-1 sm:mt-0" style="direction: ltr; display: inline-block; unicode-bidi: embed;">+212 772 331 080</a>
        <?php else: ?>
          <span class="block sm:inline">أفضل خدمة تأجير سيارات في المغرب</span> • <span class="block sm:inline">حجز فوري عبر واتساب</span> 
          <a href="https://wa.me/212772331080" class="inline-block phone-number text-gold hover:text-yellow-400 font-bold transition-colors whitespace-nowrap mt-1 sm:mt-0" style="direction: ltr; display: inline-block; unicode-bidi: embed;">+212 772 331 080</a>
        <?php endif; ?>
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

<!-- CARS SECTION -->
<section id="cars" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
  <div data-aos="fade-up" class="relative bg-gradient-to-br from-[#1a1a1a] via-[#2a2a2a] to-[#1f1f1f] p-8 sm:p-10 rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.8)] mb-8 border border-gold/20 backdrop-blur-xl overflow-hidden group">
    <!-- Animated gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-gold/10 via-transparent to-gold/10 pointer-events-none animate-pulse"></div>
    <!-- Animated shimmer effect -->
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-gold/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 pointer-events-none"></div>
    <!-- Subtle pattern overlay -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    <!-- Glow effect -->
    <div class="absolute -inset-1 bg-gradient-to-r from-gold/20 via-gold/10 to-gold/20 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-500"></div>
    
    <!-- Header -->
    <div class="relative mb-8 pb-6 border-b border-[#4A5A66]/30">
      <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold/50 to-transparent"></div>
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
          <h3 class="text-2xl sm:text-3xl font-extrabold text-white flex items-center gap-3 mb-2">
            <div class="relative">
              <svg class="w-7 h-7 text-gold drop-shadow-[0_0_10px_rgba(255,215,0,0.5)] animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
              </svg>
              <div class="absolute inset-0 bg-gold/30 blur-xl"></div>
            </div>
            <span class="bg-gradient-to-r from-white via-gold/90 to-white bg-clip-text text-transparent"><?= $text['browse_cars'] ?? 'Browse Cars' ?></span>
          </h3>
          <p class="text-muted text-sm sm:text-base mt-1 flex items-center gap-2">
            <span class="w-1 h-1 bg-gold rounded-full"></span>
            <?= $text['search_car'] ?? 'Search and filter our fleet' ?>
          </p>
        </div>
        <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-gold/10 border border-gold/30 rounded-xl backdrop-blur-sm">
          <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span class="text-gold text-sm font-semibold">Premium Selection</span>
        </div>
      </div>
    </div>
    
    <form id="filter-form" class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 sm:gap-6">
      <!-- Car Dropdown - Full width on mobile, spans 2 columns on md -->
      <div class="flex flex-col gap-3 sm:col-span-2 lg:col-span-1 group/item">
        <label for="search" class="text-muted text-xs font-bold uppercase tracking-widest flex items-center gap-2.5 group-hover/item:text-gold/80 transition-colors">
          <div class="relative">
            <svg class="w-5 h-5 text-gold drop-shadow-[0_0_8px_rgba(255,215,0,0.4)] group-hover/item:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <?= $text['cars'] ?? 'Car' ?>
        </label>
        <div class="relative">
          <select id="search" class="w-full p-4 bg-gradient-to-br from-[#1a1a1a]/90 to-[#2a2a2a]/90 border-2 border-[#4A5A66]/40 text-white rounded-2xl focus:ring-4 focus:ring-gold/30 focus:border-gold/60 transition-all duration-300 text-sm cursor-pointer hover:border-gold/40 hover:shadow-[0_0_20px_rgba(255,215,0,0.1)] backdrop-blur-md group-hover/item:border-gold/30" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 12 12'%3E%3Cpath fill='%23FFD700' d='M6 9L1 4h10z'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 1.25rem center; background-size: 14px; padding-right: 3rem;">
          <option value="" class="bg-[#1a1a1a] text-white">All</option>
          <?php foreach ($allCars as $carOption): ?>
            <option value="<?= htmlspecialchars($carOption['name']) ?>" <?= $search === $carOption['name'] ? 'selected' : '' ?> class="bg-[#1a1a1a] text-white">
              <?= htmlspecialchars($carOption['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="absolute inset-0 rounded-2xl bg-gold/0 group-hover/item:bg-gold/5 transition-colors pointer-events-none"></div>
        </div>
      </div>
      
      <!-- Gear Select -->
      <div class="flex flex-col gap-3 group/item">
        <label for="gear" class="text-muted text-xs font-bold uppercase tracking-widest flex items-center gap-2.5 group-hover/item:text-gold/80 transition-colors">
          <div class="relative">
            <svg class="w-5 h-5 text-gold drop-shadow-[0_0_8px_rgba(255,215,0,0.4)] group-hover/item:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
          </div>
          <?= $text['transmission'] ?? 'Transmission' ?>
        </label>
        <div class="relative">
          <select id="gear" class="w-full p-4 bg-gradient-to-br from-[#1a1a1a]/90 to-[#2a2a2a]/90 border-2 border-[#4A5A66]/40 text-white rounded-2xl focus:ring-4 focus:ring-gold/30 focus:border-gold/60 transition-all duration-300 text-sm cursor-pointer hover:border-gold/40 hover:shadow-[0_0_20px_rgba(255,215,0,0.1)] backdrop-blur-md group-hover/item:border-gold/30">
          <option value="" class="bg-[#1a1a1a] text-white"><?= $text['all_transmission'] ?> (<?= formatNumber($totalCount) ?>)</option>
          <option value="Manual" <?= $gear==='Manual'?'selected':'' ?> class="bg-[#1a1a1a] text-white"><?= $text['manual'] ?> (<?= formatNumber($gearCounts['Manual'] ?? 0) ?>)</option>
          <option value="Automatic" <?= $gear==='Automatic'?'selected':'' ?> class="bg-[#1a1a1a] text-white"><?= $text['automatic'] ?> (<?= formatNumber($gearCounts['Automatic'] ?? 0) ?>)</option>
        </select>
        <div class="absolute inset-0 rounded-2xl bg-gold/0 group-hover/item:bg-gold/5 transition-colors pointer-events-none"></div>
        </div>
      </div>
      
      <!-- Fuel Select -->
      <div class="flex flex-col gap-3 group/item">
        <label for="fuel" class="text-muted text-xs font-bold uppercase tracking-widest flex items-center gap-2.5 group-hover/item:text-gold/80 transition-colors">
          <div class="relative">
            <svg class="w-5 h-5 text-gold drop-shadow-[0_0_8px_rgba(255,215,0,0.4)] group-hover/item:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <?= $text['fuel'] ?? 'Fuel' ?>
        </label>
        <div class="relative">
          <select id="fuel" class="w-full p-4 bg-gradient-to-br from-[#1a1a1a]/90 to-[#2a2a2a]/90 border-2 border-[#4A5A66]/40 text-white rounded-2xl focus:ring-4 focus:ring-gold/30 focus:border-gold/60 transition-all duration-300 text-sm cursor-pointer hover:border-gold/40 hover:shadow-[0_0_20px_rgba(255,215,0,0.1)] backdrop-blur-md group-hover/item:border-gold/30">
          <option value="" class="bg-[#1a1a1a] text-white"><?= $text['all_fuel'] ?> (<?= formatNumber($totalCount) ?>)</option>
          <option value="Diesel" <?= $fuel==='Diesel'?'selected':'' ?> class="bg-[#1a1a1a] text-white"><?= $text['diesel'] ?> (<?= formatNumber($fuelCounts['Diesel'] ?? 0) ?>)</option>
          <option value="Petrol" <?= $fuel==='Petrol'?'selected':'' ?> class="bg-[#1a1a1a] text-white"><?= $text['petrol'] ?> (<?= formatNumber($fuelCounts['Petrol'] ?? 0) ?>)</option>
        </select>
        <div class="absolute inset-0 rounded-2xl bg-gold/0 group-hover/item:bg-gold/5 transition-colors pointer-events-none"></div>
        </div>
      </div>
      
      <!-- Sort Select -->
      <div class="flex flex-col gap-3 group/item">
        <label for="sort" class="text-muted text-xs font-bold uppercase tracking-widest flex items-center gap-2.5 group-hover/item:text-gold/80 transition-colors">
          <div class="relative">
            <svg class="w-5 h-5 text-gold drop-shadow-[0_0_8px_rgba(255,215,0,0.4)] group-hover/item:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
            </svg>
          </div>
          <?= $text['sort'] ?? 'Sort' ?>
        </label>
        <div class="relative">
          <select id="sort" class="w-full p-4 bg-gradient-to-br from-[#1a1a1a]/90 to-[#2a2a2a]/90 border-2 border-[#4A5A66]/40 text-white rounded-2xl focus:ring-4 focus:ring-gold/30 focus:border-gold/60 transition-all duration-300 text-sm cursor-pointer hover:border-gold/40 hover:shadow-[0_0_20px_rgba(255,215,0,0.1)] backdrop-blur-md group-hover/item:border-gold/30">
          <option value="low" <?= $sort==='low'?'selected':'' ?> class="bg-[#1a1a1a] text-white"><?= $text['low_to_high'] ?></option>
          <option value="high" <?= $sort==='high'?'selected':'' ?> class="bg-[#1a1a1a] text-white"><?= $text['high_to_low'] ?></option>
        </select>
        <div class="absolute inset-0 rounded-2xl bg-gold/0 group-hover/item:bg-gold/5 transition-colors pointer-events-none"></div>
        </div>
      </div>
      
      <!-- Clear Button -->
      <div class="flex flex-col gap-3">
        <label class="text-muted text-xs font-bold uppercase tracking-widest opacity-0 pointer-events-none">Action</label>
        <a href="?" class="relative w-full bg-gradient-to-r from-gold/25 via-gold/20 to-gold/15 hover:from-gold/35 hover:via-gold/30 hover:to-gold/25 text-gold font-bold py-4 rounded-2xl text-center transition-all duration-300 border-2 border-gold/40 hover:border-gold/60 flex items-center justify-center gap-2.5 group/btn overflow-hidden shadow-[0_4px_15px_rgba(255,215,0,0.2)] hover:shadow-[0_6px_25px_rgba(255,215,0,0.4)] hover:scale-[1.02]">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700"></div>
          <svg class="w-5 h-5 group-hover/btn:rotate-180 transition-transform duration-500 drop-shadow-[0_0_8px_rgba(255,215,0,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          <span class="relative z-10"><?= $text['clear'] ?></span>
        </a>
      </div>
    </form>
  </div>

  <p id="results-count" class="text-center text-muted text-lg mb-8"><span dir="ltr"><?= formatNumber(count($cars)) ?></span> <?= $text['vehicles_available'] ?></p>

  <div id="cars-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    <?php foreach ($cars as $i => $c): ?>
      <?= renderCarCard($c, $i) ?>
    <?php endforeach; ?>
  </div>
</section>


<?php include 'footer.php'; ?>

<!-- SCRIPTS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 800 });

  const els = { search: document.getElementById('search'), gear: document.getElementById('gear'), fuel: document.getElementById('fuel'), sort: document.getElementById('sort') };
  const container = document.getElementById('cars-container');
  const countEl = document.getElementById('results-count');
  let isLoading = false;

  const fetchCars = () => {
    if (isLoading) return;
    isLoading = true;

    const params = new URLSearchParams({
      search: els.search.value || '',
      gear: els.gear.value,
      fuel: els.fuel.value,
      sort: els.sort.value,
      ajax: 1
    });

    container.innerHTML = '<div class="col-span-full flex justify-center"><div class="spinner"></div></div>';

    fetch(`?${params}`)
      .then(r => r.json())
      .then(data => {
        container.innerHTML = data.html || '<p class="col-span-full text-center text-muted"><?= $text['no_cars_found'] ?></p>';
        countEl.innerHTML = `<span dir="ltr">${data.count}</span> <?= $text['vehicles_available'] ?>`;
        
        // Update filter option counts
        if (data.options) {
          const gearSelect = els.gear;
          const fuelSelect = els.fuel;
          
          // Update gear options
          Array.from(gearSelect.options).forEach(opt => {
            if (opt.value === '') {
              opt.text = '<?= $text['all_transmission'] ?> (' + data.options.total + ')';
            } else if (opt.value === 'Manual') {
              opt.text = '<?= $text['manual'] ?> (' + (data.options.gear.Manual || 0) + ')';
            } else if (opt.value === 'Automatic') {
              opt.text = '<?= $text['automatic'] ?> (' + (data.options.gear.Automatic || 0) + ')';
            }
          });
          
          // Update fuel options
          Array.from(fuelSelect.options).forEach(opt => {
            if (opt.value === '') {
              opt.text = '<?= $text['all_fuel'] ?> (' + data.options.total + ')';
            } else if (opt.value === 'Diesel') {
              opt.text = '<?= $text['diesel'] ?> (' + (data.options.fuel.Diesel || 0) + ')';
            } else if (opt.value === 'Petrol') {
              opt.text = '<?= $text['petrol'] ?> (' + (data.options.fuel.Petrol || 0) + ')';
            }
          });
        }
        
        AOS.refreshHard();
      })
      .catch(() => container.innerHTML = '<p class="col-span-full text-center text-red-400">Error.</p>')
      .finally(() => isLoading = false);
  };

  // Search dropdown change event
  els.search.addEventListener('change', fetchCars);
  els.gear.addEventListener('change', fetchCars);
  els.fuel.addEventListener('change', fetchCars);
  els.sort.addEventListener('change', fetchCars);
</script>
</body>
</html>