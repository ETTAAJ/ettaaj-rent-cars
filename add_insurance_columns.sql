-- Migration script to add insurance plan columns to cars table
-- Run this script in phpMyAdmin or via MySQL command line
-- Note: If columns already exist, you may get an error. You can ignore it or check first.

ALTER TABLE `cars`
ADD COLUMN `insurance_basic_price` DECIMAL(10,2) DEFAULT NULL AFTER `discount`,
ADD COLUMN `insurance_smart_price` DECIMAL(10,2) DEFAULT NULL AFTER `insurance_basic_price`,
ADD COLUMN `insurance_premium_price` DECIMAL(10,2) DEFAULT NULL AFTER `insurance_smart_price`,
ADD COLUMN `insurance_basic_deposit` DECIMAL(10,2) DEFAULT NULL AFTER `insurance_premium_price`,
ADD COLUMN `insurance_smart_deposit` DECIMAL(10,2) DEFAULT NULL AFTER `insurance_basic_deposit`,
ADD COLUMN `insurance_premium_deposit` DECIMAL(10,2) DEFAULT NULL AFTER `insurance_smart_deposit`;

