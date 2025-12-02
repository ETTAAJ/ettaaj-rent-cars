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
  <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-black py-20 lg:py-28">
    <div class="absolute inset-0 opacity-10">
      <div class="absolute top-0 left-0 w-96 h-96 bg-gold rounded-full filter blur-3xl"></div>
      <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-500 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div data-aos="fade-up">
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-6 leading-tight">
          <?= $text['contact_us_title'] ?? ($lang === 'ar' ? 'اتصل بنا' : ($lang === 'fr' ? 'Contactez-nous' : 'Contact Us')) ?>
        </h1>
        <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto leading-relaxed mb-8">
          <?= $text['contact_subtitle_new'] ?? ($lang === 'ar' ? 'نحن متواجدون على مدار الساعة لمساعدتك في تأجير سيارتك في مراكش والدار البيضاء' : ($lang === 'fr' ? 'Nous sommes disponibles 24h/24 pour vous aider avec votre location de voiture à Marrakech et Casablanca' : 'We\'re available 24/7 to help you with your car rental in Marrakech and Casablanca')) ?>
        </p>
        <!-- Quick Contact Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
          <a href="https://wa.me/212772331080" target="_blank"
             class="inline-flex items-center gap-3 bg-green-500 hover:bg-green-600 text-white font-bold text-lg py-4 px-8 rounded-full shadow-2xl transform hover:scale-105 transition-all">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.844m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.884 3.488"/></svg>
            <?= $lang === 'ar' ? 'واتساب' : ($lang === 'fr' ? 'WhatsApp' : 'WhatsApp') ?>
          </a>
          <a href="tel:+212772331080" class="inline-flex items-center gap-3 bg-gold hover:bg-yellow-500 text-black font-bold text-lg py-4 px-8 rounded-full shadow-2xl transform hover:scale-105 transition-all phone-number">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            +212 772 331 080
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
      <div data-aos="fade-right">
        <div class="bg-card/95 backdrop-blur-xl border-2 border-border rounded-3xl p-8 lg:p-12 shadow-2xl">
          <h2 class="text-3xl font-black text-primary mb-2">
            <?= $lang === 'ar' ? 'أرسل لنا رسالة' : ($lang === 'fr' ? 'Envoyez-nous un message' : 'Send Us a Message') ?>
          </h2>
          <p class="text-muted mb-8">
            <?= $lang === 'ar' ? 'املأ النموذج وسنرد عليك في دقائق' : ($lang === 'fr' ? 'Remplissez le formulaire et nous vous répondrons en quelques minutes' : 'Fill out the form and we\'ll respond within minutes') ?>
          </p>

          <form action="contact-process.php" method="POST" class="space-y-6">
            <!-- Name Input -->
            <div class="relative">
              <input type="text" name="name" id="name" required placeholder=" "
                     class="peer w-full px-5 py-4 bg-white/10 border-2 border-border rounded-2xl text-primary text-lg focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold transition placeholder-transparent">
              <label for="name" class="absolute <?= $lang === 'ar' ? 'right-5' : 'left-5' ?> -top-3 bg-[var(--card)] px-3 text-sm font-semibold text-gold transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-muted peer-focus:-top-3 peer-focus:text-gold peer-focus:text-sm">
                <?= $text['your_name'] ?>
              </label>
            </div>

            <!-- Email Input -->
            <div class="relative">
              <input type="email" name="email" id="email" required placeholder=" "
                     class="peer w-full px-5 py-4 bg-white/10 border-2 border-border rounded-2xl text-primary text-lg focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold transition placeholder-transparent">
              <label for="email" class="absolute <?= $lang === 'ar' ? 'right-5' : 'left-5' ?> -top-3 bg-[var(--card)] px-3 text-sm font-semibold text-gold transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-muted peer-focus:-top-3 peer-focus:text-gold peer-focus:text-sm">
                <?= $text['your_email'] ?>
              </label>
            </div>

            <!-- Message Textarea -->
            <div class="relative">
              <textarea name="message" id="message" rows="6" required placeholder=" "
                        class="peer w-full px-5 py-4 bg-white/10 border-2 border-border rounded-2xl text-primary text-lg focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold transition resize-none placeholder-transparent"></textarea>
              <label for="message" class="absolute <?= $lang === 'ar' ? 'right-5' : 'left-5' ?> -top-3 bg-[var(--card)] px-3 text-sm font-semibold text-gold transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-muted peer-focus:-top-3 peer-focus:text-gold peer-focus:text-sm">
                <?= $text['your_message'] ?>
              </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-gold to-yellow-500 hover:from-yellow-500 hover:to-orange-500 text-black font-bold text-lg py-5 rounded-2xl shadow-xl transition-all transform hover:scale-[1.02]">
              <span class="flex items-center justify-center gap-2">
                <?= $text['send_message'] ?>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </span>
            </button>
          </form>
        </div>
      </div>

      <!-- Right: Contact Info & Social -->
      <div data-aos="fade-left" class="space-y-8">
        <!-- Contact Cards -->
        <div class="space-y-6">
          <!-- Phone Card -->
          <div class="bg-card/80 backdrop-blur-xl border-2 border-border rounded-2xl p-6 hover:border-gold transition-all hover:transform hover:-translate-y-1">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-gold to-yellow-500 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              </div>
              <div class="flex-1">
                <h3 class="font-bold text-lg text-primary mb-1"><?= $text['phone_whatsapp_24_7'] ?></h3>
                <a href="tel:+212772331080" class="text-gold text-xl font-bold hover:underline phone-number">+212 772 331 080</a>
              </div>
            </div>
          </div>

          <!-- Email Card -->
          <div class="bg-card/80 backdrop-blur-xl border-2 border-border rounded-2xl p-6 hover:border-gold transition-all hover:transform hover:-translate-y-1">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-gold to-yellow-500 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </div>
              <div class="flex-1">
                <h3 class="font-bold text-lg text-primary mb-1"><?= $lang === 'ar' ? 'البريد الإلكتروني' : ($lang === 'fr' ? 'Email' : 'Email') ?></h3>
                <a href="mailto:contact@ettaajrentcars.com" class="text-gold text-lg font-semibold hover:underline">contact@ettaajrentcars.com</a>
              </div>
            </div>
          </div>

          <!-- Operating Hours Card -->
          <div class="bg-card/80 backdrop-blur-xl border-2 border-border rounded-2xl p-6 hover:border-gold transition-all hover:transform hover:-translate-y-1">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-gold to-yellow-500 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <div class="flex-1">
                <h3 class="font-bold text-lg text-primary mb-1">
                  <?= $lang === 'ar' ? 'ساعات العمل' : ($lang === 'fr' ? 'Horaires' : 'Operating Hours') ?>
                </h3>
                <p class="text-gold text-lg font-semibold">
                  <?= $lang === 'ar' ? '24/7 - متاح دائماً' : ($lang === 'fr' ? '24h/24, 7j/7' : '24/7 - Always Available') ?>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Social Media -->
        <div class="bg-card/80 backdrop-blur-xl border-2 border-border rounded-2xl p-6">
          <h3 class="font-bold text-lg text-primary mb-4">
            <?= $lang === 'ar' ? 'تابعنا' : ($lang === 'fr' ? 'Suivez-nous' : 'Follow Us') ?>
          </h3>
          <div class="flex gap-4">
            <a href="https://instagram.com/ettaaj.rentcars" target="_blank" class="w-14 h-14 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl flex items-center justify-center hover:scale-110 transition-transform shadow-lg">
              <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98C23.986 15.668 24 15.259 24 12c0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/></svg>
            </a>
            <a href="https://facebook.com/profile.php?id=61559816313152" target="_blank" class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center hover:scale-110 transition-transform shadow-lg">
              <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://wa.me/212772331080" target="_blank" class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center hover:scale-110 transition-transform shadow-lg">
              <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.844m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.884 3.488"/></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Maps Section -->
  <section class="py-16 bg-card-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12" data-aos="fade-up">
        <h2 class="text-4xl lg:text-5xl font-black text-primary mb-4">
          <?= $lang === 'ar' ? 'مواقعنا' : ($lang === 'fr' ? 'Nos Emplacements' : 'Our Locations') ?>
        </h2>
        <p class="text-muted text-lg">
          <?= $lang === 'ar' ? 'نخدمك في مطارات مراكش والدار البيضاء' : ($lang === 'fr' ? 'Nous vous servons dans les aéroports de Marrakech et Casablanca' : 'Serving you at Marrakech & Casablanca airports') ?>
        </p>
      </div>

      <div class="grid md:grid-cols-2 gap-8">
        <!-- Marrakech Map -->
        <div data-aos="fade-right" class="group">
          <div class="bg-card/80 backdrop-blur-xl border-2 border-border hover:border-gold rounded-3xl overflow-hidden shadow-2xl transition-all hover:transform hover:-translate-y-2">
            <div class="p-6">
              <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-gold to-yellow-500 rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                  <h3 class="text-2xl font-black text-primary"><?= $lang === 'ar' ? 'مراكش' : 'Marrakech' ?></h3>
                  <p class="text-gold font-semibold"><?= $text['marrakech_airport'] ?></p>
                </div>
              </div>
            </div>
            <div class="relative h-80 overflow-hidden">
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3398.987465432177!2d-8.003892684897948!3d31.634509981337!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xdafee9a3c2c4d8f%3A0x8e8f8f8f8f8f8f8f!2sMarrakech%20Menara%20Airport!5e0!3m2!1sen!2sma!4v1732062800000!5m2!1sen!2sma"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="p-6">
              <a href="https://maps.app.goo.gl/qNu7pL2mrhwVVxGp6" target="_blank" 
                 class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-gold to-yellow-500 hover:from-yellow-500 hover:to-orange-500 text-black font-bold py-4 rounded-2xl transition-all hover:scale-105">
                <?= $lang === 'ar' ? 'افتح في خرائط جوجل' : ($lang === 'fr' ? 'Ouvrir dans Google Maps' : 'Open in Google Maps') ?>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
            </div>
          </div>
        </div>

        <!-- Casablanca Map -->
        <div data-aos="fade-left" class="group">
          <div class="bg-card/80 backdrop-blur-xl border-2 border-border hover:border-gold rounded-3xl overflow-hidden shadow-2xl transition-all hover:transform hover:-translate-y-2">
            <div class="p-6">
              <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-gold to-yellow-500 rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                  <h3 class="text-2xl font-black text-primary"><?= $lang === 'ar' ? 'الدار البيضاء' : 'Casablanca' ?></h3>
                  <p class="text-gold font-semibold"><?= $text['casablanca_airport'] ?></p>
                </div>
              </div>
            </div>
            <div class="relative h-80 overflow-hidden">
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3323.785421234567!2d-7.589843!3d33.367058!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7cd3778aa113b%3A0xb18c7b7b98a0f94a!2sCasablanca%20Mohammed%20V%20International%20Airport!5e0!3m2!1sen!2sma!4v1732062900000!5m2!1sen!2sma"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="p-6">
              <a href="https://www.google.com/maps?q=Mohammed+V+International+Airport+Casablanca" target="_blank" 
                 class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-gold to-yellow-500 hover:from-yellow-500 hover:to-orange-500 text-black font-bold py-4 rounded-2xl transition-all hover:scale-105">
                <?= $lang === 'ar' ? 'افتح في خرائط جوجل' : ($lang === 'fr' ? 'Ouvrir dans Google Maps' : 'Open in Google Maps') ?>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
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