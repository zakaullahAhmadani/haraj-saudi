-- =============================================
-- Database: marketplace
-- Complete SQL for deployment
-- =============================================

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `marketplace`;
USE `marketplace`;

-- =============================================
-- Set foreign key checks off
-- =============================================
SET FOREIGN_KEY_CHECKS=0;



-- =============================================
-- Table: users
-- =============================================
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: categories
-- =============================================
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `arabic_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: ads
-- =============================================
CREATE TABLE `ads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `views` int NOT NULL DEFAULT '0',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `pinned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ads_slug_unique` (`slug`),
  KEY `ads_category_id_foreign` (`category_id`),
  KEY `ads_user_id_foreign` (`user_id`),
  KEY `ads_is_active_status_index` (`is_active`,`status`),
  CONSTRAINT `ads_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: ad_images
-- =============================================
CREATE TABLE `ad_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ad_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ad_images_ad_id_foreign` (`ad_id`),
  CONSTRAINT `ad_images_ad_id_foreign` FOREIGN KEY (`ad_id`) REFERENCES `ads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: migrations
-- =============================================
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: sessions
-- =============================================
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: cache
-- =============================================
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: jobs
-- =============================================
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Insert Admin User
-- =============================================
INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_admin`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@souq.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NOW(), NOW());

-- Note: Password is 'admin123' (hashed)

-- =============================================
-- Insert Categories
-- =============================================
INSERT INTO `categories` (`id`, `name`, `arabic_name`, `slug`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Home Appliances', 'الأجهزة المنزلية', 'home-appliances', 1, 1, NOW(), NOW()),
(2, 'Car buying', 'شراء السيارات', 'car-buying', 2, 1, NOW(), NOW()),
(3, 'Rent Car/ Buses', 'تأجير سيارات / حافلات', 'rent-car-buses', 3, 1, NOW(), NOW()),
(4, 'Scrap', 'خردة', 'scrap', 4, 1, NOW(), NOW()),
(5, 'Towing Service', 'خدمة السحب', 'towing-service', 5, 1, NOW(), NOW()),
(6, 'Perfume', 'عطور', 'perfume', 6, 1, NOW(), NOW()),
(7, 'Gypsum Boarding', 'ألواح جبسية', 'gypsum-boarding', 7, 1, NOW(), NOW()),
(8, 'Curtain installation', 'تركيب ستائر', 'curtain-installation', 8, 1, NOW(), NOW()),
(9, 'Glass Installation', 'تركيب زجاج', 'glass-installation', 9, 1, NOW(), NOW()),
(10, 'Plumbing and Electrician', 'سباكة وكهرباء', 'plumbing-and-electrician', 10, 1, NOW(), NOW()),
(11, 'Automobile Tint Repair', 'تلميع وتظليل السيارات', 'automobile-tint-repair', 11, 1, NOW(), NOW()),
(12, 'Home Renovation', 'تجديد المنازل', 'home-renovation', 12, 1, NOW(), NOW()),
(13, 'Laptop or Mobile Shops', 'محلات لابتوب وجوال', 'laptop-or-mobile-shops', 13, 1, NOW(), NOW()),
(14, 'Furniture Buyers', 'مشترو الأثاث', 'furniture-buyers', 14, 1, NOW(), NOW()),
(15, 'Movers and Packers', 'نقل وعفش', 'movers-and-packers', 15, 1, NOW(), NOW()),
(16, 'Tire Puncture Repair', 'إصلاح ثقوب الإطارات', 'tire-puncture-repair', 16, 1, NOW(), NOW());

-- Insert subcategories for Home Appliances
INSERT INTO `categories` (`id`, `name`, `arabic_name`, `slug`, `parent_id`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(17, 'automatic washer repair', 'إصلاح الغسالات الأوتوماتيكية', 'automatic-washer-repair', 1, 1, 1, NOW(), NOW()),
(18, 'fridge repair', 'إصلاح الثلاجات', 'fridge-repair', 1, 2, 1, NOW(), NOW()),
(19, 'dishwasher repair', 'إصلاح غسالات الصحون', 'dishwasher-repair', 1, 3, 1, NOW(), NOW()),
(20, 'ac repair', 'إصلاح المكيفات', 'ac-repair', 1, 4, 1, NOW(), NOW()),
(21, 'dryer repair', 'إصلاح المجففات', 'dryer-repair', 1, 5, 1, NOW(), NOW());

-- =============================================
-- Insert Sample Ads
-- =============================================
INSERT INTO `ads` (`title`, `slug`, `description`, `price`, `location`, `phone`, `category_id`, `user_id`, `is_active`, `status`, `views`, `is_pinned`, `pinned_at`, `created_at`, `updated_at`) VALUES
('صيانة الغسالات - فني متخصص', 'washer-repair-1', 'خدمة صيانة جميع أنواع الغسالات الأوتوماتيكية. فنيين متخصصين. ضمان 6 أشهر.', 150.00, 'الرياض', '0500000001', 17, 1, 1, 'approved', 150, 1, NOW(), NOW(), NOW()),
('صيانة مكيفات - تبريد وتكييف', 'ac-repair-1', 'صيانة وتركيب جميع أنواع المكيفات. فنيين خبرة. ضمان سنة.', 200.00, 'جدة', '0500000002', 20, 1, 1, 'approved', 85, 1, NOW(), NOW(), NOW()),
('نقل عفش بالرياض', 'movers-1', 'نقل عفش فك وتركيب بضمان. عمالة مدربة وسيارات مجهزة.', 500.00, 'الرياض', '0500000003', 15, 1, 1, 'approved', 210, 1, NOW(), NOW(), NOW()),
('تركيب ستائر', 'curtain-1', 'تركيب ستائر رول - ستائر خشبية - ستائر قماش. خصم 20% للطلب الأول.', 300.00, 'الدمام', '0500000004', 8, 1, 1, 'approved', 43, 0, NULL, NOW(), NOW()),
('شراء سيارات مستعملة', 'car-buying-1', 'نشتري جميع أنواع السيارات المستعملة. نقداً فوراً. معاينة مجانية.', NULL, 'الرياض', '0500000005', 2, 1, 1, 'approved', 320, 0, NULL, NOW(), NOW()),
('سباكة وكهرباء', 'plumbing-1', 'جميع أعمال السباكة والكهرباء. تركيب وصيانة. فنيين معتمدين.', 250.00, 'جدة', '0500000006', 10, 1, 1, 'approved', 67, 0, NULL, NOW(), NOW());

-- =============================================
-- Insert migrations record
-- =============================================
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_05_18_111739_create_categories_table', 1),
(4, '2026_05_18_111739_create_users_table', 1),
(5, '2026_05_18_111740_create_ads_table', 1),
(6, '2026_05_18_111742_create_ad_images_table', 1),
(7, '2026_05_18_112152_create_sessions_table', 1),
(8, '2026_05_19_000001_add_parent_id_to_categories_table', 1),
(9, '2026_05_20_033143_add_is_admin_to_users_table', 1),
(10, '2026_05_20_033601_add_is_active_to_users_table', 1),
(11, '2026_05_20_061603_add_featured_columns_to_ads_table', 1);

-- =============================================
-- Set foreign key checks back on
-- =============================================
SET FOREIGN_KEY_CHECKS=1;

-- =============================================
-- Verification queries
-- =============================================
SELECT '✅ Database setup complete!' AS Status;
SELECT 'Categories: ' AS Type, COUNT(*) AS Count FROM categories
UNION ALL
SELECT 'Ads: ', COUNT(*) FROM ads
UNION ALL
SELECT 'Users: ', COUNT(*) FROM users;