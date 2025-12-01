<?php
require_once 'init.php';
require_once 'config.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    header("Location: index.php");
    exit;
}

$minDays = 3;

// === DISCOUNT LOGIC ===
$discountPercent = (int)($car['discount'] ?? 0);
$originalPricePerDay = (float)$car['price_day'];
$discountedPricePerDay = $discountPercent > 0 
    ? $originalPricePerDay * (1 - $discountPercent / 100) 
    : $originalPricePerDay;
$hasDiscount = $discountPercent > 0;

// === INSURANCE PLAN VALUES ===
$insurance_basic_price = (float)($car['insurance_basic_price'] ?? 0);
$insurance_smart_price = (float)($car['insurance_smart_price'] ?? 0);
$insurance_premium_price = (float)($car['insurance_premium_price'] ?? 0);
$insurance_basic_deposit = (float)($car['insurance_basic_deposit'] ?? 0);
$insurance_smart_deposit = (float)($car['insurance_smart_deposit'] ?? 0);
$insurance_premium_deposit = (float)($car['insurance_premium_deposit'] ?? 0);

// === TRAVEL ESSENTIALS ===
$stmt_essentials = $pdo->query("SELECT * FROM travel_essentials WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
$travelEssentials = $stmt_essentials->fetchAll(PDO::FETCH_ASSOC);

function carImageUrl($image)
{
    if (empty($image)) return '';
    $file = 'uploads/' . basename($image);
    $full = $_SERVER['DOCUMENT_ROOT'] . '/' . $file;
    $v = file_exists($full) ? '?v=' . filemtime($full) : '';
    return $file . $v;
}
?>

<?php include 'header.php'; ?>

<!-- Bootstrap Icons -->
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
  body { background-color: var(--bg); color: var(--primary); }
  .bg-card { background-color: var(--card); }
  .bg-card-dark { background-color: var(--card-dark); }
  .car-card-bg { background: #000000 !important; }
  .light .car-card-bg { background: #EFECE3 !important; }
  .light .car-card-bg .text-white { color: #000000 !important; }
  .light .car-card-bg .text-primary { color: #000000 !important; }
  .light .car-card-bg .text-muted { color: #000000 !important; }
  .light .car-card-bg h3 { color: #000000 !important; }
  .light .bg-card-dark .text-primary { color: #000000 !important; }
  .border-border { border-color: var(--border); }
  .text-primary { color: var(--primary); }
  .text-muted { color: var(--muted); }
  .text-gold { color: var(--gold); }

  .tab-bar {
    position: relative;
    background: rgba(30, 30, 30, 0.4);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 8px;
    border: 1px solid rgba(255, 215, 0, 0.2);
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    transition: box-shadow 0.5s ease;
  }
  .light .tab-bar {
    background: rgba(255, 255, 255, 0.6);
    border-color: rgba(217, 119, 6, 0.2);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  }
  .tab-bar::before {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 50%;
    height: 5px;
    background: linear-gradient(90deg, #FFB22C, #FFA500);
    border-radius: 3px;
    transition: transform 0.65s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.5s ease;
    transform: translateX(100%);
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.7);
  }
  .tab-bar.active-details::before { transform: translateX(0%); }
  .tab-bar.active-booking::before { transform: translateX(100%); }

  .tab-item {
    flex: 1; padding: 18px 12px; text-align: center; font-weight: 700; font-size: 1.15rem;
    border-radius: 12px; transition: all 0.4s ease; position: relative; z-index: 10;
  }
  .tab-item svg { width: 26px; height: 26px; margin-right: 10px; }
  html[dir="rtl"] .tab-item svg { margin-right: 0; margin-left: 10px; }
  .tab-item.active { color: #000; }
  .tab-item:not(.active) { color: rgba(255,255,255,0.75); }
  .light .tab-item:not(.active) { color: rgba(30, 41, 59, 0.75); }
  .tab-item:hover:not(.active) { color: #FFB22C; }
  .light .tab-item:hover:not(.active) { color: #d97706; }

  @media (max-width: 640px) {
    .tab-item { padding: 14px 8px; font-size: 1rem; }
    .tab-item svg { width: 22px; height: 22px; }
    .tab-item span { display: block; margin-top: 6px; font-size: 0.8rem; }
  }

  /* Discount Badge */
  .discount-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    z-index: 10;
    background: #10b981;
    color: white;
    font-weight: 800;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    animation: pulse 2s infinite;
  }
  @keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
  }

  /* Input Styling */
  input { color: var(--primary) !important; -webkit-text-fill-color: var(--primary) !important; }
  input::placeholder { color: var(--muted) !important; opacity: 0.7; }
  
  /* Select Styling */
  select { 
    color: var(--primary) !important; 
    -webkit-text-fill-color: var(--primary) !important;
    background-color: rgba(255, 255, 255, 0.1) !important;
  }
  select option {
    background-color: var(--card-dark);
    color: var(--primary);
    padding: 0.5rem;
  }
  select:focus {
    outline: none;
    border-color: var(--gold);
  }

  /* Insurance Cards */
  .insurance-option {
    transition: all 0.4s ease;
    position: relative;
  }
  .insurance-option:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(255, 215, 0, 0.15) !important;
    }
  .insurance-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
  }
  .insurance-option input[type="radio"]:checked + label {
    border: 2px solid #FFB22C;
    background: linear-gradient(135deg, rgba(255,215,0,0.1), rgba(255,165,0,0.05));
    box-shadow: 0 0 25px rgba(255,215,0,0.3);
  }
  .insurance-option label {
    transition: all 0.4s ease;
    cursor: pointer;
  }

  /* Travel Essentials */
  .extra-item {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,215,0,0.15);
    border-radius: 16px;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s ease;
    flex-wrap: wrap;
    gap: 1rem;
  }
  .light .extra-item {
    background: rgba(30, 41, 59, 0.03);
    border-color: rgba(217, 119, 6, 0.15);
  }
  .extra-item:hover {
    background: rgba(255,215,0,0.06);
    border-color: rgba(255,215,0,0.3);
  }
  .light .extra-item:hover {
    background: rgba(217, 119, 6, 0.06);
    border-color: rgba(217, 119, 6, 0.3);
  }
  .extra-item label { flex: 1; min-width: 200px; cursor: pointer; }
  .extra-item svg {
    width: 24px;
    height: 24px;
    color: #FFB22C;
  }
  .extra-price {
    font-weight: bold;
    color: #FFB22C;
    font-size: 1rem;
  }

  /* Toggle Switch */
  .toggle-switch {
    position: relative;
    display: inline-block;
    width: 56px;
    height: 32px;
    cursor: pointer;
  }
  .toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #333;
    transition: all 0.4s ease;
    border-radius: 34px;
    box-shadow: inset 0 2px 6px rgba(0,0,0,0.3);
  }
  .light .slider {
    background-color: #cbd5e1;
  }
  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: all 0.4s ease;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
  }
  input:checked + .slider {
    background: linear-gradient(135deg, #FFB22C, #FFA500);
  }
  input:checked + .slider:before {
    transform: translateX(24px);
  }

  /* WhatsApp Button */
  .whatsapp-btn {
    background: linear-gradient(135deg, #FFD700, #FFA500) !important;
    color: #000 !important;
    font-weight: bold !important;
  }
  .whatsapp-btn:hover { 
    background: linear-gradient(135deg, #FFA500, #FF8C00) !important; 
    transform: scale(1.02); 
  }
  .whatsapp-btn:disabled { 
    opacity: 0.6; 
    cursor: not-allowed; 
    transform: none; 
  }

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

  /* Enhanced Animations */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  @keyframes slideInRight {
    from {
      opacity: 0;
      transform: translateX(30px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  @keyframes scaleIn {
    from {
      opacity: 0;
      transform: scale(0.9);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }
  @keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
  }
  @keyframes priceUpdate {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
  }

  /* Smooth scroll behavior */
  html {
    scroll-behavior: smooth;
  }

  /* Form animations */
  .form-section {
    animation: fadeInUp 0.6s ease-out;
  }
  .form-section:nth-child(1) { animation-delay: 0.1s; }
  .form-section:nth-child(2) { animation-delay: 0.2s; }
  .form-section:nth-child(3) { animation-delay: 0.3s; }
  .form-section:nth-child(4) { animation-delay: 0.4s; }
  .form-section:nth-child(5) { animation-delay: 0.5s; }
  .form-section:nth-child(6) { animation-delay: 0.6s; }

  /* Input focus animations */
  input:focus, select:focus {
    animation: scaleIn 0.3s ease-out;
    transform: scale(1.02);
  }

  /* Price update animation */
  #total-price {
    transition: all 0.3s ease;
  }
  #total-price.updating {
    animation: priceUpdate 0.5s ease;
  }

  /* Button animations */
  button, .whatsapp-btn, a[class*="btn"] {
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  button:active, .whatsapp-btn:active {
    transform: scale(0.98);
  }
  button::before, .whatsapp-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }
  button:active::before, .whatsapp-btn:active::before {
    width: 300px;
    height: 300px;
  }

  /* Card hover effects */
  .insurance-option label, .extra-item {
    position: relative;
    overflow: hidden;
  }
  .insurance-option label::before, .extra-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.1), transparent);
    transition: left 0.5s;
  }
  .insurance-option:hover label::before, .extra-item:hover::before {
    left: 100%;
  }

  /* Loading state */
  .loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
  }
  .loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid var(--gold);
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  /* Responsive Improvements */
  @media (max-width: 1024px) {
    main { padding: 1.5rem 1rem; }
    .tab-bar { margin-bottom: 2rem; }
  }

  @media (max-width: 768px) {
    main { padding: 1rem 0.75rem; }
    
    /* Tab bar mobile */
    .tab-bar {
      padding: 6px;
      border-radius: 12px;
    }
    .tab-item {
      padding: 12px 8px;
      font-size: 0.9rem;
    }
    .tab-item svg {
      width: 20px;
      height: 20px;
      margin-right: 6px;
    }

    /* Car name section */
    h2.text-3xl {
      font-size: 1.75rem;
    }
    .flex.items-center.justify-center.gap-4 {
      flex-wrap: wrap;
      gap: 0.5rem;
    }
    .flex.items-center.justify-center.gap-4 span {
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
    }

    /* Form improvements */
    form {
      padding: 1.5rem !important;
    }
    .form-section h2 {
      font-size: 1.25rem;
      margin-bottom: 1rem;
    }
    input, select {
      padding: 0.875rem !important;
      font-size: 1rem;
      min-height: 48px; /* Touch-friendly */
    }
    label {
      font-size: 0.75rem;
    }

    /* Insurance cards mobile */
    .insurance-option label {
      padding: 1rem !important;
    }
    .insurance-option h4 {
      font-size: 1.125rem;
    }
    .insurance-option span.text-2xl {
      font-size: 1.5rem;
    }

    /* Travel essentials mobile */
    .extra-item {
      flex-direction: column;
      align-items: flex-start;
      padding: 0.875rem;
    }
    .extra-item label {
      min-width: 0;
      width: 100%;
      margin-bottom: 0.5rem;
    }
    .extra-item .toggle-switch {
      margin-left: auto;
      margin-right: 0;
    }
    .extra-price {
      font-size: 0.875rem;
    }

    /* Total price mobile */
    #total-price {
      font-size: 2.5rem !important;
    }
    .bg-gradient-to-r.from-gold {
      padding: 1.25rem !important;
    }

    /* Button mobile */
    button, .whatsapp-btn {
      padding: 1rem !important;
      font-size: 1rem;
      min-height: 48px;
    }

    /* Car image mobile */
    .max-w-4xl {
      margin-bottom: 1.5rem;
    }
  }

  @media (max-width: 640px) {
    main { padding: 0.75rem 0.5rem; }
    
    /* Tab bar small mobile */
    .tab-item {
      padding: 10px 6px;
      font-size: 0.8rem;
    }
    .tab-item svg {
      width: 18px;
      height: 18px;
      margin-right: 4px;
    }
    .tab-item span {
      display: block;
      margin-top: 4px;
      font-size: 0.7rem;
    }

    /* Car name small mobile */
    h2.text-3xl {
      font-size: 1.5rem;
      line-height: 1.3;
    }
    .text-2xl {
      font-size: 1.25rem;
    }

    /* Form small mobile */
    form {
      padding: 1rem !important;
      border-radius: 1.5rem !important;
    }
    .grid.grid-cols-1.sm\\:grid-cols-2 {
      grid-template-columns: 1fr;
      gap: 0.75rem;
    }

    /* Insurance cards small mobile */
    .insurance-option label {
      padding: 0.875rem !important;
    }
    .insurance-option .flex.justify-between {
      flex-direction: column;
      gap: 0.5rem;
    }

    /* Total price small mobile */
    #total-price {
      font-size: 2rem !important;
    }
    #days-count, #insurance-info {
      font-size: 0.875rem;
    }

    /* Button small mobile */
    button, .whatsapp-btn {
      padding: 0.875rem !important;
      font-size: 0.9rem;
    }
  }

  @media (max-width: 480px) {
    /* Extra small devices */
    .tab-item {
      padding: 8px 4px;
      font-size: 0.75rem;
    }
    form {
      padding: 0.875rem !important;
    }
    #total-price {
      font-size: 1.75rem !important;
    }
  }

  /* Touch device optimizations */
  @media (hover: none) and (pointer: coarse) {
    button, a, .tab-item, .insurance-option label, .extra-item {
      -webkit-tap-highlight-color: rgba(255, 215, 0, 0.2);
    }
    .insurance-option:hover {
      transform: none;
    }
    .insurance-option:active {
      transform: scale(0.98);
    }
  }

  /* Reduced motion support */
  @media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
      animation-duration: 0.01ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.01ms !important;
    }
  }

  /* ============================================
     DATE RANGE PICKER STYLES
     ============================================ */
  .date-range-picker-container {
    position: relative;
    width: 100%;
  }

  .date-range-input {
    width: 100%;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 215, 0, 0.3);
    border-radius: 1rem;
    color: var(--primary);
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .date-range-input:hover {
    border-color: rgba(255, 215, 0, 0.5);
    background: rgba(255, 255, 255, 0.15);
  }

  .date-range-input:focus {
    outline: none;
    border-color: #FFB22C;
    box-shadow: 0 0 0 3px rgba(255, 178, 44, 0.2);
  }

  .date-range-picker-popup {
    position: absolute;
    top: calc(100% + 0.5rem);
    left: 0;
    z-index: 1000;
    background: var(--card-dark);
    border: 1px solid rgba(255, 215, 0, 0.3);
    border-radius: 1.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    padding: 1.5rem;
    min-width: 600px;
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .date-range-picker-popup.show {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: all;
  }

  .date-range-picker-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }

  .date-range-picker-nav {
    display: flex;
    gap: 1rem;
    align-items: center;
  }

  .date-range-picker-nav-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 215, 0, 0.3);
    border-radius: 0.5rem;
    color: #FFB22C;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .date-range-picker-nav-btn:hover {
    background: rgba(255, 178, 44, 0.2);
    border-color: #FFB22C;
    transform: scale(1.1);
  }

  .date-range-picker-nav-btn:active {
    transform: scale(0.95);
  }

  .date-range-picker-nav-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }

  .date-range-picker-month-year {
    font-size: 1.125rem;
    font-weight: 700;
    color: #FFB22C;
    min-width: 150px;
    text-align: center;
  }

  .date-range-picker-calendars {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }

  .date-range-picker-calendar {
    display: flex;
    flex-direction: column;
  }

  .date-range-picker-calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.5rem;
    margin-bottom: 0.75rem;
  }

  .date-range-picker-weekday {
    text-align: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.6);
    text-transform: uppercase;
    padding: 0.5rem 0;
  }

  .light .date-range-picker-weekday {
    color: rgba(30, 41, 59, 0.6);
  }

  .date-range-picker-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.25rem;
  }

  .date-range-picker-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    color: var(--primary);
    position: relative;
  }

  .date-range-picker-day:hover:not(.disabled):not(.start-date):not(.end-date):not(.in-range) {
    background: rgba(255, 178, 44, 0.2);
    transform: scale(1.1);
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .date-range-picker-day:not(.disabled):not(.start-date):not(.end-date) {
    cursor: pointer;
  }

  .date-range-picker-day.start-date,
  .date-range-picker-day.end-date {
    animation: dateSelected 0.3s ease-out;
  }

  @keyframes dateSelected {
    0% {
      transform: scale(0.8);
      opacity: 0.7;
    }
    50% {
      transform: scale(1.15);
    }
    100% {
      transform: scale(1);
      opacity: 1;
    }
  }

  .date-range-picker-day.disabled {
    opacity: 0.3;
    cursor: not-allowed;
    color: rgba(255, 255, 255, 0.3);
  }

  .light .date-range-picker-day.disabled {
    color: rgba(30, 41, 59, 0.3);
  }

  /* Today's date styling - visually disabled */
  .date-range-picker-day.is-today {
    position: relative;
    opacity: 0.4;
    cursor: not-allowed;
    background: rgba(255, 0, 0, 0.1) !important;
    border: 2px dashed rgba(255, 0, 0, 0.4);
    color: rgba(255, 255, 255, 0.4) !important;
  }

  .date-range-picker-day.is-today::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: rgba(255, 0, 0, 0.6);
    transform: translateY(-50%);
  }

  .light .date-range-picker-day.is-today {
    background: rgba(239, 68, 68, 0.1) !important;
    border-color: rgba(239, 68, 68, 0.4);
    color: rgba(30, 41, 59, 0.4) !important;
  }

  .light .date-range-picker-day.is-today::after {
    background: rgba(239, 68, 68, 0.6);
  }

  .date-range-picker-day.in-range {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 0;
  }

  .light .date-range-picker-day.in-range {
    background: rgba(217, 119, 6, 0.1);
  }

  .date-range-picker-day.in-range:first-child {
    border-top-left-radius: 0.5rem;
    border-bottom-left-radius: 0.5rem;
  }

  .date-range-picker-day.in-range:last-child {
    border-top-right-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
  }

  .date-range-picker-day.start-date {
    background: #FFB22C;
    color: #000;
    font-weight: 700;
    border-radius: 0.5rem;
    z-index: 2;
  }

  .date-range-picker-day.end-date {
    background: #FFB22C;
    color: #000;
    font-weight: 700;
    border-radius: 0.5rem;
    z-index: 2;
  }

  .date-range-picker-day.start-date.in-range {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }

  .date-range-picker-day.end-date.in-range {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }

  .date-range-picker-day.selected {
    background: rgba(255, 178, 44, 0.3);
  }

  .date-range-picker-footer {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 215, 0, 0.2);
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .date-range-picker-note {
    text-align: center;
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.7);
    font-style: italic;
  }

  .light .date-range-picker-note {
    color: rgba(30, 41, 59, 0.7);
  }

  .date-range-picker-status {
    margin-bottom: 0.75rem;
    padding: 0.75rem;
    background: rgba(255, 178, 44, 0.1);
    border-radius: 0.75rem;
    border: 1px solid rgba(255, 178, 44, 0.2);
  }

  .date-range-picker-status p {
    margin: 0;
    font-style: normal;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
  }

  .date-range-picker-status i {
    font-size: 1.125rem;
    color: #FFB22C;
  }

  .date-range-picker-status strong {
    color: #FFB22C;
    font-weight: 700;
  }

  .light .date-range-picker-status {
    background: rgba(217, 119, 6, 0.1);
    border-color: rgba(217, 119, 6, 0.2);
  }

  .light .date-range-picker-status i,
  .light .date-range-picker-status strong {
    color: #d97706;
  }

  .date-range-picker-same-day-warning {
    color: #fbbf24 !important;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
  }

  .date-range-picker-same-day-warning i {
    font-size: 1rem;
  }

  .date-range-picker-error {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.5);
    border-radius: 0.5rem;
    padding: 0.75rem;
    margin-top: 0.5rem;
    color: #fca5a5;
    font-size: 0.875rem;
    text-align: center;
    font-weight: 500;
  }

  .date-range-picker-error.hidden {
    display: none;
  }

  .light .date-range-picker-error {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: #dc2626;
  }

  .date-range-picker-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
  }

  .date-range-picker-btn {
    padding: 0.75rem 2rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
  }

  .date-range-picker-btn-cancel {
    background: rgba(255, 255, 255, 0.1);
    color: var(--primary);
    border: 1px solid rgba(255, 215, 0, 0.3);
  }

  .date-range-picker-btn-cancel:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 215, 0, 0.5);
    transform: translateY(-2px);
  }

  .light .date-range-picker-btn-cancel {
    background: rgba(30, 41, 59, 0.1);
    color: #1e293b;
    border-color: rgba(217, 119, 6, 0.3);
  }

  .date-range-picker-btn-apply {
    background: linear-gradient(135deg, #FFB22C, #FFA500);
    color: #000;
    font-weight: 700;
  }

  .date-range-picker-btn-apply:hover {
    background: linear-gradient(135deg, #FFA500, #FF8C00);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 178, 44, 0.4);
  }

  .date-range-picker-btn-apply:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
    background: rgba(255, 178, 44, 0.3);
  }

  .date-range-picker-btn-apply:not(:disabled) {
    box-shadow: 0 4px 15px rgba(255, 178, 44, 0.4);
    animation: pulse-glow 2s ease-in-out infinite;
  }

  @keyframes pulse-glow {
    0%, 100% {
      box-shadow: 0 4px 15px rgba(255, 178, 44, 0.4);
    }
    50% {
      box-shadow: 0 4px 25px rgba(255, 178, 44, 0.6);
    }
  }

  .date-range-picker-btn-apply i {
    margin-right: 0.5rem;
  }

  @media (max-width: 768px) {
    .date-range-picker-popup {
      min-width: 100%;
      left: 0;
      right: 0;
      padding: 1rem;
    }

    .date-range-picker-calendars {
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    .date-range-picker-actions {
      flex-direction: column;
    }

    .date-range-picker-btn {
      width: 100%;
    }
  }
</style>

<main class="max-w-7xl mx-auto px-4 py-12 bg-[var(--bg)] text-primary">

  <!-- LUXURY 2-TAB BAR -->
  <div class="max-w-3xl mx-auto mb-16">
    <div class="tab-bar active-booking" id="tab-bar" style="direction: ltr;">
      <div class="flex" style="direction: ltr;">
        <a href="<?= langUrl('car-detail.php', ['id' => $car['id']]) ?>" id="details-tab-link" class="tab-item flex items-center justify-center">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5"/>
          </svg>
          <span><?= t('car_details') ?></span>
        </a>
        <a href="<?= langUrl('booking.php', ['id' => $car['id']]) ?>" class="tab-item flex items-center justify-center active">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <span><?= t('booking_details') ?></span>
        </a>
      </div>
    </div>
  </div>

  <!-- CAR NAME -->
  <div class="text-center mb-6 sm:mb-8" data-aos="fade-up">
    <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gold mb-2 px-4">
      <?= htmlspecialchars($car['name']) ?>
    </h2>
    <div class="flex items-center justify-center gap-2 sm:gap-4 text-xs sm:text-sm text-muted flex-wrap px-4">
      <span class="flex items-center gap-2">
        <i class="bi bi-person-fill text-gold"></i>
        <span class="text-white" dir="ltr"><?= formatNumber($car['seats']) ?></span> <span class="text-white"><?= $text['seats'] ?></span>
      </span>
      <span class="flex items-center gap-2">
        <i class="bi bi-briefcase-fill text-gold"></i>
        <span class="text-white" dir="ltr"><?= formatNumber($car['bags']) ?></span> <span class="text-white"><?= $text['bags'] ?></span>
      </span>
      <span class="px-3 py-1 bg-card-dark rounded-full border border-border"><?= $car['gear'] === 'Manual' ? $text['manual'] : ($car['gear'] === 'Automatic' ? $text['automatic'] : htmlspecialchars($car['gear'])) ?></span>
      <span class="px-3 py-1 bg-card-dark rounded-full border border-border"><?= $car['fuel'] === 'Diesel' ? $text['diesel'] : ($car['fuel'] === 'Petrol' ? $text['petrol'] : htmlspecialchars($car['fuel'])) ?></span>
    </div>
    <div class="mt-3 sm:mt-4 px-4">
      <span class="text-xl sm:text-2xl font-black text-gold" dir="ltr">
        <?= formatPrice($discountedPricePerDay) ?>
      </span>
      <span class="text-base sm:text-lg text-muted">/<?= $text['day'] ?></span>
      <?php if ($hasDiscount): ?>
        <span class="ml-2 text-lg text-green-400 line-through opacity-70" dir="ltr">
          <?= formatPrice($originalPricePerDay) ?>
        </span>
        <span class="ml-2 px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm font-bold" dir="ltr">
          -<?= formatNumber($discountPercent) ?>% OFF
        </span>
      <?php endif; ?>
      <!-- Weekly and Monthly Prices -->
      <div class="flex justify-center gap-2 sm:gap-3 mt-3 text-xs sm:text-sm font-medium flex-wrap">
        <span class="px-2 sm:px-3 py-1 bg-card-dark rounded-full border border-border text-muted">
          <?= $text['week'] ?>: <strong class="text-primary" dir="ltr"><?= formatPrice((float)$car['price_week']) ?></strong>
        </span>
        <span class="px-2 sm:px-3 py-1 bg-card-dark rounded-full border border-border text-muted">
          <?= $text['month'] ?>: <strong class="text-primary" dir="ltr"><?= formatPrice((float)$car['price_month']) ?></strong>
        </span>
      </div>
    </div>
  </div>
        
  <!-- CAR IMAGE -->
  <div class="max-w-4xl mx-auto mb-8" data-aos="fade-up">
    <div class="relative w-full rounded-3xl overflow-hidden shadow-2xl border border-border group" style="box-shadow: 0 10px 30px rgba(255, 178, 44, 0.3);">
      <div class="relative w-full pt-[56.25%] car-card-bg overflow-hidden">
          <?php
          $imgUrl = !empty($car['image'])
            ? carImageUrl($car['image'])
              : 'https://via.placeholder.com/800x450/36454F/FFFFFF?text=' . urlencode($car['name']);
          ?>
          <img src="<?= htmlspecialchars($imgUrl) ?>" 
               alt="<?= htmlspecialchars($car['name']) ?>"
               class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

          <?php if ($hasDiscount): ?>
          <div class="discount-badge">
            -<?= $discountPercent ?>%
          </div>
          <?php endif; ?>
        </div>
            </div>
          </div>

  <div class="max-w-4xl mx-auto">
    <!-- BOOKING FORM -->
    <div data-aos="fade-up" class="space-y-8">
      <form id="booking-form" class="bg-card/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-border p-6 sm:p-8 md:p-10 space-y-6 sm:space-y-8">
        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

        <!-- DATES -->
        <div class="form-section">
          <h2 class="text-xl sm:text-2xl font-bold text-gold mb-4"><?= $text['trip_dates'] ?></h2>
          <div class="grid grid-cols-1 gap-4">
            <div class="relative">
              <div class="date-range-picker-container">
                <input type="text" 
                       id="date-range-input" 
                       class="date-range-input w-full p-4 bg-white/10 border border-border rounded-2xl focus:ring-2 focus:ring-gold focus:border-gold transition" 
                       placeholder="<?= $text['pickup_date'] ?> → <?= $text['return_date'] ?>" 
                       readonly
                       required>
                <input type="hidden" name="pickup" id="pickup">
                <input type="hidden" name="return" id="return">
                <div id="date-range-picker-popup" class="date-range-picker-popup"></div>
              </div>
              <label class="absolute <?= $lang === 'ar' ? 'right-4' : 'left-4' ?> -top-2.5 bg-[var(--card)] px-3 text-xs font-bold text-gold"><?= $text['trip_dates'] ?></label>
            </div>
          </div>
          <!-- Trip Time Selection -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
            <div class="relative">
              <input type="time" 
                     name="pickup_time" 
                     id="pickup_time" 
                     value="10:00"
                     class="w-full p-4 bg-white/10 border border-border rounded-2xl focus:ring-2 focus:ring-gold focus:border-gold transition text-primary">
              <label class="absolute <?= $lang === 'ar' ? 'right-4' : 'left-4' ?> -top-2.5 bg-[var(--card)] px-3 text-xs font-bold text-gold"><?= $text['pickup_date'] ?> <?= $text['time'] ?? 'Time' ?></label>
            </div>
            <div class="relative">
              <input type="time" 
                     name="return_time" 
                     id="return_time" 
                     value="10:00"
                     class="w-full p-4 bg-white/10 border border-border rounded-2xl focus:ring-2 focus:ring-gold focus:border-gold transition text-primary">
              <label class="absolute <?= $lang === 'ar' ? 'right-4' : 'left-4' ?> -top-2.5 bg-[var(--card)] px-3 text-xs font-bold text-gold"><?= $text['return_date'] ?> <?= $text['time'] ?? 'Time' ?></label>
            </div>
          </div>
          <p id="date-error" class="text-red-400 text-sm mt-2 hidden"><?= $text['return_date_error'] ?> <span dir="ltr"><?= formatNumber($minDays) ?></span> <?= $text['after_pickup'] ?></p>
        </div>

        <!-- PICKUP LOCATION -->
        <div class="form-section">
          <h2 class="text-xl sm:text-2xl font-bold text-gold mb-4"><?= $text['pickup_location'] ?></h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="relative">
              <select name="pickup_location" id="pickup_location" required 
                      class="w-full p-4 bg-white/10 border border-border rounded-2xl focus:ring-2 focus:ring-gold focus:border-gold transition appearance-none cursor-pointer text-primary pr-10">
                <option value="" class="bg-[var(--card-dark)] text-primary"><?= $text['pickup_location'] ?>...</option>
                <option value="<?= htmlspecialchars($text['marrakech_airport']) ?>" class="bg-[var(--card-dark)] text-primary"><?= $text['marrakech_airport'] ?></option>
                <option value="<?= htmlspecialchars($text['casablanca_airport']) ?>" class="bg-[var(--card-dark)] text-primary"><?= $text['casablanca_airport'] ?></option>
              </select>
              <label class="absolute <?= $lang === 'ar' ? 'right-4' : 'left-4' ?> -top-2.5 bg-[var(--card)] px-3 text-xs font-bold text-gold pointer-events-none"><?= $text['pickup_location'] ?></label>
              <div class="absolute <?= $lang === 'ar' ? 'left-4' : 'right-4' ?> top-1/2 -translate-y-1/2 pointer-events-none z-10">
                <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- PROTECTION PLAN -->
        <div class="form-section">
          <h2 class="text-xl sm:text-2xl font-bold text-gold mb-4"><?= $text['protection_plan'] ?></h2>
        <div class="space-y-4">

            <div class="insurance-option">
              <input type="radio" name="insurance" id="basic" value="<?= $text['basic_insurance'] ?><?= $insurance_basic_price > 0 ? ' - MAD' . formatNumber($insurance_basic_price) . '/' . $text['day'] : ' - ' . $text['free'] ?>" checked>
              <label for="basic" class="block p-6 bg-card-dark/80 rounded-2xl border border-border hover:border-gold/50 transition-all">
                <div class="flex justify-between items-start mb-3">
                  <div>
                    <h4 class="text-xl font-bold"><?= $text['basic_insurance'] ?></h4>
                    <p class="text-sm text-green-400"><?= $text['standard_coverage'] ?></p>
                  </div>
                  <span class="text-2xl font-black text-gold" dir="ltr">
                    <?php if ($insurance_basic_price > 0): ?>
                      <?= formatPrice($insurance_basic_price) ?>/<?= $text['day'] ?>
                    <?php else: ?>
                      <?= $text['free'] ?>
                    <?php endif; ?>
                  </span>
                </div>
                <?php if ($insurance_basic_deposit > 0): ?>
                  <p class="text-sm text-muted mb-3"><?= $text['deposit'] ?>: <strong dir="ltr"><?= formatPrice($insurance_basic_deposit) ?></strong></p>
                <?php endif; ?>
                <ul class="text-sm space-y-1 text-muted">
                  <li>• <?= $text['third_party_liability'] ?></li>
                  <li>• <?= $text['basic_collision'] ?></li>
                  <li>• <?= $text['standard_theft'] ?></li>
                </ul>
              </label>
            </div>

            <div class="insurance-option">
              <input type="radio" name="insurance" id="smart" value="<?= $text['smart_insurance'] ?> - MAD<?= formatNumber($insurance_smart_price) ?>/<?= $text['day'] ?>">
              <label for="smart" class="block p-6 bg-card-dark/80 rounded-2xl border border-border hover:border-gold/50 transition-all">
                <div class="flex justify-between items-start mb-3">
                  <div>
                    <h4 class="text-xl font-bold"><?= $text['smart_insurance'] ?></h4>
                    <p class="text-sm text-amber-400"><?= $text['citadine_rate'] ?></p>
                  </div>
                  <span class="text-2xl font-black text-gold" dir="ltr"><?= formatPrice($insurance_smart_price) ?>/<?= $text['day'] ?></span>
                </div>
                <?php if ($insurance_smart_deposit > 0): ?>
                  <p class="text-sm text-[var(--text-muted)] mb-3"><?= $text['deposit'] ?>: <strong dir="ltr"><?= formatPrice($insurance_smart_deposit) ?></strong></p>
                <?php endif; ?>
                <ul class="text-sm space-y-1 text-[var(--text-muted)]">
                  <li>• <?= $text['all_basic_coverage'] ?></li>
                  <li>• <?= $text['reduced_excess'] ?></li>
                  <li>• <?= $text['window_tire'] ?></li>
                  <li>• <?= $text['personal_accident'] ?></li>
                </ul>
              </label>
            </div>

            <div class="insurance-option">
              <input type="radio" name="insurance" id="premium" value="<?= $text['premium_insurance'] ?> - MAD<?= formatNumber($insurance_premium_price) ?>/<?= $text['day'] ?>">
              <label for="premium" class="block p-6 bg-card-dark/80 rounded-2xl border border-border hover:border-gold/50 transition-all">
                <div class="flex justify-between items-start mb-3">
                  <div>
                    <h4 class="text-xl font-bold"><?= $text['premium_insurance'] ?></h4>
                    <p class="text-sm text-amber-400"><?= $text['citadine_rate'] ?></p>
                  </div>
                  <span class="text-2xl font-black text-gold" dir="ltr"><?= formatPrice($insurance_premium_price) ?>/<?= $text['day'] ?></span>
                </div>
                <?php if ($insurance_premium_deposit > 0): ?>
                  <p class="text-sm text-[var(--text-muted)] mb-3"><?= $text['deposit'] ?>: <strong dir="ltr"><?= formatPrice($insurance_premium_deposit) ?></strong></p>
                <?php endif; ?>
                <ul class="text-sm space-y-1 text-[var(--text-muted)]">
                  <li>• <?= $text['all_basic_coverage'] ?></li>
                  <li>• <?= $text['zero_excess'] ?></li>
                  <li>• <?= $text['premium_roadside'] ?></li>
                  <li>• <?= $text['personal_effects'] ?></li>
                  <li>• <?= $text['extended_liability'] ?></li>
                </ul>
              </label>
            </div>
          </div>
          <p class="text-xs text-center text-muted mt-4">
            <?= $text['insurance_note'] ?>
          </p>
        </div>

        <!-- TRAVEL ESSENTIALS -->
        <div class="form-section">
          <h2 class="text-xl sm:text-2xl font-bold text-gold mb-4"><?= $text['travel_essentials'] ?></h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php if (empty($travelEssentials)): ?>
              <div class="col-span-2 text-center text-muted py-8">
                No travel essentials available at the moment.
              </div>
            <?php else: ?>
              <?php foreach ($travelEssentials as $essential): 
                $essentialId = 'essential_' . $essential['id'];
                $priceText = number_format($essential['price'], 2);
                $unitText = $essential['per_day'] ? '/' . $text['day'] : '/rental';
                
                // Get language-specific name and description
                $nameKey = 'name_' . $lang;
                $descKey = 'description_' . $lang;
                $essentialName = !empty($essential[$nameKey]) ? $essential[$nameKey] : ($essential['name_en'] ?? $essential['name'] ?? '');
                $essentialDesc = !empty($essential[$descKey]) ? $essential[$descKey] : ($essential['description_en'] ?? $essential['description'] ?? '');
                
                $valueText = htmlspecialchars($essentialName) . ' - MAD ' . $priceText . $unitText;
                $displayPrice = formatPrice($essential['price'], 2);
              ?>
                <div class="extra-item">
                  <label for="<?= $essentialId ?>" class="flex-1 flex items-center gap-3">
                    <?php if ($essential['icon']): ?>
                      <i class="bi <?= htmlspecialchars($essential['icon']) ?> text-2xl text-gold"></i>
                    <?php else: ?>
                      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <?php endif; ?>
                    <div>
                      <div class="font-semibold"><?= htmlspecialchars($essentialName) ?></div>
                      <?php if ($essentialDesc): ?>
                        <div class="text-xs text-[var(--text-muted)]"><?= htmlspecialchars($essentialDesc) ?></div>
                      <?php endif; ?>
                    </div>
                  </label>
                  <div class="flex items-center gap-3">
                    <span class="extra-price" dir="ltr"><?= $displayPrice ?><?= $unitText ?></span>
                    <div class="toggle-switch">
                      <input type="checkbox" id="<?= $essentialId ?>" name="extras[]" 
                             value="<?= htmlspecialchars($valueText) ?>"
                             data-essential-id="<?= $essential['id'] ?>"
                             data-price="<?= $essential['price'] ?>"
                             data-per-day="<?= $essential['per_day'] ?>">
                      <span class="slider"></span>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- TOTAL PRICE -->
        <div class="form-section bg-gradient-to-r from-gold/10 to-yellow-500/10 p-5 sm:p-7 rounded-2xl border border-gold/30 text-center">
          <p class="text-gold font-bold mb-3 text-base sm:text-lg"><?= $text['total_estimated_price'] ?></p>
          <p id="total-price" class="text-3xl sm:text-4xl md:text-5xl font-black text-primary transition-all duration-300" role="status" aria-live="polite" dir="ltr">MAD0</p>
          <p id="days-count" class="text-muted mt-2 text-lg" aria-live="polite"></p>
          <p id="insurance-info" class="text-sm text-gold mt-3 font-medium"><?= $text['basic_insurance_included'] ?></p>
          <p id="extras-info" class="text-sm text-amber-300 mt-2"></p>
          <?php if ($hasDiscount): ?>
            <p class="text-green-400 text-sm mt-2 font-bold"><?= $text['you_save'] ?> <span dir="ltr"><?= formatPrice(($originalPricePerDay - $discountedPricePerDay) * $minDays) ?></span> <?= $text['on_minimum_rental'] ?></p>
          <?php endif; ?>
        </div>

        <!-- PERSONAL INFO -->
        <div class="form-section">
          <h2 class="text-xl sm:text-2xl font-bold text-gold mb-4"><?= $text['personal_details'] ?></h2>
          <div class="space-y-4">
            <input type="text" name="name" required placeholder="<?= $text['full_name'] ?>" 
                   class="w-full p-4 bg-white/10 border border-border rounded-2xl focus:ring-2 focus:ring-gold text-primary">
            <input type="email" name="email" required placeholder="<?= $text['email_address'] ?>" 
                   class="w-full p-4 bg-white/10 border border-border rounded-2xl focus:ring-2 focus:ring-gold text-primary">
            <input type="tel" name="phone" required placeholder="<?= $text['phone_whatsapp'] ?>" 
                   class="w-full p-4 bg-white/10 border border-border rounded-2xl focus:ring-2 focus:ring-gold text-primary phone-number" dir="ltr">
          </div>
        </div>

        <!-- SUBMIT -->
        <button type="submit" id="submit-btn" disabled 
                class="whatsapp-btn w-full py-6 rounded-2xl shadow-2xl transition-all duration-300 flex items-center justify-center gap-4 text-xl font-bold">
          <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.134.297-.347.446-.52.149-.174.198-.297.297-.446.099-.148.05-.273-.024-.385-.074-.112-.67-1.62-.92-2.22-.246-.594-.495-.59-.67-.599-.174-.008-.371-.008-.569-.008-.197 0-.52.074-.792.372-.273.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.558 5.745 8.623 8.05.297.149.595.223.893.298.297.074.595.05.893-.025.297-.074 1.255-.52 1.43-.966.173-.446.173-.82.124-.966-.05-.148-.198-.297-.446-.446zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
          </svg>
          <?= t('send_booking') ?>
        </button>
      </form>
    </div>
  </div>

</main>

<?php include 'footer.php'; ?>

<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 800 });

  // ============================================
  // DATE RANGE PICKER CLASS
  // ============================================
  class DateRangePicker {
    constructor(inputId, options = {}) {
      this.input = document.getElementById(inputId);
      this.popup = document.getElementById('date-range-picker-popup');
      this.pickupInput = document.getElementById('pickup');
      this.returnInput = document.getElementById('return');
      this.minDays = options.minDays || 3;
      this.onApply = options.onApply || null;
      this.onCancel = options.onCancel || null;
      
      this.startDate = null;
      this.endDate = null;
      this.currentMonth = new Date();
      this.isOpen = false;
      
      this.init();
    }

    init() {
      this.attachEvents();
      this.updateInput();
    }

    attachEvents() {
      // Open/close on input click
      this.input.addEventListener('click', (e) => {
        e.stopPropagation();
        this.toggle();
      });

      // Close on outside click (but not when clicking inside popup)
      document.addEventListener('click', (e) => {
        if (this.isOpen && 
            !this.popup.contains(e.target) && 
            !this.input.contains(e.target) &&
            !e.target.closest('.date-range-picker-popup')) {
          this.close();
        }
      });

      // Close on escape key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && this.isOpen) {
          this.close();
        }
      });

      // Prevent popup clicks from closing the popup
      this.popup.addEventListener('click', (e) => {
        e.stopPropagation();
      });
    }

    toggle() {
      if (this.isOpen) {
        this.close();
      } else {
        this.open();
      }
    }

    open() {
      this.isOpen = true;
      this.popup.classList.add('show');
      this.render();
      // Initialize drag selection after rendering (only once)
      if (!this.dragHandlersAttached) {
        setTimeout(() => {
          this.initDragSelection();
        }, 50);
      }
    }

    close() {
      this.isOpen = false;
      this.popup.classList.remove('show');
    }

    render() {
      const month1 = new Date(this.currentMonth);
      const month2 = new Date(this.currentMonth);
      month2.setMonth(month2.getMonth() + 1);

      this.popup.innerHTML = `
        <div class="date-range-picker-header">
          <div class="date-range-picker-nav">
            <button class="date-range-picker-nav-btn" onclick="event.stopPropagation(); dateRangePicker.prevMonth()" type="button">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
            </button>
            <button class="date-range-picker-nav-btn" onclick="event.stopPropagation(); dateRangePicker.nextMonth()" type="button">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </button>
          </div>
        </div>
        <div class="date-range-picker-calendars">
          ${this.renderCalendar(month1)}
          ${this.renderCalendar(month2)}
        </div>
            <div class="date-range-picker-footer">
              <div id="date-picker-status" class="date-range-picker-status">
                ${!this.startDate ? 
                  `<p class="date-range-picker-note"><i class="bi bi-calendar-check"></i> Click to select pickup date</p>` :
                  !this.endDate ?
                  `<p class="date-range-picker-note"><i class="bi bi-calendar-check"></i> Pickup: <strong>${this.formatDate(this.startDate)}</strong> - Now select return date</p>` :
                  `<p class="date-range-picker-note text-green-400"><i class="bi bi-check-circle"></i> Selected: <strong>${this.formatDate(this.startDate)}</strong> to <strong>${this.formatDate(this.endDate)}</strong></p>`
                }
              </div>
              <p class="date-range-picker-note" style="font-size: 0.75rem; margin-top: 0.5rem;">Minimum rental period is ${this.minDays} days</p>
              <p class="date-range-picker-note date-range-picker-same-day-warning">
                <i class="bi bi-info-circle"></i> Same-day rentals are not accepted. Please choose a date starting from tomorrow.
              </p>
              <div id="same-day-error" class="date-range-picker-error hidden"></div>
              <div class="date-range-picker-actions">
                <button class="date-range-picker-btn date-range-picker-btn-cancel" onclick="event.stopPropagation(); dateRangePicker.handleCancel()" type="button">
                  Cancel
                </button>
                <button id="date-picker-apply-btn" class="date-range-picker-btn date-range-picker-btn-apply" onclick="event.stopPropagation(); dateRangePicker.handleApply()" type="button" ${!this.isValidRange() ? 'disabled' : ''}>
                  ${this.isValidRange() ? '<i class="bi bi-check-circle"></i> Apply Selection' : 'Apply'}
                </button>
              </div>
            </div>
      `;
    }

    renderCalendar(month) {
      const year = month.getFullYear();
      const monthIndex = month.getMonth();
      const monthName = month.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
      
      const firstDay = new Date(year, monthIndex, 1);
      const lastDay = new Date(year, monthIndex + 1, 0);
      const daysInMonth = lastDay.getDate();
      const startingDayOfWeek = firstDay.getDay();

      let html = `
        <div class="date-range-picker-calendar">
          <div class="date-range-picker-month-year">${monthName}</div>
          <div class="date-range-picker-calendar-header">
            ${['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(day => 
              `<div class="date-range-picker-weekday">${day}</div>`
            ).join('')}
          </div>
          <div class="date-range-picker-days">
      `;

      // Empty cells for days before month starts
      for (let i = 0; i < startingDayOfWeek; i++) {
        html += '<div class="date-range-picker-day"></div>';
      }

      // Days of the month
      for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, monthIndex, day);
        const dateStr = this.formatDate(date);
        const isDisabled = this.isDateDisabled(date);
        const isToday = this.isToday(date);
        const isStart = this.startDate && this.formatDate(this.startDate) === dateStr;
        const isEnd = this.endDate && this.formatDate(this.endDate) === dateStr;
        const inRange = this.isDateInRange(date);

        let classes = 'date-range-picker-day';
        if (isDisabled) classes += ' disabled';
        if (isToday) classes += ' is-today';
        if (isStart) classes += ' start-date';
        if (isEnd) classes += ' end-date';
        if (inRange) classes += ' in-range';

        html += `
          <div class="${classes}" 
               data-date="${dateStr}"
               onclick="event.stopPropagation(); dateRangePicker.selectDate('${dateStr}', event)"
               ${isDisabled ? 'style="pointer-events: none;"' : ''}
               ${isToday ? 'title="Same-day booking is not allowed"' : ''}>
            ${day}
          </div>
        `;
      }

      html += '</div></div>';
      
      // Update Apply button state after a short delay to ensure DOM is ready
      setTimeout(() => {
        const applyBtn = document.getElementById('date-picker-apply-btn');
        if (applyBtn) {
          const isValid = this.isValidRange();
          applyBtn.disabled = !isValid;
          if (isValid) {
            applyBtn.innerHTML = '<i class="bi bi-check-circle"></i> Apply Selection';
            applyBtn.style.opacity = '1';
            applyBtn.style.cursor = 'pointer';
          } else {
            applyBtn.innerHTML = 'Apply';
            applyBtn.style.opacity = '0.5';
            applyBtn.style.cursor = 'not-allowed';
          }
        }
      }, 10);
      
      return html;
    }

    isDateDisabled(date) {
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      const checkDate = new Date(date);
      checkDate.setHours(0, 0, 0, 0);
      
      // Disable past dates AND today (same-day booking not allowed)
      if (checkDate <= today) {
        return true;
      }

      // If start date is selected, disable dates that would result in less than minDays
      if (this.startDate && !this.endDate) {
        const startDate = new Date(this.startDate);
        startDate.setHours(0, 0, 0, 0);
        const daysDiff = Math.ceil((checkDate - startDate) / (1000 * 60 * 60 * 24));
        if (daysDiff > 0 && daysDiff < this.minDays) {
          return true;
        }
      }

      return false;
    }
    
    isToday(date) {
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      const checkDate = new Date(date);
      checkDate.setHours(0, 0, 0, 0);
      return checkDate.getTime() === today.getTime();
    }

    isDateInRange(date) {
      if (!this.startDate || !this.endDate) return false;
      
      const checkDate = new Date(date);
      checkDate.setHours(0, 0, 0, 0);
      const start = new Date(this.startDate);
      start.setHours(0, 0, 0, 0);
      const end = new Date(this.endDate);
      end.setHours(0, 0, 0, 0);

      return checkDate > start && checkDate < end;
    }

    selectDate(dateStr, event) {
      // Stop event propagation to prevent popup from closing
      if (event) {
        event.stopPropagation();
        event.preventDefault();
      }
      
      const date = new Date(dateStr);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      const checkDate = new Date(date);
      checkDate.setHours(0, 0, 0, 0);
      
      // Block same-day booking - today cannot be selected
      if (checkDate <= today) {
        this.showSameDayError();
        return;
      }
      
      // FIRST CLICK: Select pickup date (start date)
      if (!this.startDate) {
        this.startDate = date;
        this.endDate = null;
        this.hideSameDayError();
        this.render();
        this.updateInput();
        // Keep popup open - don't close it
        return;
      }
      
      // SECOND CLICK: Select return date (end date)
      if (this.startDate && !this.endDate) {
        const startDate = new Date(this.startDate);
        startDate.setHours(0, 0, 0, 0);
        const daysDiff = Math.ceil((checkDate - startDate) / (1000 * 60 * 60 * 24));
        
        // If clicked date is before start date, make it the new start date
        if (checkDate < startDate) {
          this.startDate = date;
          this.endDate = null;
          this.hideSameDayError();
          this.render();
          this.updateInput();
          return;
        }
        
        // If days difference is less than minimum, start new selection with this date
        if (daysDiff < this.minDays) {
          this.startDate = date;
          this.endDate = null;
          this.hideSameDayError();
          this.render();
          this.updateInput();
          return;
        }
        
        // Valid end date (at least minDays after start)
        this.endDate = date;
        this.hideSameDayError();
        this.render();
        this.updateInput();
        // Keep popup open so user can see the selection or click Apply
        return;
      }
      
      // If both dates are selected, start new selection (first click behavior)
      if (this.startDate && this.endDate) {
        this.startDate = date;
        this.endDate = null;
        this.hideSameDayError();
        this.render();
        this.updateInput();
      }
    }
    
    showSameDayError() {
      // Show error message
      const errorMsg = document.getElementById('same-day-error');
      if (errorMsg) {
        errorMsg.classList.remove('hidden');
        errorMsg.textContent = 'Same-day booking is not allowed. Please choose a date starting from tomorrow.';
      }
      // Also show in date error element if exists
      const dateError = document.getElementById('date-error');
      if (dateError) {
        dateError.classList.remove('hidden');
        dateError.textContent = 'Same-day booking is not allowed. Please choose a date starting from tomorrow.';
      }
    }
    
    hideSameDayError() {
      const errorMsg = document.getElementById('same-day-error');
      if (errorMsg) {
        errorMsg.classList.add('hidden');
      }
    }

    // Add drag selection support
    initDragSelection() {
      // Use event delegation on the popup
      if (this.dragHandlersAttached) return;
      this.dragHandlersAttached = true;

      let isDragging = false;
      let startDragDate = null;
      let dragStartPos = null;
      const picker = this;
      const DRAG_THRESHOLD = 5; // pixels to consider it a drag vs click

      // Use event delegation - attach to popup once
      this.popup.addEventListener('mousedown', function(e) {
        const dayEl = e.target.closest('.date-range-picker-day:not(.disabled)');
        if (!dayEl || !picker.isOpen) return;
        
        const dateStr = dayEl.getAttribute('data-date');
        if (!dateStr) return;

        // Check if date is today or in the past (same-day booking not allowed)
        const selectedDate = new Date(dateStr);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        selectedDate.setHours(0, 0, 0, 0);
        
        if (selectedDate <= today) {
          picker.showSameDayError();
          return;
        }

        // Store drag start position to distinguish clicks from drags
        dragStartPos = { x: e.clientX, y: e.clientY };
        startDragDate = new Date(dateStr);
        // Don't set dates yet - let click handler do it, or drag handler if it's a drag
      });

      // Global mouse move/up handlers (only active when dragging)
      const handleMouseMove = (e) => {
        if (!dragStartPos || !picker.isOpen) return;
        
        // Check if mouse moved enough to be considered a drag
        const moveDistance = Math.sqrt(
          Math.pow(e.clientX - dragStartPos.x, 2) + 
          Math.pow(e.clientY - dragStartPos.y, 2)
        );
        
        if (moveDistance > DRAG_THRESHOLD && !isDragging) {
          // Start dragging
          isDragging = true;
          picker.startDate = startDragDate;
          picker.endDate = null;
          picker.render();
          picker.hideSameDayError();
        }
        
        if (!isDragging) return;
        
        const dayEl = document.elementFromPoint(e.clientX, e.clientY)?.closest('.date-range-picker-day:not(.disabled)');
        if (!dayEl) return;
        
        const dateStr = dayEl.getAttribute('data-date');
        if (!dateStr) return;

        const endDragDate = new Date(dateStr);
        const daysDiff = Math.ceil((endDragDate - startDragDate) / (1000 * 60 * 60 * 24));
        
        if (daysDiff >= picker.minDays) {
          if (endDragDate >= startDragDate) {
            picker.startDate = startDragDate;
            picker.endDate = endDragDate;
          } else {
            picker.startDate = endDragDate;
            picker.endDate = startDragDate;
          }
          picker.render();
        }
      };

      const handleMouseUp = (e) => {
        if (isDragging) {
          // Was a drag - update input
          isDragging = false;
          picker.updateInput();
        } else if (dragStartPos) {
          // Was a click - let the onclick handler in renderCalendar handle it
          // The onclick will call selectDate() which handles the two-click logic
        }
        dragStartPos = null;
        startDragDate = null;
      };

      document.addEventListener('mousemove', handleMouseMove);
      document.addEventListener('mouseup', handleMouseUp);

      // Touch support for mobile
      let touchStartPos = null;
      this.popup.addEventListener('touchstart', function(e) {
        const dayEl = e.target.closest('.date-range-picker-day:not(.disabled)');
        if (!dayEl || !picker.isOpen) return;
        
        const dateStr = dayEl.getAttribute('data-date');
        if (!dateStr) return;

        // Check if date is today or in the past (same-day booking not allowed)
        const selectedDate = new Date(dateStr);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        selectedDate.setHours(0, 0, 0, 0);
        
        if (selectedDate <= today) {
          picker.showSameDayError();
          return;
        }

        const touch = e.touches[0];
        touchStartPos = { x: touch.clientX, y: touch.clientY };
        startDragDate = new Date(dateStr);
        // Don't set dates yet - let tap handler do it, or drag handler if it's a drag
      });

      const handleTouchMove = (e) => {
        if (!touchStartPos || !picker.isOpen) return;
        
        const touch = e.touches[0];
        
        // Check if touch moved enough to be considered a drag
        const moveDistance = Math.sqrt(
          Math.pow(touch.clientX - touchStartPos.x, 2) + 
          Math.pow(touch.clientY - touchStartPos.y, 2)
        );
        
        if (moveDistance > DRAG_THRESHOLD && !isDragging) {
          // Start dragging
          isDragging = true;
          picker.startDate = startDragDate;
          picker.endDate = null;
          picker.render();
          picker.hideSameDayError();
        }
        
        if (!isDragging) return;
        
        const dayEl = document.elementFromPoint(touch.clientX, touch.clientY)?.closest('.date-range-picker-day:not(.disabled)');
        if (!dayEl) return;
        
        const dateStr = dayEl.getAttribute('data-date');
        if (!dateStr) return;

        const endDragDate = new Date(dateStr);
        const daysDiff = Math.ceil((endDragDate - startDragDate) / (1000 * 60 * 60 * 24));
        
        if (daysDiff >= picker.minDays) {
          if (endDragDate >= startDragDate) {
            picker.startDate = startDragDate;
            picker.endDate = endDragDate;
          } else {
            picker.startDate = endDragDate;
            picker.endDate = startDragDate;
          }
          picker.render();
        }
        e.preventDefault();
      };

      const handleTouchEnd = () => {
        if (isDragging) {
          // Was a drag - update input
          isDragging = false;
          picker.updateInput();
        } else if (touchStartPos) {
          // Was a tap - let the onclick handler in renderCalendar handle it
          // The onclick will call selectDate() which handles the two-click logic
        }
        touchStartPos = null;
        startDragDate = null;
      };

      document.addEventListener('touchmove', handleTouchMove);
      document.addEventListener('touchend', handleTouchEnd);
    }

    isValidRange() {
      if (!this.startDate || !this.endDate) return false;
      const daysDiff = Math.ceil((this.endDate - this.startDate) / (1000 * 60 * 60 * 24));
      return daysDiff >= this.minDays;
    }

    formatDate(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }

    updateInput() {
      if (this.startDate && this.endDate) {
        const startStr = this.formatDate(this.startDate);
        const endStr = this.formatDate(this.endDate);
        this.input.value = `${startStr} → ${endStr}`;
        
        // Update hidden inputs for form submission
        if (this.pickupInput) {
          this.pickupInput.value = startStr;
          this.pickupInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (this.returnInput) {
          this.returnInput.value = endStr;
          this.returnInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        
        // Trigger calculation update
        if (typeof scheduleUpdate === 'function') {
          scheduleUpdate();
        }
      } else if (this.startDate) {
        this.input.value = this.formatDate(this.startDate);
        if (this.pickupInput) {
          this.pickupInput.value = this.formatDate(this.startDate);
          this.pickupInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (this.returnInput) this.returnInput.value = '';
      } else {
        this.input.value = '';
        if (this.pickupInput) this.pickupInput.value = '';
        if (this.returnInput) this.returnInput.value = '';
      }
    }

    prevMonth() {
      this.currentMonth.setMonth(this.currentMonth.getMonth() - 1);
      this.render();
    }

    nextMonth() {
      this.currentMonth.setMonth(this.currentMonth.getMonth() + 1);
      this.render();
    }

    handleApply() {
      if (this.isValidRange()) {
        const startStr = this.formatDate(this.startDate);
        const endStr = this.formatDate(this.endDate);
        this.input.value = `${startStr} → ${endStr}`;
        
        // Update hidden inputs
        if (this.pickupInput) this.pickupInput.value = startStr;
        if (this.returnInput) this.returnInput.value = endStr;
        
        // Trigger change event for existing price calculation
        if (this.pickupInput) {
          this.pickupInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (this.returnInput) {
          this.returnInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        
        // Force update calculation
        if (typeof scheduleUpdate === 'function') {
          scheduleUpdate();
        }
        
        if (this.onApply) {
          this.onApply(this.startDate, this.endDate);
        }
        
        this.close();
      }
    }

    handleCancel() {
      // Reset to previous values or clear
      this.startDate = null;
      this.endDate = null;
      this.updateInput();
      this.close();
      
      if (this.onCancel) {
        this.onCancel();
      }
    }

    getStartDate() {
      return this.startDate ? this.formatDate(this.startDate) : null;
    }

    getEndDate() {
      return this.endDate ? this.formatDate(this.endDate) : null;
    }

    setDates(startDateStr, endDateStr) {
      if (startDateStr) {
        this.startDate = new Date(startDateStr);
      }
      if (endDateStr) {
        this.endDate = new Date(endDateStr);
      }
      this.updateInput();
      this.render();
    }
  }

  const pickup = document.getElementById('pickup');
  const ret = document.getElementById('return');
  const totalEl = document.getElementById('total-price');
  const daysEl = document.getElementById('days-count');
  const insuranceInfo = document.getElementById('insurance-info');
  const extrasInfo = document.getElementById('extras-info');
  const error = document.getElementById('date-error');
  const btn = document.getElementById('submit-btn');
  const form = document.getElementById('booking-form');

  // Currency info
  const currencyInfo = <?= json_encode(getCurrency()) ?>;
  const currencyRate = currencyInfo.rate || 1.0;
  const currencySymbol = currencyInfo.symbol || 'MAD';
  
  // Convert MAD to selected currency
  function convertToCurrency(madAmount) {
    if (currencyRate <= 0) return madAmount;
    return madAmount / currencyRate;
  }
  
  // Format price with currency symbol
  function formatPriceJS(amount) {
    const converted = convertToCurrency(amount);
    const formatted = converted.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
    if (currencyInfo.code === 'USD' || currencyInfo.code === 'EUR') {
      return currencySymbol + formatted;
    }
    return formatted + ' ' + currencySymbol;
  }
  
  const pricePerDay = <?= json_encode($discountedPricePerDay) ?>;
  const pricePerWeek = <?= json_encode((float)$car['price_week']) ?>;
  const pricePerMonth = <?= json_encode((float)$car['price_month']) ?>;
  const originalPricePerDay = <?= json_encode($originalPricePerDay) ?>;
  const hasDiscount = <?= $hasDiscount ? 'true' : 'false' ?>;
  const discountPercent = <?= $discountPercent ?>;
  const minDays = <?= $minDays ?>;
  const insurancePrices = { 
    'basic': <?= json_encode($insurance_basic_price) ?>, 
    'smart': <?= json_encode($insurance_smart_price) ?>, 
    'premium': <?= json_encode($insurance_premium_price) ?> 
  };
  const insuranceDeposits = {
    'basic': <?= json_encode($insurance_basic_deposit) ?>,
    'smart': <?= json_encode($insurance_smart_deposit) ?>,
    'premium': <?= json_encode($insurance_premium_deposit) ?>
  };

  // Build extrasPrices from database data
  const extrasPrices = {};
  <?php foreach ($travelEssentials as $essential): ?>
    extrasPrices['essential_<?= $essential['id'] ?>'] = { 
      price: <?= $essential['price'] ?>, 
      perDay: <?= $essential['per_day'] ? 'true' : 'false' ?> 
    };
  <?php endforeach; ?>

  // Calculate car rental price based on duration (smart pricing)
  function calculateCarPrice(days) {
    if (days >= 30) {
      // Use monthly pricing
      const months = Math.floor(days / 30);
      const remainingDays = days % 30;
      return (months * pricePerMonth) + (remainingDays * pricePerDay);
    } else if (days >= 7) {
      // Use weekly pricing
      const weeks = Math.floor(days / 7);
      const remainingDays = days % 7;
      return (weeks * pricePerWeek) + (remainingDays * pricePerDay);
    } else {
      // Use daily pricing
      return days * pricePerDay;
    }
  }

  let updateFrame = null;
  const scheduleUpdate = () => {
    if (updateFrame) cancelAnimationFrame(updateFrame);
    updateFrame = requestAnimationFrame(() => {
      updateTotal();
      updateFrame = null;
    });
  };

  const sliderActiveBackground = 'linear-gradient(135deg, #FFB22C, #FFA500)';
  const setSliderColor = (checkbox) => {
    const slider = checkbox.closest('.toggle-switch')?.querySelector('.slider');
    if (!slider) { return; }
    slider.style.background = checkbox.checked ? sliderActiveBackground : '#333';
  };

  function updateTotal() {
    // Get dates from hidden inputs (updated by date range picker)
    const pickupDate = pickup?.value || '';
    const returnDate = ret?.value || '';
    
    if (!pickupDate || !returnDate) { 
      if (btn) btn.disabled = true; 
      if (totalEl) totalEl.textContent = formatPriceJS(0);
      if (daysEl) daysEl.textContent = '';
      return; 
    }
    
    // Calculate days difference (same-day booking not allowed)
    const pickupDateObj = new Date(pickupDate);
    const returnDateObj = new Date(returnDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    pickupDateObj.setHours(0, 0, 0, 0);
    
    // Validate same-day booking is not allowed
    if (pickupDateObj <= today) {
      if (error) error.classList.remove('hidden');
      if (error) error.textContent = 'Same-day booking is not allowed. Please choose a date starting from tomorrow.';
      if (btn) btn.disabled = true;
      if (totalEl) totalEl.textContent = formatPriceJS(0);
      if (daysEl) daysEl.textContent = '';
      return;
    }
    
    const days = Math.ceil((returnDateObj - pickupDateObj) / 86400000);
    
    if (days < minDays || days <= 0) {
      if (error) error.classList.remove('hidden');
      if (btn) btn.disabled = true;
      if (totalEl) totalEl.textContent = formatPriceJS(0);
      if (daysEl) daysEl.textContent = '';
      return;
    }
    if (error) error.classList.add('hidden');
    
    // Use days for calculation
    const rentalDays = days;

    const selectedInsurance = document.querySelector('input[name="insurance"]:checked').id;
    const insuranceCostPerDay = insurancePrices[selectedInsurance];

    let extrasTotal = 0;
    const selectedExtras = [];
    document.querySelectorAll('input[name="extras[]"]:checked').forEach(cb => {
      const id = cb.id;
      const item = extrasPrices[id];
      if (item) {
        // Fix: Calculate per day correctly for daily extras
        const cost = item.perDay ? (item.price * rentalDays) : item.price;
        extrasTotal += cost;
        selectedExtras.push(cb.value);
      }
    });

    // Use smart pricing (weekly/monthly when appropriate)
    const carTotal = calculateCarPrice(rentalDays);
    const insuranceTotal = rentalDays * insuranceCostPerDay;
    const grandTotal = carTotal + insuranceTotal + extrasTotal;

    // Add animation class for price update
    totalEl.classList.add('updating');
    setTimeout(() => {
      totalEl.textContent = formatPriceJS(grandTotal);
      totalEl.setAttribute('dir', 'ltr');
      totalEl.classList.remove('updating');
    }, 150);
    
    // Show pricing tier info (use rentalDays for display)
    let pricingInfo = rentalDays + ' <?= $text['day'] ?>' + (rentalDays > 1 ? 's' : '');
    if (rentalDays >= 30) {
      const months = Math.floor(rentalDays / 30);
      const remainingDays = rentalDays % 30;
      pricingInfo += ` (${months} month${months > 1 ? 's' : ''}`;
      if (remainingDays > 0) {
        pricingInfo += ` + ${remainingDays} day${remainingDays > 1 ? 's' : ''}`;
      }
      pricingInfo += ')';
    } else if (rentalDays >= 7) {
      const weeks = Math.floor(rentalDays / 7);
      const remainingDays = rentalDays % 7;
      pricingInfo += ` (${weeks} week${weeks > 1 ? 's' : ''}`;
      if (remainingDays > 0) {
        pricingInfo += ` + ${remainingDays} day${remainingDays > 1 ? 's' : ''}`;
      }
      pricingInfo += ')';
    }
    daysEl.textContent = pricingInfo;
    const basicPriceText = insurancePrices.basic > 0 
      ? formatPriceJS(insurancePrices.basic) + '/<?= $text['day'] ?>' 
      : "<?= $text['free'] ?>";
    const smartPriceText = formatPriceJS(insurancePrices.smart) + '/<?= $text['day'] ?>';
    const premiumPriceText = formatPriceJS(insurancePrices.premium) + '/<?= $text['day'] ?>';
    
    const labels = { 
      basic: "<?= $text['basic_insurance'] ?>: " + basicPriceText, 
      smart: "<?= $text['smart_insurance'] ?>: " + smartPriceText, 
      premium: "<?= $text['premium_insurance'] ?>: " + premiumPriceText
    };
    insuranceInfo.textContent = labels[selectedInsurance];

    if (selectedExtras.length > 0) {
      extrasInfo.textContent = selectedExtras.join(' • ');
      extrasInfo.style.display = 'block';
    } else {
      extrasInfo.textContent = '';
      extrasInfo.style.display = 'none';
    }

    btn.disabled = false;
  }

  // Sync toggle color on change & page load
  document.querySelectorAll('.toggle-switch input[type="checkbox"]').forEach(cb => {
    cb.addEventListener('change', () => {
      setSliderColor(cb);
      scheduleUpdate();
    });
    setSliderColor(cb);
  });

  // Allow clicking the slider element itself to toggle the checkbox
  document.querySelectorAll('.toggle-switch .slider').forEach(slider => {
    slider.addEventListener('click', () => {
      const checkbox = slider.previousElementSibling;
      if (!checkbox) { return; }
      checkbox.checked = !checkbox.checked;
      checkbox.dispatchEvent(new Event('change', { bubbles: true }));
    });
  });

  document.querySelectorAll('input[name="insurance"], input[name="extras[]"]').forEach(el => {
    el.addEventListener('change', scheduleUpdate);
  });

  // Date change listeners
  if (pickup) {
    pickup.addEventListener('change', () => {
      if (pickup.value) {
        // Same-day booking not allowed - minimum return date is pickup date + minDays
        const pickupDate = new Date(pickup.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        pickupDate.setHours(0, 0, 0, 0);
        
        // Validate pickup is not today or in the past
        if (pickupDate <= today) {
          if (error) {
            error.classList.remove('hidden');
            error.textContent = 'Same-day booking is not allowed. Please choose a date starting from tomorrow.';
          }
          if (btn) btn.disabled = true;
          return;
        }
        
        const minReturn = new Date(pickup.value);
        minReturn.setDate(minReturn.getDate() + minDays);
        if (ret) ret.min = minReturn.toISOString().split('T')[0];
        scheduleUpdate();
      }
    });
  }
  if (ret) {
    ret.addEventListener('change', scheduleUpdate);
  }

  // Time change listeners
  const pickupTime = document.getElementById('pickup_time');
  const returnTime = document.getElementById('return_time');
  if (pickupTime) {
    pickupTime.addEventListener('change', scheduleUpdate);
  }
  if (returnTime) {
    returnTime.addEventListener('change', scheduleUpdate);
  }

  document.getElementById('details-tab-link')?.addEventListener('click', function(e) {
    e.preventDefault();
    const tabBar = document.getElementById('tab-bar');
    tabBar.classList.remove('active-booking');
    tabBar.classList.add('active-details');
    tabBar.style.boxShadow = '0 8px 50px rgba(255, 215, 0, 0.5)';
    setTimeout(() => {
      const href = this.getAttribute('href');
      window.location.href = href;
    }, 500);
  });

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    // Validate same-day booking is not allowed
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const pickupDate = new Date(pickup.value);
    pickupDate.setHours(0, 0, 0, 0);
    
    if (pickupDate <= today) {
      alert('Same-day booking is not allowed. Please choose a date starting from tomorrow.');
      return;
    }

    // Calculate days (same-day booking not allowed)
    const daysDiff = Math.ceil((new Date(ret.value) - new Date(pickup.value)) / 86400000);
    const rentalDays = daysDiff;
    
    const selectedInsurance = document.querySelector('input[name="insurance"]:checked');
    const insuranceText = selectedInsurance.value;

    let extrasTotal = 0;
    const extrasList = [];
    document.querySelectorAll('input[name="extras[]"]:checked').forEach(cb => {
      const id = cb.id;
      const item = extrasPrices[id];
      if (item) {
        const cost = item.perDay ? item.price * rentalDays : item.price;
        extrasTotal += cost;
        extrasList.push(cb.value);
      }
    });

    // Use smart pricing (weekly/monthly when appropriate)
    const carTotal = calculateCarPrice(rentalDays);
    const insuranceCostPerDay = insurancePrices[selectedInsurance.id];
    const insuranceTotal = insuranceCostPerDay * rentalDays;
    const grandTotal = carTotal + insuranceTotal + extrasTotal;

    const discountText = hasDiscount ? ` (-${discountPercent}% discount applied)` : '';
    const extrasText = extrasList.length > 0 ? "\nExtras:\n" + extrasList.map(e => "• " + e).join("\n") : "";
    
    const insuranceDetail = insuranceCostPerDay > 0 
      ? `Insurance: ${insuranceText} (MAD${insuranceTotal.toLocaleString()})\n`
      : `Insurance: ${insuranceText}\n`;

    // Get pricing breakdown (use rentalDays)
    let pricingBreakdown = '';
    if (rentalDays >= 30) {
      const months = Math.floor(rentalDays / 30);
      const remainingDays = rentalDays % 30;
      pricingBreakdown = `${months} month(s) × MAD${pricePerMonth.toLocaleString()} = MAD${(months * pricePerMonth).toLocaleString()}`;
      if (remainingDays > 0) {
        pricingBreakdown += `\n${remainingDays} day(s) × MAD${pricePerDay.toLocaleString()} = MAD${(remainingDays * pricePerDay).toLocaleString()}`;
      }
    } else if (rentalDays >= 7) {
      const weeks = Math.floor(rentalDays / 7);
      const remainingDays = rentalDays % 7;
      pricingBreakdown = `${weeks} week(s) × MAD${pricePerWeek.toLocaleString()} = MAD${(weeks * pricePerWeek).toLocaleString()}`;
      if (remainingDays > 0) {
        pricingBreakdown += `\n${remainingDays} day(s) × MAD${pricePerDay.toLocaleString()} = MAD${(remainingDays * pricePerDay).toLocaleString()}`;
      }
    } else {
      pricingBreakdown = `${rentalDays} day(s) × MAD${pricePerDay.toLocaleString()}${discountText} = MAD${carTotal.toLocaleString()}`;
    }

    const pickupLocation = form.pickup_location.value || 'Not specified';
    const pickupTime = form.pickup_time?.value || '10:00';
    const returnTime = form.return_time?.value || '10:00';
    
    const msg = `NEW BOOKING - ET TAAJ RENT CARS\n\n` +
                `Car: <?= htmlspecialchars($car['name']) ?>\n` +
                `Pickup Date: ${pickup.value} at ${pickupTime}\n` +
                `Return Date: ${ret.value} at ${returnTime}\n` +
                `Pickup Location: ${pickupLocation}\n` +
                `Duration: ${rentalDays} day${rentalDays > 1 ? 's' : ''}\n\n` +
                `Car Rental Pricing:\n${pricingBreakdown}\n` +
                `Car Total: MAD${carTotal.toLocaleString()}\n` +
                `${insuranceDetail}` +
                `${extrasText ? extrasText + "\n" : ""}` +
                `GRAND TOTAL: MAD${grandTotal.toLocaleString()}\n\n` +
                `Name: ${form.name.value}\n` +
                `Email: ${form.email.value}\n` +
                `Phone: ${form.phone.value}\n\n` +
                `Please confirm availability & send payment link!`;

    window.open(`https://wa.me/212772331080?text=${encodeURIComponent(msg)}`, '_blank');

    form.reset();
    document.querySelectorAll('.toggle-switch input[type="checkbox"]').forEach(cb => setSliderColor(cb));
    totalEl.textContent = formatPriceJS(0);
    daysEl.textContent = '';
    insuranceInfo.textContent = '<?= $text['basic_insurance_included'] ?>';
    extrasInfo.textContent = '';
    btn.disabled = true;
  });

  // Initialize Date Range Picker
  let dateRangePicker;
  document.addEventListener('DOMContentLoaded', () => {
    // Initialize date range picker
    dateRangePicker = new DateRangePicker('date-range-input', {
      minDays: minDays,
      onApply: (start, end) => {
        // Trigger price update
        scheduleUpdate();
      },
      onCancel: () => {
        // Reset price calculation
        scheduleUpdate();
      }
    });
    
    // Set minimum date for hidden inputs (tomorrow - same-day booking not allowed)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    if (pickup) pickup.min = tomorrow.toISOString().split('T')[0];
    scheduleUpdate();
  });
</script>
</body>
</html>

