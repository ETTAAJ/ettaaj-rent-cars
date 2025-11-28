# Travel Essentials Translations

## Arabic and French Translations for Default Travel Essentials

### 1. Premium Fuel Service
**English:**
- Name: Premium Fuel Service
- Description: Prepaid full tank

**Arabic (العربية):**
- Name: خدمة الوقود المميزة
- Description: خزان كامل مسبق الدفع

**French (Français):**
- Name: Service de carburant premium
- Description: Plein de carburant prépayé

---

### 2. Unlimited Kilometers
**English:**
- Name: Unlimited Kilometers
- Description: Drive without mileage restrictions

**Arabic (العربية):**
- Name: كيلومترات غير محدودة
- Description: القيادة دون قيود على المسافة

**French (Français):**
- Name: Kilomètres illimités
- Description: Conduire sans restrictions de kilométrage

---

### 3. Flexible Cancellation
**English:**
- Name: Flexible Cancellation
- Description: Free cancellation until scheduled departure

**Arabic (العربية):**
- Name: إلغاء مرن
- Description: إلغاء مجاني حتى وقت المغادرة المقرر

**French (Français):**
- Name: Annulation flexible
- Description: Annulation gratuite jusqu'au départ prévu

---

### 4. Additional Drivers
**English:**
- Name: Additional Drivers
- Description: Add up to 2 additional drivers

**Arabic (العربية):**
- Name: سائقون إضافيون
- Description: أضف حتى سائقين إضافيين

**French (Français):**
- Name: Conducteurs supplémentaires
- Description: Ajouter jusqu'à 2 conducteurs supplémentaires

---

## How to Apply Translations

1. Run the SQL file: `Admin/update_travel_essentials_translations.sql` in phpMyAdmin
2. Or manually update each travel essential in the admin panel:
   - Go to Travel Essentials Management
   - Click "Edit" on each essential
   - Use the language tabs to add Arabic and French translations

## Notes

- Arabic text is written in Arabic script (RTL direction)
- French text includes proper accents
- All translations match the existing language files in `languages/ar.php` and `languages/fr.php`

