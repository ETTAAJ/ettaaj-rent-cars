<?php
/**
 * Admin Sidebar Component
 * Modern, redesigned sidebar with enhanced UI/UX
 */
if (!isset($pdo)) {
    require_once 'config.php';
}

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);

?>

<style>
  /* Modern Sidebar Styles */
  #adminSidebar {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
    background: linear-gradient(180deg, #1a1a1a 0%, #2C3A44 100%);
  }
  
  /* Mobile Sidebar */
  @media (max-width: 1023px) {
    #adminSidebar {
      transform: translateX(-100%);
    }
    #adminSidebar.open {
      transform: translateX(0);
    }
    #sidebarToggle,
    #sidebarToggleBottom {
      display: block;
    }
  }
  
  /* Desktop Sidebar */
  @media (min-width: 1024px) {
    #adminSidebar {
      transform: translateX(0);
    }
    #sidebarToggle,
    #sidebarToggleBottom {
      display: none;
    }
    main {
      margin-left: 16rem;
    }
  }
  
  /* Sidebar Header */
  .sidebar-header {
    background: linear-gradient(135deg, #FFB22C 0%, #FFA500 100%);
    position: relative;
    overflow: hidden;
  }
  
  .sidebar-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse-glow 3s ease-in-out infinite;
  }
  
  @keyframes pulse-glow {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.8; }
  }
  
  /* Navigation Links */
  .sidebar-link {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    border-radius: 12px;
    margin: 4px 0;
    overflow: hidden;
  }
  
  .sidebar-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, #FFB22C 0%, #FFA500 100%);
    transform: scaleY(0);
    transition: transform 0.3s ease;
    border-radius: 0 4px 4px 0;
  }
  
  .sidebar-link:hover {
    transform: translateX(6px);
    background: rgba(255, 178, 44, 0.1);
  }
  
  .sidebar-link.active {
    background: linear-gradient(135deg, rgba(255, 178, 44, 0.2) 0%, rgba(255, 165, 0, 0.15) 100%);
    color: #FFB22C;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(255, 178, 44, 0.2);
  }
  
  .sidebar-link.active::before {
    transform: scaleY(1);
  }
  
  .sidebar-link.active .sidebar-icon {
    color: #FFB22C;
    transform: scale(1.1);
  }
  
  .sidebar-icon {
    transition: all 0.3s ease;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  /* Badge Styles */
  .sidebar-badge {
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
  
  /* Divider */
  .sidebar-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent 0%, #4A5A66 50%, transparent 100%);
    margin: 16px 0;
  }
  
  /* Footer */
  .sidebar-footer {
    background: linear-gradient(180deg, #1a1a1a 0%, #0f0f0f 100%);
    border-top: 1px solid rgba(255, 178, 44, 0.1);
  }
  
  /* Overlay */
  #sidebarOverlay {
    backdrop-filter: blur(4px);
    background: rgba(0, 0, 0, 0.6);
  }
  
  /* Scrollbar Styling */
  #adminSidebar nav {
    scrollbar-width: thin;
    scrollbar-color: #FFB22C #2C3A44;
  }
  
  #adminSidebar nav::-webkit-scrollbar {
    width: 6px;
  }
  
  #adminSidebar nav::-webkit-scrollbar-track {
    background: #1a1a1a;
  }
  
  #adminSidebar nav::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #FFB22C 0%, #FFA500 100%);
    border-radius: 3px;
  }
  
  #adminSidebar nav::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #FFA500 0%, #FFB22C 100%);
  }
  
  /* Burger Menu Icon Animation */
  .burger-icon {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    width: 24px;
    height: 20px;
    transition: all 0.3s ease;
  }
  
  .burger-icon span {
    display: block;
    height: 3px;
    width: 100%;
    background-color: currentColor;
    border-radius: 3px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
  }
  
  /* When sidebar is open, transform burger to X */
  body.sidebar-open #sidebarToggle .burger-icon span:nth-child(1),
  body.sidebar-open #sidebarToggleBottom .burger-icon span:nth-child(1) {
    transform: rotate(45deg) translate(7px, 7px);
  }
  
  body.sidebar-open #sidebarToggle .burger-icon span:nth-child(2),
  body.sidebar-open #sidebarToggleBottom .burger-icon span:nth-child(2) {
    opacity: 0;
    transform: translateX(-10px);
  }
  
  body.sidebar-open #sidebarToggle .burger-icon span:nth-child(3),
  body.sidebar-open #sidebarToggleBottom .burger-icon span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -7px);
  }
  
  /* Toggle Buttons */
  #sidebarToggle {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  }
  
  #sidebarToggle:hover {
    box-shadow: 0 6px 16px rgba(255, 178, 44, 0.4);
  }
  
  #sidebarToggleBottom {
    box-shadow: 0 8px 20px rgba(255, 178, 44, 0.5);
  }
  
  #sidebarToggleBottom:hover {
    box-shadow: 0 12px 28px rgba(255, 178, 44, 0.7);
    transform: scale(1.1);
  }
</style>

<!-- Sidebar -->
<aside id="adminSidebar" class="fixed left-0 top-0 h-full w-64 z-40">
  <div class="flex flex-col h-full">
    <!-- Logo/Header -->
    <div class="sidebar-header p-6 border-b border-yellow-500/20 relative">
      <div class="flex items-center gap-3 relative z-10">
        <div class="w-12 h-12 bg-black/30 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg border-2 border-white/20">
          <i class="bi bi-speedometer2 text-white text-2xl"></i>
        </div>
        <div>
          <h2 class="text-xl font-bold text-black">Admin Panel</h2>
          <p class="text-xs text-black/70 font-medium">ETTAAJ Rent Cars</p>
        </div>
      </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto p-4">
      <ul class="space-y-1">
        <!-- Dashboard / Cars -->
        <li>
          <a href="index.php" 
             class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg <?= $current_page === 'index.php' ? 'active' : 'text-gray-300 hover:text-white' ?>">
            <div class="sidebar-icon">
              <i class="bi bi-car-front-fill text-xl"></i>
            </div>
            <span class="flex-1">Car Management</span>
          </a>
        </li>
        
        <!-- Travel Essentials -->
        <li>
          <a href="travel-essentials.php" 
             class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg <?= $current_page === 'travel-essentials.php' ? 'active' : 'text-gray-300 hover:text-white' ?>">
            <div class="sidebar-icon">
              <i class="bi bi-bag-check-fill text-xl"></i>
            </div>
            <span class="flex-1">Travel Essentials</span>
          </a>
        </li>
        
        <!-- Currencies -->
        <li>
          <a href="currencies.php" 
             class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg <?= $current_page === 'currencies.php' ? 'active' : 'text-gray-300 hover:text-white' ?>">
            <div class="sidebar-icon">
              <i class="bi bi-currency-exchange text-xl"></i>
            </div>
            <span class="flex-1">Currencies</span>
          </a>
        </li>
        
        <!-- Divider -->
        <li class="sidebar-divider"></li>
        
        <!-- Settings -->
        <li>
          <a href="change_password.php" 
             class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg <?= $current_page === 'change_password.php' ? 'active' : 'text-gray-300 hover:text-white' ?>">
            <div class="sidebar-icon">
              <i class="bi bi-shield-lock text-xl"></i>
            </div>
            <span class="flex-1">Change Password</span>
          </a>
        </li>
        
        <!-- Logout -->
        <li>
          <a href="logout.php" 
             class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:text-red-300 hover:bg-red-900/20 transition-all">
            <div class="sidebar-icon">
              <i class="bi bi-box-arrow-right text-xl"></i>
            </div>
            <span class="flex-1">Logout</span>
          </a>
        </li>
      </ul>
    </nav>
    
    <!-- Footer -->
    <div class="sidebar-footer p-4">
      <div class="text-xs text-gray-400 text-center">
        <p class="font-semibold text-gray-300 mb-1">ETTAAJ Rent Cars</p>
        <p class="text-gray-500 text-[10px]">Admin Dashboard v1.0</p>
      </div>
    </div>
  </div>
</aside>

<!-- Sidebar Toggle Button (Mobile) - Top -->
<button id="sidebarToggle" 
        class="fixed top-4 left-4 z-[9999] bg-[#2C3A44] text-yellow-500 p-3 rounded-xl shadow-2xl hover:bg-[#36454F] transition-all transform hover:scale-110 border-2 border-yellow-500/30 backdrop-blur-sm"
        aria-label="Toggle Sidebar"
        style="display: none;">
  <div class="burger-icon">
    <span></span>
    <span></span>
    <span></span>
  </div>
</button>

<!-- Sidebar Toggle Button (Mobile) - Bottom -->
<button id="sidebarToggleBottom" 
        class="fixed bottom-4 left-4 z-[9999] bg-gradient-to-r from-yellow-500 to-orange-500 text-black p-4 rounded-full shadow-2xl hover:from-orange-500 hover:to-yellow-500 transition-all transform hover:scale-110 active:scale-95 border-2 border-black/20"
        aria-label="Toggle Sidebar"
        style="display: none;">
  <div class="burger-icon">
    <span></span>
    <span></span>
    <span></span>
  </div>
</button>

<!-- Overlay (Mobile) -->
<div id="sidebarOverlay" 
     class="fixed inset-0 z-[9998] hidden"
     onclick="toggleAdminSidebar()"
     style="display: none;"></div>

<script>
  /**
   * Admin Sidebar Toggle Function
   * Modern sidebar with smooth animations
   */
  function toggleAdminSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (!sidebar || !overlay) return;
    
    const isOpen = sidebar.classList.contains('open');
    
    if (isOpen) {
      // Close sidebar
      sidebar.classList.remove('open');
      overlay.classList.add('hidden');
      overlay.style.display = 'none';
      document.body.classList.remove('sidebar-open');
      document.body.style.overflow = '';
    } else {
      // Open sidebar
      sidebar.classList.add('open');
      overlay.classList.remove('hidden');
      overlay.style.display = 'block';
      document.body.classList.add('sidebar-open');
      document.body.style.overflow = 'hidden';
    }
    
    // Update aria-label for accessibility
    const toggleBtn = document.getElementById('sidebarToggle');
    const toggleBtnBottom = document.getElementById('sidebarToggleBottom');
    const newLabel = isOpen ? 'Open Sidebar' : 'Close Sidebar';
    
    if (toggleBtn) toggleBtn.setAttribute('aria-label', newLabel);
    if (toggleBtnBottom) toggleBtnBottom.setAttribute('aria-label', newLabel);
  }
  
  // Initialize sidebar toggle
  document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (toggleBtn) {
      toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleAdminSidebar();
      });
    }
    
    // Bottom toggle button
    const toggleBtnBottom = document.getElementById('sidebarToggleBottom');
    if (toggleBtnBottom) {
      toggleBtnBottom.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleAdminSidebar();
      });
    }
    
    if (overlay) {
      overlay.addEventListener('click', toggleAdminSidebar);
    }
    
    // Close sidebar on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const sidebar = document.getElementById('adminSidebar');
        if (sidebar && sidebar.classList.contains('open')) {
          toggleAdminSidebar();
        }
      }
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
      const sidebar = document.getElementById('adminSidebar');
      const toggleBtn = document.getElementById('sidebarToggle');
      const toggleBtnBottom = document.getElementById('sidebarToggleBottom');
      
      if (window.innerWidth < 1024 && sidebar && sidebar.classList.contains('open')) {
        if (!sidebar.contains(e.target) && !toggleBtn?.contains(e.target) && !toggleBtnBottom?.contains(e.target)) {
          toggleAdminSidebar();
        }
      }
    });
  });
  
  // Handle window resize and initial setup
  function updateSidebarVisibility() {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');
    const toggleBtnBottom = document.getElementById('sidebarToggleBottom');
    
    if (window.innerWidth >= 1024) {
      // Desktop: always show sidebar, hide toggle buttons
      if (sidebar) sidebar.classList.add('open');
      if (overlay) {
        overlay.classList.add('hidden');
        overlay.style.display = 'none';
      }
      if (toggleBtn) {
        toggleBtn.style.display = 'none';
      }
      if (toggleBtnBottom) {
        toggleBtnBottom.style.display = 'none';
      }
      document.body.classList.remove('sidebar-open');
      document.body.style.overflow = '';
    } else {
      // Mobile: show toggle buttons, sidebar closed by default
      if (sidebar) sidebar.classList.remove('open');
      if (overlay) {
        overlay.classList.add('hidden');
        overlay.style.display = 'none';
      }
      if (toggleBtn) {
        toggleBtn.style.display = 'block';
      }
      if (toggleBtnBottom) {
        toggleBtnBottom.style.display = 'block';
      }
      document.body.classList.remove('sidebar-open');
      document.body.style.overflow = '';
    }
  }
  
  // Initial setup
  updateSidebarVisibility();
  
  // Handle window resize
  window.addEventListener('resize', updateSidebarVisibility);
</script>
