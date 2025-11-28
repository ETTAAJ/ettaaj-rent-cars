-- Update Travel Essentials with Arabic and French translations

-- 1. Premium Fuel Service
UPDATE `travel_essentials` SET 
  `name_ar` = 'خدمة الوقود المميزة',
  `name_fr` = 'Service de carburant premium',
  `description_ar` = 'خزان كامل مسبق الدفع',
  `description_fr` = 'Plein de carburant prépayé'
WHERE `name` = 'Premium Fuel Service' OR `name_en` = 'Premium Fuel Service';

-- 2. Unlimited Kilometers
UPDATE `travel_essentials` SET 
  `name_ar` = 'كيلومترات غير محدودة',
  `name_fr` = 'Kilomètres illimités',
  `description_ar` = 'القيادة دون قيود على المسافة',
  `description_fr` = 'Conduire sans restrictions de kilométrage'
WHERE `name` = 'Unlimited Kilometers' OR `name_en` = 'Unlimited Kilometers';

-- 3. Flexible Cancellation
UPDATE `travel_essentials` SET 
  `name_ar` = 'إلغاء مرن',
  `name_fr` = 'Annulation flexible',
  `description_ar` = 'إلغاء مجاني حتى وقت المغادرة المقرر',
  `description_fr` = 'Annulation gratuite jusqu\'au départ prévu'
WHERE `name` = 'Flexible Cancellation' OR `name_en` = 'Flexible Cancellation';

-- 4. Additional Drivers
UPDATE `travel_essentials` SET 
  `name_ar` = 'سائقون إضافيون',
  `name_fr` = 'Conducteurs supplémentaires',
  `description_ar` = 'أضف حتى سائقين إضافيين',
  `description_fr` = 'Ajouter jusqu\'à 2 conducteurs supplémentaires'
WHERE `name` = 'Additional Drivers' OR `name_en` = 'Additional Drivers';

