SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `order_item_add_on`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `add_ons`;
DROP TABLE IF EXISTS `sizes`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `address` text,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `base_price` decimal(10,2) NOT NULL,
  `category` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sizes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `price_adjustment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `add_ons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `delivery_address` text,
  `status` enum('pending','confirmed','preparing','ready','completed','cancelled') NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `notes` text,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `pickup_method` varchar(255) NOT NULL DEFAULT 'in_store',
  `pickup_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `size_id` bigint unsigned NOT NULL,
  `sugar_level` varchar(10) NOT NULL DEFAULT '50%',
  `ice_level` varchar(20) NOT NULL DEFAULT 'normal_ice',
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `special_instructions` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_size_id_foreign` (`size_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `order_items_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_item_add_on` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint unsigned NOT NULL,
  `add_on_id` bigint unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_item_add_on_order_item_id_foreign` (`order_item_id`),
  KEY `order_item_add_on_add_on_id_foreign` (`add_on_id`),
  CONSTRAINT `order_item_add_on_add_on_id_foreign` FOREIGN KEY (`add_on_id`) REFERENCES `add_ons` (`id`),
  CONSTRAINT `order_item_add_on_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@milktea.test', '$2y$10$iEcl.ApdkXz7XS.o1OBfj.MK3g6eQ.xU0gYa5dgZ41QWCO0Iqx5eC', 'admin', '09123456789', '123 Admin Street, Manila, Philippines', NOW(), NOW()),
(2, 'John Doe', 'customer@example.com', '$2y$10$1Tqxsh3rL81oqWOwAs5PJenHS7KKHoXz2th/GzIOCw05KSEQX0M2.', 'customer', '09987654321', '456 Customer Ave, Quezon City, Philippines', NOW(), NOW());

INSERT INTO `sizes` (`id`, `name`, `display_name`, `price_adjustment`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'small', 'Small (12oz)', 0.00, 1, 1, NOW(), NOW()),
(2, 'medium', 'Medium (16oz)', 10.00, 2, 1, NOW(), NOW()),
(3, 'large', 'Large (22oz)', 20.00, 3, 1, NOW(), NOW());

INSERT INTO `add_ons` (`id`, `name`, `description`, `price`, `category`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Pearl', 'Chewy tapioca pearls', 10.00, 'topping', 1, 1, NOW(), NOW()),
(2, 'Nata', 'Sweet coconut jelly', 10.00, 'topping', 2, 1, NOW(), NOW()),
(3, 'Pudding', 'Creamy egg pudding', 15.00, 'topping', 3, 1, NOW(), NOW()),
(4, 'Grass Jelly', 'Refreshing herbal jelly', 10.00, 'topping', 4, 1, NOW(), NOW()),
(5, 'Aloe Vera', 'Healthy aloe vera pieces', 15.00, 'topping', 5, 1, NOW(), NOW()),
(6, 'Red Bean', 'Sweet red beans', 15.00, 'topping', 6, 1, NOW(), NOW());

INSERT INTO `products` (`id`, `name`, `description`, `base_price`, `category`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Classic Milk Tea', 'Our signature black milk tea with a rich, creamy flavor.', 80.00, 'milk_tea', 1, 1, NOW(), NOW()),
(2, 'Wintermelon Milk Tea', 'Refreshing wintermelon flavor blended with creamy milk.', 85.00, 'milk_tea', 2, 1, NOW(), NOW()),
(3, 'Okinawa Milk Tea', 'Brown sugar flavored milk tea with a caramel taste.', 90.00, 'milk_tea', 3, 1, NOW(), NOW()),
(4, 'Thai Milk Tea', 'Authentic Thai tea with a unique orange color and flavor.', 95.00, 'milk_tea', 4, 1, NOW(), NOW()),
(5, 'Taro Milk Tea', 'Creamy taro flavor with a beautiful purple color.', 90.00, 'milk_tea', 5, 1, NOW(), NOW()),
(6, 'Matcha Milk Tea', 'Premium Japanese green tea with milk.', 100.00, 'milk_tea', 6, 1, NOW(), NOW()),
(7, 'Strawberry Fruit Tea', 'Fresh strawberry flavor with green tea base.', 85.00, 'fruit_tea', 7, 1, NOW(), NOW()),
(8, 'Mango Fruit Tea', 'Tropical mango flavor with green tea base.', 85.00, 'fruit_tea', 8, 1, NOW(), NOW()),
(9, 'Passion Fruit Tea', 'Refreshing passion fruit with green tea.', 80.00, 'fruit_tea', 9, 1, NOW(), NOW()),
(10, 'Iced Americano', 'Strong espresso with cold water and ice.', 75.00, 'coffee', 10, 1, NOW(), NOW()),
(11, 'Caramel Macchiato', 'Espresso with vanilla syrup, steamed milk, and caramel drizzle.', 110.00, 'coffee', 11, 1, NOW(), NOW()),
(12, 'Hazelnut Latte', 'Smooth latte with hazelnut flavor.', 105.00, 'coffee', 12, 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
