<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="en" class="transition-colors duration-300">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ETTAAJ RENT CARS - Premium Car Rental in Morocco</title>

  <!-- Favicon -->
  <link rel="icon" href="pub_img/ETTAAJ-RENT-CARS.jpg" type="image/png" sizes="512x512">
  <link rel="icon" href="pub_img/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" href="pub_img/ETTAAJ-RENT-CARS.jpg">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { 
            gold: '#FFD700', 
            'gold-dark': '#E6C200',
            'light-gold': '#d97706'
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg: #36454F;
      --bg-dark: #2C3A44;
      --border: #4A5A66;
      --primary: #FFFFFF;
      --muted: #D1D5DB;
      --gold: #FFD700;
      --gold-dark: #E6C200;
      --hover-bg: rgba(255, 215, 0, 0.1);
    }
    .light {
      --bg: #f8fafc;
      --bg-dark: #e2e8f0;
      --border: #cbd5e1;
      --primary: #1e293b;
      --muted: #64748b;
      --gold: #d97706;
      --gold-dark: #b45309;
      --hover-bg: rgba(217, 119, 6, 0.1);
    }

    body { 
      font-family: 'Inter', sans-serif; 
      background-color: var(--bg); 
      color: var(--primary);
      scroll-behavior: smooth; 
    }

    .sidebar { transition: transform 0.3s ease-in-out; background-color: var(--bg-dark); }
    .sidebar.open  { transform: translateX(0); }
    .sidebar.closed { transform: translateX(-100%); }

    .nav-link { @apply text-current hover:text-[var(--gold-dark)] transition; }
    .nav-link:hover { background-color: var(--hover-bg); border-radius: 0.5rem; }

    .bg-gradient-gold {
      background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* 360° Rotation on Hover + Golden Glow */
    .logo-spin {
      transition: transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1), 
                        box-shadow 0.6s ease;
      border-radius: 50%;
      ring: 4px solid transparent;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .logo-spin:hover {
      transform: rotate(360deg) scale(1.15);
      box-shadow: 
        0 0 30px rgba(255, 215, 0, 0.6),
        0 0 50px rgba(255, 215, 0, 0.4),
        inset 0 0 20px rgba(255, 215, 0, 0.3);
      ring-color: var(--gold);
    }

    /* For mobile - trigger on tap */
    @media (hover: none) {
      .logo-spin:active {
        transform: rotate(360deg) scale(1.2) !important;
        box-shadow: 0 0 40px rgba(255, 215, 0, 0.8) !important;
      }
    }
  </style>
</head>
<body class="min-h-screen">

  <?php 
    $whatsapp_number   = "212772331080";
    $formatted_number  = "+212 772 331 080";
    $wa_link           = "https://wa.me/$whatsapp_number";
  ?>

  <!-- Mobile Sidebar -->
  <div id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-[var(--bg-dark)]/95 backdrop-blur-md shadow-2xl z-50 sidebar closed lg:hidden border-r border-border">
    <div class="p-6">
      <div class="flex justify-between items-center mb-8">
        <a href="index.php" class="flex items-center space-x-3">
          <img src="pub_img/ETTAAJ-RENT-CARS.jpg" alt="Logo" 
               class="w-12 h-12 rounded-full ring-4 ring-[var(--gold)]/40 logo-spin">
          <span class="text-xl font-bold bg-gradient-gold">ETTAAJ RENT CARS</span>
        </a>
        <button id="close-sidebar" class="text-current hover:text-[var(--gold-dark)]">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <nav class="space-y-4">
        <a href="index.php" class="nav-link block px-3 py-2 rounded-lg">Home</a>
        <a href="index.php#cars" class="nav-link block px-3 py-2 rounded-lg">Cars</a>
        <a href="about.php" class="nav-link block px-3 py-2 rounded-lg">About</a>
        <a href="contact.php" class="nav-link block px-3 py-2 rounded-lg">Contact</a>
      </nav>

      <div class="mt-6 pt-6 border-t border-border">
        <a href="<?= $wa_link ?>" class="flex items-center gap-2 text-gold hover:text-[var(--gold-dark)] font-semibold">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.884 3.488"/></svg>
          <span><?= $formatted_number ?></span>
        </a>
      </div>
    </div>
  </div>

  <div id="sidebar-overlay" class="fixed inset-0 bg-black/70 z-40 hidden lg:hidden"></div>

  <!-- HEADER WITH 360° SPIN LOGO -->
  <header class="bg-[var(--bg-dark)]/90 backdrop-blur-md shadow-lg sticky top-0 z-30 border-b border-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

      <!-- Mobile: Centered Logo + Hamburger -->
      <div class="flex items-center justify-between lg:hidden relative">
        <button id="open-sidebar" class="text-current hover:text-[var(--gold-dark)] z-40">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>

        <!-- Centered Logo with 360° Spin -->
        <a href="index.php" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center space-x-3">
          <img src="pub_img/ETTAAJ-RENT-CARS.jpg" alt="Logo" 
               class="w-12 h-12 rounded-full ring-4 ring-[var(--gold)]/50 shadow-xl logo-spin">
          <span class="text-xl font-bold bg-gradient-gold whitespace-nowrap">ETTAAJ RENT CARS</span>
        </a>

        <div class="w-7 h-7"></div>
      </div>

      <!-- Desktop Layout -->
      <div class="hidden lg:flex items-center justify-between">
        <a href="index.php" class="flex items-center space-x-3">
          <img src="pub_img/ETTAAJ-RENT-CARS.jpg" alt="Logo" 
               class="w-12 h-12 rounded-full ring-4 ring-[var(--gold)]/50 shadow-xl logo-spin">
          <span class="text-2xl font-bold bg-gradient-gold">ETTAAJ RENT CARS</span>
        </a>

        <div class="flex items-center space-x-8">
          <nav class="flex space-x-8">
            <a href="index.php" class="nav-link">Home</a>
            <a href="index.php#cars" class="nav-link">Cars</a>
            <a href="about.php" class="nav-link">About</a>
            <a href="contact.php" class="nav-link">Contact</a>
          </nav>

          <a href="<?= $wa_link ?>" class="flex items-center gap-2 text-gold hover:text-[var(--gold-dark)] font-semibold">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">...</svg>
            <?= $formatted_number ?>
          </a>

          <div class="flex items-center gap-2">
            <span class="text-sm text-[var(--muted)]">Dark Mode</span>
            <label class="theme-toggle">...</label>
          </div>
        </div>
      </div>
    </div>
  </header>

  <script>
    // Sidebar & Theme Script (unchanged)
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');

    openBtn?.addEventListener('click', () => {
      sidebar.classList.replace('closed', 'open');
      overlay.classList.remove('hidden');
    });
    const closeSidebar = () => {
      sidebar.classList.replace('open', 'closed');
      overlay.classList.add('hidden');
    };
    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);

    // Theme Toggle
    const html = document.documentElement;
    const applyTheme = (isLight) => {
      if (isLight) html.classList.add('light');
      else html.classList.remove('light');
      localStorage.setItem('theme', isLight ? 'light' : 'dark');
    };
    const saved = localStorage.getItem('theme');
    const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
    applyTheme(saved === 'light' || (!saved && prefersLight));
  </script>
</body>
</html>