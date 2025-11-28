<?php
/**
 * Admin Header Component
 * Modern header navigation for admin panel
 */
if (!isset($pdo)) {
    require_once 'config.php';
}

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);

// Get visitor count for badge
$visitorCount = 0;
try {
    $visitorCountStmt = $pdo->query("SELECT COUNT(*) as count FROM visitor_data WHERE email IS NOT NULL OR phone IS NOT NULL");
    $visitorCountResult = $visitorCountStmt->fetch(PDO::FETCH_ASSOC);
    $visitorCount = (int)($visitorCountResult['count'] ?? 0);
} catch (Exception $e) {
    $visitorCount = 0;
}
?>

<style>
  /* Admin Header Styles */
  .admin-header {
    background: linear-gradient(135deg, #1a1a1a 0%, #2C3A44 100%);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    border-bottom: 2px solid rgba(255, 178, 44, 0.2);
  }
  
  .header-link {
    transition: all 0.3s ease;
    position: relative;
    border-radius: 8px;
    padding: 0.5rem 1rem;
  }
  
  .header-link:hover {
    background: rgba(255, 178, 44, 0.1);
    transform: translateY(-2px);
  }
  
  .header-link.active {
    background: linear-gradient(135deg, rgba(255, 178, 44, 0.2) 0%, rgba(255, 165, 0, 0.15) 100%);
    color: #FFB22C;
    font-weight: 700;
  }
  
  .header-link.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 3px;
    background: linear-gradient(90deg, #FFB22C 0%, #FFA500 100%);
    border-radius: 3px 3px 0 0;
  }
  
  .header-badge {
    animation: pulse-badge 2s ease-in-out infinite;
    background: linear-gradient(135deg, #FFB22C 0%, #FFA500 100%);
    box-shadow: 0 2px 8px rgba(255, 178, 44, 0.4);
  }
  
  @keyframes pulse-badge {
    0%, 100% {
      transform: scale(1);
      box-shadow: 0 2px 8px rgba(255, 178, 44, 0.4);
    }
    50% {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(255, 178, 44, 0.6);
    }
  }
  
  /* User Menu Dropdown */
  .header-user-menu {
    position: relative;
  }
  
  #userMenuDropdown {
    animation: slideDown 0.2s ease-out;
  }
  
  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  #userMenuToggle i.bi-chevron-down {
    transition: transform 0.3s ease;
  }
  
  .header-user-menu.open #userMenuToggle i.bi-chevron-down {
    transform: rotate(180deg);
  }
  
  /* Mobile Menu */
  #mobileMenu {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #1a1a1a 0%, #2C3A44 100%);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    z-index: 9999;
    padding: 1rem;
    max-height: 90vh;
    overflow-y: auto;
  }
  
  #mobileMenu.open {
    display: block;
  }
  
  /* Responsive Design */
  @media (max-width: 1023px) {
    .desktop-nav {
      display: none;
    }
    #mobileMenuToggle {
      display: block;
    }
    .admin-header {
      padding: 0.5rem 0;
    }
    .admin-header h1 {
      font-size: 1rem;
    }
    .admin-header p {
      font-size: 0.7rem;
    }
  }
  
  @media (min-width: 640px) and (max-width: 1023px) {
    .admin-header h1 {
      font-size: 1.125rem;
    }
  }
  
  @media (min-width: 1024px) {
    .desktop-nav {
      display: flex;
    }
    #mobileMenuToggle {
      display: none;
    }
    #mobileMenu {
      display: none !important;
    }
    .header-link span {
      white-space: nowrap;
    }
  }
  
  /* Mobile Menu Improvements */
  @media (max-width: 1023px) {
    #mobileMenu {
      top: 64px;
      border-top: 2px solid rgba(255, 178, 44, 0.2);
    }
  }
</style>

<!-- Admin Header -->
<header class="admin-header sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center shadow-lg">
          <i class="bi bi-speedometer2 text-black text-xl"></i>
        </div>
        <div>
          <h1 class="text-lg font-bold text-yellow-500">Admin Panel</h1>
          <p class="text-xs text-gray-400">ETTAAJ Rent Cars</p>
        </div>
      </div>
      
      <!-- Desktop Navigation -->
      <nav class="desktop-nav items-center gap-2">
        <a href="index.php" 
           class="header-link flex items-center gap-2 <?= $current_page === 'index.php' ? 'active' : 'text-gray-300' ?>">
          <i class="bi bi-car-front-fill"></i>
          <span>Cars</span>
        </a>
        
        <a href="visitors.php" 
           class="header-link flex items-center gap-2 <?= $current_page === 'visitors.php' ? 'active' : 'text-gray-300' ?>">
          <i class="bi bi-people-fill"></i>
          <span>Visitors</span>
          <?php if ($visitorCount > 0): ?>
          <span class="header-badge text-black text-xs font-bold px-2 py-0.5 rounded-full">
            <?= $visitorCount > 99 ? '99+' : $visitorCount ?>
          </span>
          <?php endif; ?>
        </a>
        
        <a href="travel-essentials.php" 
           class="header-link flex items-center gap-2 <?= $current_page === 'travel-essentials.php' ? 'active' : 'text-gray-300' ?>">
          <i class="bi bi-bag-check-fill"></i>
          <span>Essentials</span>
        </a>
        
        <a href="currencies.php" 
           class="header-link flex items-center gap-2 <?= $current_page === 'currencies.php' ? 'active' : 'text-gray-300' ?>">
          <i class="bi bi-currency-exchange"></i>
          <span>Currencies</span>
        </a>
        
        <!-- User Menu Dropdown -->
        <div class="header-user-menu relative">
          <button id="userMenuToggle" 
                  class="header-link flex items-center gap-2 text-gray-300 hover:text-white focus:outline-none">
            <i class="bi bi-person-circle text-xl"></i>
            <span class="hidden sm:inline">Admin</span>
            <i class="bi bi-chevron-down text-sm"></i>
          </button>
          <div id="userMenuDropdown" 
               class="absolute right-0 mt-2 w-48 bg-[#2C3A44] border border-[#4A5A66] rounded-lg shadow-2xl z-50 hidden overflow-hidden">
            <a href="change_password.php" 
               class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#36454F] hover:text-yellow-500 transition <?= $current_page === 'change_password.php' ? 'bg-[#36454F] text-yellow-500' : '' ?>"
               onclick="closeUserMenu()">
              <i class="bi bi-shield-lock"></i>
              <span>Change Password</span>
            </a>
            <div class="border-t border-[#4A5A66]"></div>
            <a href="logout.php" 
               class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-900/30 hover:text-red-300 transition"
               onclick="closeUserMenu()">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </div>
        </div>
      </nav>
      
      <!-- Mobile Menu Toggle -->
      <button id="mobileMenuToggle" 
              class="text-yellow-500 p-2 rounded-lg hover:bg-yellow-500/10 transition"
              aria-label="Toggle Menu">
        <i class="bi bi-list text-2xl"></i>
      </button>
    </div>
  </div>
  
  <!-- Mobile Menu -->
  <div id="mobileMenu" class="border-t border-gray-700">
    <nav class="flex flex-col gap-1 p-4">
      <a href="index.php" 
         class="header-link flex items-center gap-3 px-4 py-3 rounded-lg <?= $current_page === 'index.php' ? 'active' : 'text-gray-300' ?>"
         onclick="closeMobileMenu()">
        <i class="bi bi-car-front-fill text-xl"></i>
        <span>Car Management</span>
      </a>
      
      <a href="visitors.php" 
         class="header-link flex items-center gap-3 px-4 py-3 rounded-lg <?= $current_page === 'visitors.php' ? 'active' : 'text-gray-300' ?>"
         onclick="closeMobileMenu()">
        <i class="bi bi-people-fill text-xl"></i>
        <span>Visitor Tracking</span>
        <?php if ($visitorCount > 0): ?>
        <span class="ml-auto header-badge text-black text-xs font-bold px-2.5 py-1 rounded-full">
          <?= $visitorCount > 99 ? '99+' : $visitorCount ?>
        </span>
        <?php endif; ?>
      </a>
      
      <a href="travel-essentials.php" 
         class="header-link flex items-center gap-3 px-4 py-3 rounded-lg <?= $current_page === 'travel-essentials.php' ? 'active' : 'text-gray-300' ?>"
         onclick="closeMobileMenu()">
        <i class="bi bi-bag-check-fill text-xl"></i>
        <span>Travel Essentials</span>
      </a>
      
      <a href="currencies.php" 
         class="header-link flex items-center gap-3 px-4 py-3 rounded-lg <?= $current_page === 'currencies.php' ? 'active' : 'text-gray-300' ?>"
         onclick="closeMobileMenu()">
        <i class="bi bi-currency-exchange text-xl"></i>
        <span>Currencies</span>
      </a>
      
      <div class="border-t border-gray-700 mt-2 pt-2">
        <a href="change_password.php" 
           class="header-link flex items-center gap-3 px-4 py-3 rounded-lg <?= $current_page === 'change_password.php' ? 'active' : 'text-gray-300' ?>"
           onclick="closeMobileMenu()">
          <i class="bi bi-shield-lock text-xl"></i>
          <span>Change Password</span>
        </a>
        
        <a href="logout.php" 
           class="header-link flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:text-red-300 hover:bg-red-900/20"
           onclick="closeMobileMenu()">
          <i class="bi bi-box-arrow-right text-xl"></i>
          <span>Logout</span>
        </a>
      </div>
    </nav>
  </div>
</header>

<script>
  function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const toggle = document.getElementById('mobileMenuToggle');
    
    if (menu) {
      menu.classList.toggle('open');
      const icon = toggle.querySelector('i');
      if (icon) {
        if (menu.classList.contains('open')) {
          icon.classList.remove('bi-list');
          icon.classList.add('bi-x-lg');
        } else {
          icon.classList.remove('bi-x-lg');
          icon.classList.add('bi-list');
        }
      }
    }
  }
  
  function closeMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const toggle = document.getElementById('mobileMenuToggle');
    
    if (menu) {
      menu.classList.remove('open');
      const icon = toggle.querySelector('i');
      if (icon) {
        icon.classList.remove('bi-x-lg');
        icon.classList.add('bi-list');
      }
    }
  }
  
  // User Menu Dropdown
  function toggleUserMenu() {
    const menu = document.getElementById('userMenuDropdown');
    const toggle = document.getElementById('userMenuToggle');
    const userMenu = document.querySelector('.header-user-menu');
    
    if (menu && userMenu) {
      const isOpen = menu.classList.contains('hidden');
      
      // Close all other dropdowns
      document.querySelectorAll('#userMenuDropdown').forEach(m => {
        if (m !== menu) {
          m.classList.add('hidden');
          m.closest('.header-user-menu')?.classList.remove('open');
        }
      });
      
      if (isOpen) {
        menu.classList.remove('hidden');
        userMenu.classList.add('open');
      } else {
        menu.classList.add('hidden');
        userMenu.classList.remove('open');
      }
    }
  }
  
  function closeUserMenu() {
    const menu = document.getElementById('userMenuDropdown');
    const userMenu = document.querySelector('.header-user-menu');
    if (menu) menu.classList.add('hidden');
    if (userMenu) userMenu.classList.remove('open');
  }
  
  document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('mobileMenuToggle');
    if (toggle) {
      toggle.addEventListener('click', toggleMobileMenu);
    }
    
    // User menu toggle
    const userMenuToggle = document.getElementById('userMenuToggle');
    if (userMenuToggle) {
      userMenuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleUserMenu();
      });
    }
    
    // Close menus when clicking outside
    document.addEventListener('click', function(e) {
      const mobileMenu = document.getElementById('mobileMenu');
      const mobileToggle = document.getElementById('mobileMenuToggle');
      const userMenu = document.querySelector('.header-user-menu');
      const userDropdown = document.getElementById('userMenuDropdown');
      
      // Close mobile menu
      if (mobileMenu && mobileToggle && !mobileMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
        if (mobileMenu.classList.contains('open')) {
          closeMobileMenu();
        }
      }
      
      // Close user menu
      if (userMenu && userDropdown && !userMenu.contains(e.target)) {
        if (!userDropdown.classList.contains('hidden')) {
          closeUserMenu();
        }
      }
    });
    
    // Close menus on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeMobileMenu();
        closeUserMenu();
      }
    });
  });
</script>

