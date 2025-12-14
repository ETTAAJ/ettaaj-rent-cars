# ETTAAJ RENT CARS
**Car Rental System**  
*A modern, responsive Car Rental Website with timezone-safe booking calculations, multi-language support, and a secure admin panel. Built with PHP, MySQL, Bootstrap 5, Tailwind CSS, and AOS animations.*

---

## Features

### Public Frontend  
`index.php` • `car-detail.php` • `booking.php`

- **Search & filter** cars (name, gear, fuel, price)  
- **Responsive card grid** (mobile-first)  
- **Live AJAX filtering**  
- **Car detail page** with similar cars  
- **Timezone-safe booking form** with **live price calculation**  
- **Minimum 3-day rental** enforcement  
- **Free day included** (display only, not in pricing)  
- **Multi-language support** (EN/FR/AR)  
- **Currency conversion** (MAD/USD/EUR)  
- **Gold accent color**: `#FFB22C`

---

## Booking Calculation System

### Timezone-Safe Date Calculation

The booking system uses a **timezone-safe calculation** that ensures identical results on all devices (PC, mobile, tablets) regardless of the user's timezone.

#### Key Features:

1. **Date-Only Calculation (YYYY-MM-DD)**
   - Never relies on device local time
   - Uses DATE ONLY format, not time
   - Normalizes both startDate and endDate to midnight (00:00:00) in local timezone

2. **Paid Days Formula**
   ```
   paidDays = (endDate - startDate in days) + 1
   ```
   - Example: Jan 1 to Jan 7 = 7 paid days
   - Both dates are normalized to 00:00:00 before calculation

3. **Free Day Display**
   - Always adds 1 free day for display purposes
   - Displayed duration = paidDays + 1 free day
   - **Free day does NOT affect pricing**

4. **Pricing Rules**
   - Car price = `paidDays × daily car price`
   - Travel Essentials = `paidDays × daily essentials price`
   - Insurance = `paidDays × daily insurance price`
   - Example: 7 paid days × 285 MAD/day = **1995 MAD**

5. **UI Display**
   - Shows: "X days (Y paid days + 1 Free Day (No Charge))"
   - Free day clearly labeled in green as "Free (No Charge)"

---

### Admin Panel  
`Admin/`

- **Add, edit, delete** cars  
- **Travel Essentials management**  
- **Insurance plans configuration**  
- **Discount management**  
- **Secure image upload** (JPG, PNG, GIF, WebP)  
- **Unique filenames** to prevent conflicts  
- **Full CRUD operations**  

---

## Folder Structure

```
ettaaj-rent-cars/
├── uploads/                  # Car images (uploaded here)
├── Admin/                    # Admin panel
│   ├── index.php            # List all cars
│   ├── create.php           # Add new car
│   ├── edit.php             # Edit car
│   ├── delete.php           # Delete car
│   ├── travel-essentials.php # Manage travel essentials
│   └── config.php           # DB connection
├── languages/                # Multi-language files
│   ├── en.php               # English translations
│   ├── fr.php               # French translations
│   └── ar.php               # Arabic translations
├── pub_img/                 # Public images
├── vidio/                   # Video files
├── index.php                # Homepage with search
├── car-detail.php           # Single car view
├── booking.php              # Booking form (timezone-safe)
├── booking-process.php      # Process booking validation
├── header.php               # Header
├── footer.php               # Footer
├── config.php               # Main DB config
├── init.php                 # Initialization & language setup
└── ettaajrentcars.sql       # Database schema
```

---

## Database Setup

### 1. Create Database & Import Schema

```sql
-- Import ettaajrentcars.sql or create manually:

CREATE DATABASE ettaaj_rent_cars;
USE ettaaj_rent_cars;

CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    seats INT NOT NULL,
    bags INT NOT NULL,
    gear ENUM('Manual', 'Automatic') NOT NULL,
    fuel ENUM('Diesel', 'Petrol') NOT NULL,
    price_day DECIMAL(10,2) NOT NULL,
    price_week DECIMAL(10,2) NOT NULL,
    price_month DECIMAL(10,2) NOT NULL,
    discount INT DEFAULT 0,
    insurance_basic_price DECIMAL(10,2) DEFAULT 0,
    insurance_smart_price DECIMAL(10,2) DEFAULT 0,
    insurance_premium_price DECIMAL(10,2) DEFAULT 0,
    insurance_basic_deposit DECIMAL(10,2) DEFAULT 0,
    insurance_smart_deposit DECIMAL(10,2) DEFAULT 0,
    insurance_premium_deposit DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE travel_essentials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(255) NOT NULL,
    name_fr VARCHAR(255),
    name_ar VARCHAR(255),
    description_en TEXT,
    description_fr TEXT,
    description_ar TEXT,
    price DECIMAL(10,2) NOT NULL,
    per_day BOOLEAN DEFAULT 1,
    icon VARCHAR(100),
    is_active BOOLEAN DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 2. Configuration

**config.php** (root & admin)
```php
<?php
$host = 'localhost';
$db   = 'ettaaj_rent_cars';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```

---

## Setup Instructions

1. **Copy to XAMPP**
   ```
   Copy to: C:\xampp\htdocs\ettaaj-rent-cars\
   ```

2. **Create uploads folder**
   ```bash
   mkdir uploads
   ```

3. **Set permissions** (Windows)
   ```bash
   icacls "C:\xampp\htdocs\ettaaj-rent-cars\uploads" /grant Users:M
   ```

4. **Start XAMPP**
   - Start Apache + MySQL

5. **Import database**
   - Import `ettaajrentcars.sql` via phpMyAdmin
   - Or run SQL commands above

6. **Access the site**
   ```
   http://localhost/ettaaj-rent-cars/
   ```

---

## Admin Panel

**URL**: `http://localhost/ettaaj-rent-cars/Admin/`

- Click "Add New Car" to add vehicles
- Upload images → saved to `../uploads/`
- Manage travel essentials
- Configure insurance plans
- Edit/Delete with confirmation

---

## Booking System Technical Details

### Date Calculation Function

```javascript
function calculatePaidDays(startDate, endDate) {
    // Parse dates as YYYY-MM-DD strings
    let startStr = typeof startDate === 'string' ? startDate : 
                   startDate.getFullYear() + '-' + 
                   String(startDate.getMonth() + 1).padStart(2, '0') + '-' + 
                   String(startDate.getDate()).padStart(2, '0');
    let endStr = typeof endDate === 'string' ? endDate : 
                 endDate.getFullYear() + '-' + 
                 String(endDate.getMonth() + 1).padStart(2, '0') + '-' + 
                 String(endDate.getDate()).padStart(2, '0');
    
    // Create dates in LOCAL timezone normalized to 00:00:00
    const startParts = startStr.split('-');
    const endParts = endStr.split('-');
    
    const start = new Date(
      parseInt(startParts[0], 10),  // year
      parseInt(startParts[1], 10) - 1,  // month (0-indexed)
      parseInt(startParts[2], 10),  // day
      0, 0, 0, 0  // 00:00:00.000
    );
    
    const end = new Date(
      parseInt(endParts[0], 10),
      parseInt(endParts[1], 10) - 1,
      parseInt(endParts[2], 10),
      0, 0, 0, 0
    );
    
    // Calculate: (endDate - startDate) + 1
    const diffTime = end.getTime() - start.getTime();
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    return diffDays + 1;
}
```

### Pricing Calculation

```javascript
// Always uses daily pricing: paidDays × pricePerDay
function calculateCarPrice(paidDays) {
    return paidDays * pricePerDay;
}

// Example: 7 paid days × 285 MAD/day = 1995 MAD
```

### Validation Rules

- **Same-day booking**: Not allowed (pickup must be tomorrow or later)
- **Minimum rental**: 3 days
- **Date format**: YYYY-MM-DD (DATE ONLY, no time)
- **Timezone**: Local timezone normalized to 00:00:00

---

## Styling & UX

- **Gold color**: `#FFB22C` (primary accent)
- **AOS animations** on scroll
- **Tailwind + Bootstrap** hybrid
- **Mobile responsive** (1–4 columns)
- **RTL support** for Arabic
- **Dark/Light mode** support

---

## Security

- **PDO prepared statements** (SQL injection prevention)
- **Input sanitization** (XSS prevention)
- **File type & size validation**
- **Unique filenames** (prevents overwrites)
- **basename()** to prevent path traversal
- **Date validation** (timezone-safe, prevents manipulation)

---

## Multi-Language Support

The system supports three languages:
- **English (en)** - Default
- **French (fr)**
- **Arabic (ar)** - RTL support

Language files are in `languages/` directory. Switch language via URL parameter: `?lang=en`, `?lang=fr`, `?lang=ar`

---

## Currency Support

- **MAD** (Moroccan Dirham) - Default
- **USD** (US Dollar)
- **EUR** (Euro)

Currency conversion rates are configurable in the system.

---

## Future Improvements

- [ ] User login & booking history
- [ ] Email confirmation
- [ ] Payment gateway integration
- [ ] Admin dashboard statistics
- [ ] Booking calendar view
- [ ] SMS notifications
- [ ] Advanced search filters
- [ ] Car availability management

---

## License

This project is proprietary software for ETTAAJ RENT CARS.

---

## Support

For issues or questions, please contact the development team.
