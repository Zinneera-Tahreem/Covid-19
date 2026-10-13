-- ====================================================================
-- COVID-19 Information & Management Portal - Database Schema
-- Software Testing & Quality Assurance (STQA) Mini Project
-- Database: covid19_db
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `covid19_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `covid19_db`;

-- --------------------------------------------------------
-- Table: users (Stores user account credentials & profiles)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL UNIQUE,
  `phone` VARCHAR(15) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('user', 'admin') DEFAULT 'user',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: registrations (Stores vaccine & RT-PCR registrations)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `registration_ref` VARCHAR(20) NOT NULL UNIQUE,
  `user_id` INT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `gov_id` VARCHAR(20) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(15) NOT NULL,
  `dob` DATE NOT NULL,
  `dose_type` VARCHAR(50) NOT NULL,
  `preferred_date` DATE NOT NULL,
  `center_name` VARCHAR(150) NOT NULL,
  `pincode` VARCHAR(10) NOT NULL,
  `medical_conditions` TEXT DEFAULT NULL,
  `status` ENUM('Confirmed', 'Pending', 'Completed', 'Cancelled') DEFAULT 'Confirmed',
  `registered_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: comments (Stores community feedback & discussion posts)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `comments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `author_name` VARCHAR(100) NOT NULL,
  `author_email` VARCHAR(120) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `rating` INT DEFAULT 5,
  `comment_text` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Sample Starter Data for Instant Testing
-- --------------------------------------------------------

-- Sample Users (Password for all demo accounts: 'Admin@123' or 'Covid@2024')
INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Admin Health Officer', 'admin@covidcare.org', '9876543210', '$2y$10$vK3zO0e3Q6G4Zt7I7Yf7neF7aE7gE7wO0cR3G8uL5jM9qN2pY1mWu', 'admin', NOW()),
(2, 'Aarav Sharma', 'aarav.sharma@example.com', '9823456781', '$2y$10$vK3zO0e3Q6G4Zt7I7Yf7neF7aE7gE7wO0cR3G8uL5jM9qN2pY1mWu', 'user', NOW()),
(3, 'Priya Nair', 'priya.nair@example.com', '8765432109', '$2y$10$vK3zO0e3Q6G4Zt7I7Yf7neF7aE7gE7wO0cR3G8uL5jM9qN2pY1mWu', 'user', NOW())
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Sample Vaccine Registrations
INSERT INTO `registrations` (`id`, `registration_ref`, `user_id`, `full_name`, `gov_id`, `email`, `phone`, `dob`, `dose_type`, `preferred_date`, `center_name`, `pincode`, `medical_conditions`, `status`, `registered_at`) VALUES
(1, 'VAC-2024-8921', 2, 'Aarav Sharma', '458912345678', 'aarav.sharma@example.com', '9823456781', '1995-04-12', 'Booster Dose', '2026-10-20', 'City Central Government Hospital', '400001', 'None', 'Confirmed', NOW()),
(2, 'VAC-2024-3419', 3, 'Priya Nair', '912345678901', 'priya.nair@example.com', '8765432109', '1998-11-23', 'Dose 2 (Covishield)', '2026-10-22', 'Apex Healthcare Vaccination Hub', '560001', 'Mild asthma', 'Confirmed', NOW()),
(3, 'VAC-2024-7712', NULL, 'Rohan Verma', '332211445566', 'rohan.verma@example.com', '7890123456', '1990-07-15', 'RT-PCR Test', '2026-10-18', 'Suburban Diagnostics Lab', '110001', 'Fever and cold since 2 days', 'Pending', NOW())
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Sample Comments
INSERT INTO `comments` (`id`, `user_id`, `author_name`, `author_email`, `category`, `rating`, `comment_text`, `created_at`) VALUES
(1, 2, 'Aarav Sharma', 'aarav.sharma@example.com', 'Vaccination Experience', 5, 'Smooth and seamless process at City Central Hospital! The verification was quick and side effects were minimal.', NOW()),
(2, 3, 'Priya Nair', 'priya.nair@example.com', 'Safety Protocols', 5, 'Great sanitation standards observed at the center. Temperature checks and social distancing were strictly maintained.', NOW()),
(3, NULL, 'Dr. Sunita Kulkarni', 'sunita.k@healthline.org', 'Medical Advice', 5, 'Ensure adequate hydration after taking your booster dose and avoid strenuous workouts for 48 hours.', NOW())
ON DUPLICATE KEY UPDATE `id`=`id`;
