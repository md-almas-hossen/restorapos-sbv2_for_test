CREATE TABLE `shifts` (
  `id` int(10)  NOT NULL AUTO_INCREMENT,
  `create_by` int(10)  NOT NULL,
  `start_Time` time NOT NULL,
  `end_Time` time NOT NULL,
  `shift_title` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_date` date NOT NULL,
  `start_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `shift_user` (
  `id` int(10)  NOT NULL AUTO_INCREMENT,
  `shift_id` int(10)  NOT NULL,
  `emp_id` char(100)  NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;