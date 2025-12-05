<?php 
require_once 'init.php';
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" class="scroll-smooth" dir="<?= getDir() ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <?php
  // SEO Meta Tags - Language & Location Specific for Contact Page
  $baseUrl = 'https://www.ettaajrentcars.com';
  $currentUrl = $baseUrl . '/contact.php' . (isset($_GET['lang']) ? '?lang=' . $lang : '');
  
  // Language-specific titles and descriptions for Contact page
  $seoData = [
    'en' => [
      'title' => 'Contact ETTAAJ Rent Cars | Car Rental Marrakech & Casablanca Airport - WhatsApp +212 772 331 080',
      'description' => 'Contact ETTAAJ Rent Cars - Best car rental in Marrakech & Casablanca airports. Instant WhatsApp reply 24/7, free airport delivery, no deposit. Call or message +212 772 331 080 now!',
      'keywords' => 'contact ettaaj rent cars, car rental Marrakech airport contact, car rental Casablanca airport contact, car rental Marrakech whatsapp, car rental Casablanca whatsapp, car rental Marrakech phone number, car rental Casablanca phone number, best car rental Marrakech contact, best car rental Casablanca contact'
    ],
    'fr' => [
      'title' => 'Contact ETTAAJ Rent Cars | Location Voiture Aéroport Marrakech & Casablanca - WhatsApp +212 772 331 080',
      'description' => 'Contactez ETTAAJ Rent Cars - Meilleure location de voiture aux aéroports de Marrakech et Casablanca. Réponse WhatsApp instantanée 24/7, livraison gratuite à l\'aéroport, sans caution. Appelez ou envoyez un message +212 772 331 080 maintenant !',
      'keywords' => 'contact ettaaj rent cars, location voiture aéroport Marrakech contact, location voiture aéroport Casablanca contact, location voiture Marrakech whatsapp, location voiture Casablanca whatsapp'
    ],
    'ar' => [
      'title' => 'اتصل بـ ETTAAJ Rent Cars | تأجير السيارات مطار مراكش والدار البيضاء - واتساب +212 772 331 080',
      'description' => 'اتصل بـ ETTAAJ Rent Cars - أفضل تأجير سيارات في مطارات مراكش والدار البيضاء. رد واتساب فوري 24/7، توصيل مجاني للمطار، بدون وديعة. اتصل أو أرسل رسالة +212 772 331 080 الآن!',
      'keywords' => 'اتصل ettaaj rent cars، تأجير سيارات مطار مراكش اتصال، تأجير سيارات مطار الدار البيضاء اتصال، تأجير سيارات مراكش واتساب، تأجير سيارات الدار البيضاء واتساب'
    ]
  ];
  
  $currentSeo = $seoData[$lang] ?? $seoData['en'];
  ?>
  
  <!-- OPTIMIZED FOR MARRAKECH & CASABLANCA -->
  <title><?= htmlspecialchars($currentSeo['title']) ?></title>
  <meta name="description" content="<?= htmlspecialchars($currentSeo['description']) ?>" />
  <meta name="keywords" content="<?= htmlspecialchars($currentSeo['keywords']) ?>" />
  <meta name="author" content="ETTAAJ Rent Cars" />
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
  <meta name="language" content="<?= $lang ?>" />
  <meta name="geo.region" content="MA" />
  <meta name="geo.placename" content="Marrakech, Casablanca" />
  <meta name="geo.position" content="31.6069;-8.0363, 33.5731;-7.5898" />
  <meta name="ICBM" content="31.6069, -8.0363" />
  <link rel="icon" href="pub_img/ettaaj-rent-cars.jpeg">

  <link rel="canonical" href="<?= htmlspecialchars($currentUrl) ?>" />
  
  <!-- Hreflang Tags -->
  <link rel="alternate" hreflang="en" href="<?= $baseUrl ?>/contact.php?lang=en" />
  <link rel="alternate" hreflang="fr" href="<?= $baseUrl ?>/contact.php?lang=fr" />
  <link rel="alternate" hreflang="ar" href="<?= $baseUrl ?>/contact.php?lang=ar" />
  <link rel="alternate" hreflang="x-default" href="<?= $baseUrl ?>/contact.php?lang=en" />

  <meta property="og:title" content="<?= htmlspecialchars($currentSeo['title']) ?>" />
  <meta property="og:description" content="<?= htmlspecialchars($currentSeo['description']) ?>" />
  <meta property="og:url" content="<?= htmlspecialchars($currentUrl) ?>" />
  <meta property="og:image" content="<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg" />
  <meta property="og:type" content="website" />
  <meta property="og:locale" content="<?= $lang === 'fr' ? 'fr_FR' : ($lang === 'ar' ? 'ar_MA' : 'en_US') ?>" />

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= htmlspecialchars($currentSeo['title']) ?>" />
  <meta name="twitter:description" content="<?= htmlspecialchars($currentSeo['description']) ?>" />
  <meta name="twitter:image" content="<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg" />

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "CarRentalService",
    "name": "ETTAAJ Rent Cars",
    "alternateName": "ETTAAJ RENT CARS",
    "url": "<?= $baseUrl ?>",
    "telephone": "+212772331080",
    "image": "<?= $baseUrl ?>/pub_img/ettaaj-rent-cars.jpeg",
    "description": "<?= htmlspecialchars($currentSeo['description']) ?>",
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
    "contactPoint": {
      "@type": "ContactPoint",
      "telephone": "+212772331080",
      "contactType": "Customer Service",
      "areaServed": "MA",
      "availableLanguage": ["English", "French", "Arabic"]
    },
    "openingHours": "Mo-Su 00:00-23:59"
  }
  </script>

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
      --card-dark-gradient: linear-gradient(135deg, #0B0B0C 0%, #121212 55%, var(--therde) 120%);
    }
    .light {
      --bg: #f8fafc; --bg-dark: #e2e8f0; --card: #EFECE3; --card-dark: #EFECE3;
      --border: #cbd5e1; --primary: #1e293b; --muted: #64748b; --gold: #d97706;
      --text-primary: var(--primary); --text-muted: var(--muted);
    }
    .car-card-bg { background: #000000 !important; }
    body { background-color: var(--bg); color: var(--primary); font-family: 'Inter', sans-serif; }
    .bg-card { background-color: var(--card); }
    .bg-card-dark { background-color: var(--card-dark); }
    .car-card-bg { background: #000000 !important; }
    .border-border { border-color: var(--border); }
    .text-primary { color: var(--primary); }
    .text-muted { color: var(--muted); }
    .text-gold { color: var(--gold); }
    /* Phone number always LTR */
    .phone-number { direction: ltr !important; display: inline-block; unicode-bidi: embed; }
    
    /* Modern Contact Page Styles */
    html {
      scroll-behavior: smooth;
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
    
    /* Hover effects for cards */
    .hover-lift {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 25px -5px rgba(255, 178, 44, 0.3), 0 10px 10px -5px rgba(255, 178, 44, 0.2);
    }
    
    /* Input focus animations */
    input:focus, textarea:focus {
      transform: translateY(-2px);
    }
    
    /* Responsive text sizes */
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
      .text-7xl {
        font-size: 3.5rem;
      }
    }
  </style>
</head>
<body class="min-h-screen">

<?php include 'header.php'; ?>

<main class="min-h-screen">
  <!-- Hero Section -->
  <section class="relative overflow-hidden bg-gradient-to-br from-[#0a0a0a] via-[#1a1a1a] to-[#0f0f0f] py-24 lg:py-32">
    <!-- Animated Background Effects -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute top-0 left-0 w-[600px] h-[600px] bg-green-500/20 rounded-full filter blur-[120px] animate-pulse"></div>
      <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-gold/20 rounded-full filter blur-[120px] animate-pulse" style="animation-delay: 1s;"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gold/10 rounded-full filter blur-[150px]"></div>
    </div>
    
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,215,0,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,215,0,0.1) 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div data-aos="fade-up" data-aos-duration="1000">
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-8 leading-tight">
          <span class="bg-gradient-to-r from-white via-gold/90 to-white bg-clip-text text-transparent drop-shadow-2xl">
            <?= $text['contact_us_title'] ?? ($lang === 'ar' ? 'اتصل بنا' : ($lang === 'fr' ? 'Contactez-nous' : 'Contact Us')) ?>
          </span>
        </h1>
        
        <div class="w-32 h-1.5 bg-gradient-to-r from-transparent via-gold to-transparent rounded-full mx-auto mb-10"></div>
        
        <p class="text-xl md:text-2xl lg:text-3xl text-gray-200 max-w-4xl mx-auto leading-relaxed mb-12 font-medium">
          <?= $text['contact_subtitle_new'] ?? ($lang === 'ar' ? 'نحن متواجدون على مدار الساعة لمساعدتك في تأجير سيارتك في مراكش والدار البيضاء' : ($lang === 'fr' ? 'Nous sommes disponibles 24h/24 pour vous aider avec votre location de voiture à Marrakech et Casablanca' : 'We\'re available 24/7 to help you with your car rental in Marrakech and Casablanca')) ?>
        </p>
        
        <!-- Quick Contact Buttons -->
        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
          <a href="https://wa.me/212772331080" target="_blank"
             class="group relative inline-flex items-center gap-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold text-xl py-5 px-10 rounded-2xl shadow-2xl transform hover:scale-110 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            <svg class="w-7 h-7 relative z-10" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.844m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.884 3.488"/></svg>
            <span class="relative z-10"><?= $lang === 'ar' ? 'واتساب' : ($lang === 'fr' ? 'WhatsApp' : 'WhatsApp') ?></span>
          </a>
          <a href="tel:+212772331080" class="group relative inline-flex items-center gap-4 bg-gradient-to-r from-gold via-yellow-500 to-orange-500 hover:from-yellow-500 hover:to-orange-500 text-black font-bold text-xl py-5 px-10 rounded-2xl shadow-2xl transform hover:scale-110 transition-all duration-300 overflow-hidden phone-number">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            <svg class="w-7 h-7 relative z-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <span class="relative z-10">+212 772 331 080</span>
          </a>
        </div>
      </div>
    </div>

    <!-- SEO Keywords (Hidden) -->
    <div style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border-width: 0;">
      <h2>Contact ETTAAJ Rent Cars - Car Rental Marrakech & Casablanca Airport</h2>
      <p>Contact us for the best car rental service in Morocco. Available in Marrakech and Casablanca airports 24/7.</p>
    </div>
  </section>

  <!-- Contact Form & Info Section -->
  <section class="py-16 lg:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-12 items-start">
      
      <!-- Left: Contact Form -->
      <div data-aos="fade-right" data-aos-duration="1000" class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-br from-gold/30 via-yellow-500/20 to-gold/30 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
        <div class="relative bg-gradient-to-br from-[#1a1a1a] via-[#2a2a2a] to-[#1a1a1a] backdrop-blur-xl border-2 border-gold/30 rounded-3xl p-10 lg:p-14 shadow-[0_25px_50px_-12px_rgba(255,215,0,0.3)]">
          <div class="mb-8">
            <h2 class="text-4xl lg:text-5xl font-black text-primary mb-4">
              <span class="bg-gradient-to-r from-white via-gold/90 to-white bg-clip-text text-transparent">
                <?= $lang === 'ar' ? 'أرسل لنا رسالة' : ($lang === 'fr' ? 'Envoyez-nous un message' : 'Send Us a Message') ?>
              </span>
            </h2>
            <div class="w-24 h-1.5 bg-gradient-to-r from-gold via-yellow-500 to-gold rounded-full mb-6"></div>
            <p class="text-xl text-muted font-medium">
              <?= $lang === 'ar' ? 'املأ النموذج وسنرد عليك في دقائق' : ($lang === 'fr' ? 'Remplissez le formulaire et nous vous répondrons en quelques minutes' : 'Fill out the form and we\'ll respond within minutes') ?>
            </p>
          </div>

          <form action="contact-process.php" method="POST" class="space-y-7">
            <!-- Name Input -->
            <div class="relative group/input">
              <input type="text" name="name" id="name" required placeholder=" "
                     class="peer w-full px-6 py-5 bg-gradient-to-br from-[#1a1a1a]/80 to-[#2a2a2a]/80 border-2 border-[#4A5A66]/40 rounded-2xl text-primary text-lg focus:outline-none focus:ring-4 focus:ring-gold/30 focus:border-gold/60 transition-all duration-300 placeholder-transparent hover:border-gold/40 backdrop-blur-sm">
              <label for="name" class="absolute <?= $lang === 'ar' ? 'right-6' : 'left-6' ?> -top-3 bg-gradient-to-br from-[#1a1a1a] to-[#2a2a2a] px-4 text-sm font-bold text-gold transition-all peer-placeholder-shown:top-5 peer-placeholder-shown:text-base peer-placeholder-shown:text-muted peer-focus:-top-3 peer-focus:text-gold peer-focus:text-sm">
                <?= $text['your_name'] ?>
              </label>
              <div class="absolute inset-0 rounded-2xl bg-gold/0 group-hover/input:bg-gold/5 transition-colors pointer-events-none"></div>
            </div>

            <!-- Email Input -->
            <div class="relative group/input">
              <input type="email" name="email" id="email" required placeholder=" "
                     class="peer w-full px-6 py-5 bg-gradient-to-br from-[#1a1a1a]/80 to-[#2a2a2a]/80 border-2 border-[#4A5A66]/40 rounded-2xl text-primary text-lg focus:outline-none focus:ring-4 focus:ring-gold/30 focus:border-gold/60 transition-all duration-300 placeholder-transparent hover:border-gold/40 backdrop-blur-sm">
              <label for="email" class="absolute <?= $lang === 'ar' ? 'right-6' : 'left-6' ?> -top-3 bg-gradient-to-br from-[#1a1a1a] to-[#2a2a2a] px-4 text-sm font-bold text-gold transition-all peer-placeholder-shown:top-5 peer-placeholder-shown:text-base peer-placeholder-shown:text-muted peer-focus:-top-3 peer-focus:text-gold peer-focus:text-sm">
                <?= $text['your_email'] ?>
              </label>
              <div class="absolute inset-0 rounded-2xl bg-gold/0 group-hover/input:bg-gold/5 transition-colors pointer-events-none"></div>
            </div>

            <!-- Message Textarea -->
            <div class="relative group/input">
              <textarea name="message" id="message" rows="6" required placeholder=" "
                        class="peer w-full px-6 py-5 bg-gradient-to-br from-[#1a1a1a]/80 to-[#2a2a2a]/80 border-2 border-[#4A5A66]/40 rounded-2xl text-primary text-lg focus:outline-none focus:ring-4 focus:ring-gold/30 focus:border-gold/60 transition-all duration-300 resize-none placeholder-transparent hover:border-gold/40 backdrop-blur-sm"></textarea>
              <label for="message" class="absolute <?= $lang === 'ar' ? 'right-6' : 'left-6' ?> -top-3 bg-gradient-to-br from-[#1a1a1a] to-[#2a2a2a] px-4 text-sm font-bold text-gold transition-all peer-placeholder-shown:top-5 peer-placeholder-shown:text-base peer-placeholder-shown:text-muted peer-focus:-top-3 peer-focus:text-gold peer-focus:text-sm">
                <?= $text['your_message'] ?>
              </label>
              <div class="absolute inset-0 rounded-2xl bg-gold/0 group-hover/input:bg-gold/5 transition-colors pointer-events-none"></div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="relative w-full bg-gradient-to-r from-gold via-yellow-500 to-orange-500 hover:from-yellow-500 hover:via-orange-500 hover:to-orange-600 text-black font-bold text-xl py-6 rounded-2xl shadow-2xl transition-all transform hover:scale-[1.02] overflow-hidden group/btn">
              <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700"></div>
              <span class="relative z-10 flex items-center justify-center gap-3">
                <?= $text['send_message'] ?>
                <svg class="w-6 h-6 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </span>
            </button>
          </form>
        </div>
      </div>

      <!-- Right: Contact Info & Social -->
      <div data-aos="fade-left" data-aos-duration="1000" class="space-y-8">
        <!-- Contact Cards -->
        <div class="space-y-6">
          <!-- Phone Card -->
          <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-br from-gold/30 via-yellow-500/20 to-gold/30 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div class="relative bg-gradient-to-br from-[#1a1a1a] via-[#2a2a2a] to-[#1a1a1a] backdrop-blur-xl border-2 border-gold/30 rounded-3xl p-8 hover:border-gold/60 transition-all duration-500 transform group-hover:-translate-y-2 shadow-[0_20px_60px_-15px_rgba(255,215,0,0.2)]">
              <div class="flex items-start gap-5">
                <div class="relative flex-shrink-0 w-18 h-18 bg-gradient-to-br from-gold via-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                  <div class="absolute inset-0 bg-gold/50 blur-xl"></div>
                  <svg class="w-8 h-8 text-black relative z-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div class="flex-1">
                  <h3 class="font-black text-xl text-primary mb-3"><?= $text['phone_whatsapp_24_7'] ?></h3>
                  <a href="tel:+212772331080" class="text-gold text-2xl font-black hover:underline phone-number transition-all hover:text-yellow-400">+212 772 331 080</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Email Card -->
          <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-br from-gold/30 via-yellow-500/20 to-gold/30 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div class="relative bg-gradient-to-br from-[#1a1a1a] via-[#2a2a2a] to-[#1a1a1a] backdrop-blur-xl border-2 border-gold/30 rounded-3xl p-8 hover:border-gold/60 transition-all duration-500 transform group-hover:-translate-y-2 shadow-[0_20px_60px_-15px_rgba(255,215,0,0.2)]">
              <div class="flex items-start gap-5">
                <div class="relative flex-shrink-0 w-18 h-18 bg-gradient-to-br from-gold via-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                  <div class="absolute inset-0 bg-gold/50 blur-xl"></div>
                  <svg class="w-8 h-8 text-black relative z-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1">
                  <h3 class="font-black text-xl text-primary mb-3"><?= $lang === 'ar' ? 'البريد الإلكتروني' : ($lang === 'fr' ? 'Email' : 'Email') ?></h3>
                  <a href="mailto:contact@ettaajrentcars.com" class="text-gold text-xl font-bold hover:underline transition-all hover:text-yellow-400">contact@ettaajrentcars.com</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Operating Hours Card -->
          <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-br from-gold/30 via-yellow-500/20 to-gold/30 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div class="relative bg-gradient-to-br from-[#1a1a1a] via-[#2a2a2a] to-[#1a1a1a] backdrop-blur-xl border-2 border-gold/30 rounded-3xl p-8 hover:border-gold/60 transition-all duration-500 transform group-hover:-translate-y-2 shadow-[0_20px_60px_-15px_rgba(255,215,0,0.2)]">
              <div class="flex items-start gap-5">
                <div class="relative flex-shrink-0 w-18 h-18 bg-gradient-to-br from-gold via-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                  <div class="absolute inset-0 bg-gold/50 blur-xl"></div>
                  <svg class="w-8 h-8 text-black relative z-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                  <h3 class="font-black text-xl text-primary mb-3">
                    <?= $lang === 'ar' ? 'ساعات العمل' : ($lang === 'fr' ? 'Horaires' : 'Operating Hours') ?>
                  </h3>
                  <p class="text-gold text-2xl font-black">
                    <?= $lang === 'ar' ? '24/7 - متاح دائماً' : ($lang === 'fr' ? '24h/24, 7j/7' : '24/7 - Always Available') ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Social Media -->
        <div class="relative group">
          <div class="absolute -inset-1 bg-gradient-to-br from-gold/30 via-yellow-500/20 to-gold/30 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
          <div class="relative bg-gradient-to-br from-[#1a1a1a] via-[#2a2a2a] to-[#1a1a1a] backdrop-blur-xl border-2 border-gold/30 rounded-3xl p-8 shadow-[0_20px_60px_-15px_rgba(255,215,0,0.2)]">
            <h3 class="font-black text-2xl text-primary mb-6">
              <span class="bg-gradient-to-r from-white via-gold/90 to-white bg-clip-text text-transparent">
                <?= $lang === 'ar' ? 'تابعنا' : ($lang === 'fr' ? 'Suivez-nous' : 'Follow Us') ?>
              </span>
            </h3>
            <div class="flex gap-5">
              <a href="https://instagram.com/ettaaj.rentcars" target="_blank" class="group/social relative w-16 h-16 bg-gradient-to-br from-purple-600 via-pink-600 to-red-500 rounded-2xl flex items-center justify-center hover:scale-110 transition-all duration-300 shadow-2xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover/social:translate-x-full transition-transform duration-700"></div>
                <svg class="w-8 h-8 text-white relative z-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98C23.986 15.668 24 15.259 24 12c0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/></svg>
              </a>
              <a href="https://facebook.com/profile.php?id=61559816313152" target="_blank" class="group/social relative w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center hover:scale-110 transition-all duration-300 shadow-2xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover/social:translate-x-full transition-transform duration-700"></div>
                <svg class="w-8 h-8 text-white relative z-10" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </a>
              <a href="https://wa.me/212772331080" target="_blank" class="group/social relative w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center hover:scale-110 transition-all duration-300 shadow-2xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover/social:translate-x-full transition-transform duration-700"></div>
                <svg class="w-8 h-8 text-white relative z-10" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.844m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.884 3.488"/></svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Maps Section -->
  <section class="py-20 lg:py-28 bg-gradient-to-br from-[#1a1a1a] via-[#2a2a2a] to-[#1a1a1a] relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 50px 50px;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="1000">
        <h2 class="text-5xl lg:text-6xl font-black text-primary mb-6">
          <span class="bg-gradient-to-r from-white via-gold/90 to-white bg-clip-text text-transparent">
            <?= $lang === 'ar' ? 'مواقعنا' : ($lang === 'fr' ? 'Nos Emplacements' : 'Our Locations') ?>
          </span>
        </h2>
        <div class="w-32 h-2 bg-gradient-to-r from-transparent via-gold to-transparent rounded-full mx-auto mb-8"></div>
        <p class="text-2xl text-muted font-medium">
          <?= $lang === 'ar' ? 'نخدمك في مطارات مراكش والدار البيضاء' : ($lang === 'fr' ? 'Nous vous servons dans les aéroports de Marrakech et Casablanca' : 'Serving you at Marrakech & Casablanca airports') ?>
        </p>
      </div>

      <div class="grid md:grid-cols-2 gap-10">
        <!-- Marrakech Map -->
        <div data-aos="fade-right" data-aos-duration="1000" class="group">
          <div class="relative">
            <div class="absolute -inset-1 bg-gradient-to-br from-gold/30 via-yellow-500/20 to-gold/30 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div class="relative bg-gradient-to-br from-[#1a1a1a] via-[#2a2a2a] to-[#1a1a1a] backdrop-blur-xl border-2 border-gold/30 hover:border-gold/60 rounded-3xl overflow-hidden shadow-[0_25px_50px_-12px_rgba(255,215,0,0.3)] transition-all duration-500 transform group-hover:-translate-y-3">
              <div class="p-8">
                <div class="flex items-center gap-4 mb-6">
                  <div class="relative w-16 h-16 bg-gradient-to-br from-gold via-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                    <div class="absolute inset-0 bg-gold/50 blur-xl"></div>
                    <svg class="w-8 h-8 text-black relative z-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </div>
                  <div>
                    <h3 class="text-3xl font-black text-primary"><?= $lang === 'ar' ? 'مراكش' : 'Marrakech' ?></h3>
                    <p class="text-gold font-bold text-lg"><?= $text['marrakech_airport'] ?></p>
                  </div>
                </div>
              </div>
              <div class="relative h-96 overflow-hidden border-t-2 border-gold/20">
                <iframe 
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3398.987465432177!2d-8.003892684897948!3d31.634509981337!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xdafee9a3c2c4d8f%3A0x8e8f8f8f8f8f8f8f!2sMarrakech%20Menara%20Airport!5e0!3m2!1sen!2sma!4v1732062800000!5m2!1sen!2sma"
                  width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
              <div class="p-8">
                <a href="https://maps.app.goo.gl/qNu7pL2mrhwVVxGp6" target="_blank" 
                   class="group/btn relative flex items-center justify-center gap-3 w-full bg-gradient-to-r from-gold via-yellow-500 to-orange-500 hover:from-yellow-500 hover:via-orange-500 hover:to-orange-600 text-black font-bold text-lg py-5 rounded-2xl transition-all hover:scale-105 overflow-hidden shadow-2xl">
                  <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700"></div>
                  <span class="relative z-10"><?= $lang === 'ar' ? 'افتح في خرائط جوجل' : ($lang === 'fr' ? 'Ouvrir dans Google Maps' : 'Open in Google Maps') ?></span>
                  <svg class="w-6 h-6 relative z-10 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Casablanca Map -->
        <div data-aos="fade-left" data-aos-duration="1000" class="group">
          <div class="relative">
            <div class="absolute -inset-1 bg-gradient-to-br from-gold/30 via-yellow-500/20 to-gold/30 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div class="relative bg-gradient-to-br from-[#1a1a1a] via-[#2a2a2a] to-[#1a1a1a] backdrop-blur-xl border-2 border-gold/30 hover:border-gold/60 rounded-3xl overflow-hidden shadow-[0_25px_50px_-12px_rgba(255,215,0,0.3)] transition-all duration-500 transform group-hover:-translate-y-3">
              <div class="p-8">
                <div class="flex items-center gap-4 mb-6">
                  <div class="relative w-16 h-16 bg-gradient-to-br from-gold via-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                    <div class="absolute inset-0 bg-gold/50 blur-xl"></div>
                    <svg class="w-8 h-8 text-black relative z-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </div>
                  <div>
                    <h3 class="text-3xl font-black text-primary"><?= $lang === 'ar' ? 'الدار البيضاء' : 'Casablanca' ?></h3>
                    <p class="text-gold font-bold text-lg"><?= $text['casablanca_airport'] ?></p>
                  </div>
                </div>
              </div>
              <div class="relative h-96 overflow-hidden border-t-2 border-gold/20">
                <iframe 
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3323.785421234567!2d-7.589843!3d33.367058!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7cd3778aa113b%3A0xb18c7b7b98a0f94a!2sCasablanca%20Mohammed%20V%20International%20Airport!5e0!3m2!1sen!2sma!4v1732062900000!5m2!1sen!2sma"
                  width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
              <div class="p-8">
                <a href="https://www.google.com/maps?q=Mohammed+V+International+Airport+Casablanca" target="_blank" 
                   class="group/btn relative flex items-center justify-center gap-3 w-full bg-gradient-to-r from-gold via-yellow-500 to-orange-500 hover:from-yellow-500 hover:via-orange-500 hover:to-orange-600 text-black font-bold text-lg py-5 rounded-2xl transition-all hover:scale-105 overflow-hidden shadow-2xl">
                  <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700"></div>
                  <span class="relative z-10"><?= $lang === 'ar' ? 'افتح في خرائط جوجل' : ($lang === 'fr' ? 'Ouvrir dans Google Maps' : 'Open in Google Maps') ?></span>
                  <svg class="w-6 h-6 relative z-10 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 900 });
</script>
</body>
</html>