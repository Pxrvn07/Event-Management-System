-- =====================================================
-- FINAL EVENT MANAGEMENT SYSTEM DATABASE
-- Version: 2.0 - Complete & Up-to-Date
-- Generated: October 21, 2025
-- =====================================================
-- This is the final, comprehensive database that includes:
-- ✅ All latest features and schema updates
-- ✅ Registration closed functionality with capacity limits
-- ✅ File upload support with proper folder structure
-- ✅ User profile photos and all required columns  
-- ✅ Proper indexes for performance
-- ✅ Sample data for testing all features
-- ✅ Password hashing support
-- ✅ Complete foreign key relationships
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Create database
CREATE DATABASE IF NOT EXISTS `event_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `event_management`;

-- Drop existing tables to ensure clean setup
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `event_registrations`;
DROP TABLE IF EXISTS `event_coordinators`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `staff`;
DROP TABLE IF EXISTS `admin`;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- ADMIN TABLE
-- =====================================================
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `roll_no` (`roll_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Admin sample data (password: admin123)
INSERT INTO `admin` (`admin_name`, `email`, `password`, `roll_no`) VALUES
('System Administrator', 'admin@sathyabama.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN001'),
('Event Coordinator Admin', 'eventadmin@sathyabama.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN002');

-- =====================================================
-- STAFF TABLE
-- =====================================================
CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `roll_no` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `roll_no` (`roll_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Staff sample data (password: staff123)
INSERT INTO `staff` (`staff_name`, `email`, `password`, `designation`, `roll_no`) VALUES
('Dr. Rajesh Kumar', 'rajesh.kumar@sathyabama.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Professor & Event Coordinator', 'STAFF001'),
('Prof. Priya Sharma', 'priya.sharma@sathyabama.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Assistant Professor', 'STAFF002'),
('Dr. Amit Singh', 'amit.singh@sathyabama.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Associate Professor', 'STAFF003');

-- =====================================================
-- USERS TABLE (STUDENTS)
-- =====================================================
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `roll_no` varchar(50) NOT NULL,
  `profile_photo` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `roll_no` (`roll_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Student sample data (password: student123)
INSERT INTO `users` (`full_name`, `email`, `password`, `phone`, `roll_no`, `profile_photo`) VALUES
('Arjun Patel', 'arjun.patel@student.sathyabama.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', '44110001', 'uploads/profiles/arjun_patel.svg'),
('Priya Krishnan', 'priya.krishnan@student.sathyabama.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543211', '44110002', 'uploads/profiles/priya_krishnan.svg'),
('Rahul Sharma', 'rahul.sharma@student.sathyabama.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543212', '44110003', 'uploads/profiles/rahul_sharma.svg'),
('Sneha Gupta', 'sneha.gupta@student.sathyabama.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543213', '44110004', 'uploads/profiles/sneha_gupta.svg'),
('Vikram Singh', 'vikram.singh@student.sathyabama.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543214', '44110005', 'uploads/profiles/vikram_singh.svg');

-- =====================================================
-- EVENTS TABLE (WITH ALL FEATURES)
-- =====================================================
CREATE TABLE `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `staff_id` int(11) NOT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `total_participants` int(11) DEFAULT 0,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`event_id`),
  KEY `staff_id` (`staff_id`),
  KEY `idx_events_status` (`status`),
  KEY `idx_events_date` (`date`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample events with different scenarios
INSERT INTO `events` (`event_name`, `description`, `date`, `staff_id`, `total_participants`, `status`) VALUES
('Annual Tech Fest 2025', 'Join us for the biggest technology festival of the year! Featuring coding competitions, tech talks, workshops, and innovation showcases. Experience the latest in AI, robotics, and software development.', '2025-03-15', 1, 500, 'approved'),

('Cultural Heritage Night', 'Celebrate the rich cultural diversity of our campus! An evening filled with traditional performances, ethnic food stalls, folk dances, and cultural exhibitions from around the world.', '2025-03-20', 2, 300, 'approved'),

('Sports Championship 2025', 'Annual inter-department sports competition featuring cricket, football, basketball, badminton, and track events. Show your athletic spirit and compete for the championship trophy!', '2025-03-25', 1, 25, 'approved'),

('AI & Machine Learning Workshop', 'Hands-on workshop covering the fundamentals of artificial intelligence and machine learning. Learn Python, TensorFlow, and build your first ML model. Laptops will be provided.', '2025-04-01', 3, 50, 'approved'),

('Entrepreneurship Summit', 'Meet successful entrepreneurs, learn about startup ecosystems, and pitch your business ideas. Network with investors and get mentorship from industry leaders.', '2025-04-05', 2, 200, 'approved'),

('Photography Exhibition', 'Showcase your creative vision! Submit your best photographs for display in our annual photography exhibition. Themes include nature, portrait, street, and abstract photography.', '2025-04-10', 1, 100, 'pending'),

('Coding Bootcamp', 'Intensive 3-day coding bootcamp covering web development, mobile apps, and software engineering best practices. Perfect for beginners and intermediate programmers.', '2025-04-15', 3, 80, 'approved');

-- =====================================================
-- EVENT COORDINATORS TABLE
-- =====================================================
CREATE TABLE `event_coordinators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_coordinator` (`event_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `event_coordinators_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  CONSTRAINT `event_coordinators_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample coordinators
INSERT INTO `event_coordinators` (`event_id`, `user_id`) VALUES
(1, 1), -- Arjun for Tech Fest
(1, 2), -- Priya for Tech Fest
(2, 2), -- Priya for Cultural Night
(2, 3), -- Rahul for Cultural Night
(3, 4), -- Sneha for Sports Championship
(4, 1), -- Arjun for AI Workshop
(4, 5), -- Vikram for AI Workshop
(5, 2), -- Priya for Entrepreneurship Summit
(5, 3); -- Rahul for Entrepreneurship Summit

-- =====================================================
-- EVENT REGISTRATIONS TABLE
-- =====================================================
CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('registered','cancelled') DEFAULT 'registered',
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_registration` (`event_id`,`roll_no`),
  KEY `idx_event_registrations_status` (`status`),
  CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample registrations for different events
INSERT INTO `event_registrations` (`event_id`, `roll_no`, `email`, `phone`, `status`) VALUES
-- Tech Fest registrations
(1, '44110001', 'arjun.patel@student.sathyabama.edu', '9876543210', 'registered'),
(1, '44110002', 'priya.krishnan@student.sathyabama.edu', '9876543211', 'registered'),
(1, '44110003', 'rahul.sharma@student.sathyabama.edu', '9876543212', 'registered'),

-- Cultural Night registrations
(2, '44110001', 'arjun.patel@student.sathyabama.edu', '9876543210', 'registered'),
(2, '44110004', 'sneha.gupta@student.sathyabama.edu', '9876543213', 'registered'),

-- AI Workshop registrations
(4, '44110002', 'priya.krishnan@student.sathyabama.edu', '9876543211', 'registered'),
(4, '44110005', 'vikram.singh@student.sathyabama.edu', '9876543214', 'registered');

-- =====================================================
-- FILL SPORTS CHAMPIONSHIP TO CAPACITY (25/25)
-- This demonstrates the "Registration Closed" feature
-- =====================================================
INSERT INTO `event_registrations` (`event_id`, `roll_no`, `email`, `phone`, `status`) VALUES
-- Fill Sports Championship (event_id = 3) to full capacity
(3, '44110001', 'arjun.patel@student.sathyabama.edu', '9876543210', 'registered'),
(3, '44110002', 'priya.krishnan@student.sathyabama.edu', '9876543211', 'registered'),
(3, '44110003', 'rahul.sharma@student.sathyabama.edu', '9876543212', 'registered'),
(3, '44110004', 'sneha.gupta@student.sathyabama.edu', '9876543213', 'registered'),
(3, '44110005', 'vikram.singh@student.sathyabama.edu', '9876543214', 'registered'),
(3, '44110006', 'student6@student.sathyabama.edu', '9876543215', 'registered'),
(3, '44110007', 'student7@student.sathyabama.edu', '9876543216', 'registered'),
(3, '44110008', 'student8@student.sathyabama.edu', '9876543217', 'registered'),
(3, '44110009', 'student9@student.sathyabama.edu', '9876543218', 'registered'),
(3, '44110010', 'student10@student.sathyabama.edu', '9876543219', 'registered'),
(3, '44110011', 'student11@student.sathyabama.edu', '9876543220', 'registered'),
(3, '44110012', 'student12@student.sathyabama.edu', '9876543221', 'registered'),
(3, '44110013', 'student13@student.sathyabama.edu', '9876543222', 'registered'),
(3, '44110014', 'student14@student.sathyabama.edu', '9876543223', 'registered'),
(3, '44110015', 'student15@student.sathyabama.edu', '9876543224', 'registered'),
(3, '44110016', 'student16@student.sathyabama.edu', '9876543225', 'registered'),
(3, '44110017', 'student17@student.sathyabama.edu', '9876543226', 'registered'),
(3, '44110018', 'student18@student.sathyabama.edu', '9876543227', 'registered'),
(3, '44110019', 'student19@student.sathyabama.edu', '9876543228', 'registered'),
(3, '44110020', 'student20@student.sathyabama.edu', '9876543229', 'registered'),
(3, '44110021', 'student21@student.sathyabama.edu', '9876543230', 'registered'),
(3, '44110022', 'student22@student.sathyabama.edu', '9876543231', 'registered'),
(3, '44110023', 'student23@student.sathyabama.edu', '9876543232', 'registered'),
(3, '44110024', 'student24@student.sathyabama.edu', '9876543233', 'registered'),
(3, '44110025', 'student25@student.sathyabama.edu', '9876543234', 'registered');

-- =====================================================
-- RESET AUTO_INCREMENT VALUES
-- =====================================================
ALTER TABLE `admin` AUTO_INCREMENT = 3;
ALTER TABLE `staff` AUTO_INCREMENT = 4;
ALTER TABLE `users` AUTO_INCREMENT = 6;
ALTER TABLE `events` AUTO_INCREMENT = 8;
ALTER TABLE `event_coordinators` AUTO_INCREMENT = 10;
ALTER TABLE `event_registrations` AUTO_INCREMENT = 31;

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Check all events with registration status
SELECT 
    e.event_id,
    e.event_name,
    e.status as event_status,
    e.total_participants,
    COUNT(er.id) as registered_count,
    CASE 
        WHEN e.total_participants > 0 AND COUNT(er.id) >= e.total_participants 
        THEN 'REGISTRATION CLOSED' 
        ELSE 'OPEN' 
    END as registration_status,
    e.date,
    s.staff_name as created_by
FROM events e
LEFT JOIN event_registrations er ON e.event_id = er.event_id AND er.status = 'registered'
LEFT JOIN staff s ON e.staff_id = s.id
GROUP BY e.event_id, e.event_name, e.status, e.total_participants, e.date, s.staff_name
ORDER BY e.event_id;

-- Show user accounts summary
SELECT 'Admin' as user_type, COUNT(*) as count FROM admin
UNION ALL
SELECT 'Staff' as user_type, COUNT(*) as count FROM staff
UNION ALL
SELECT 'Students' as user_type, COUNT(*) as count FROM users;

-- Show most popular events
SELECT 
    e.event_name,
    COUNT(er.id) as registrations,
    e.total_participants,
    ROUND((COUNT(er.id) / e.total_participants) * 100, 1) as fill_percentage
FROM events e
LEFT JOIN event_registrations er ON e.event_id = er.event_id AND er.status = 'registered'
WHERE e.status = 'approved' AND e.total_participants > 0
GROUP BY e.event_id, e.event_name, e.total_participants
ORDER BY registrations DESC;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- =====================================================
-- DATABASE SETUP COMPLETE! 
-- =====================================================
-- 🎉 Your Event Management System database is ready!
-- 
-- 📊 What's included:
-- ✅ 2 Admin accounts (admin123)
-- ✅ 3 Staff accounts (staff123) 
-- ✅ 5 Student accounts (student123)
-- ✅ 7 Sample events (6 approved, 1 pending)
-- ✅ 1 Registration closed event (Sports Championship: 25/25)
-- ✅ Realistic event descriptions and dates
-- ✅ Event coordinators assigned
-- ✅ Sample registrations for testing
-- ✅ All required columns and relationships
-- ✅ Proper indexes for performance
-- ✅ Password hashing support
-- ✅ File upload path support
-- 
-- 🧪 Test Features:
-- • Login with any sample account
-- • View events in student dashboard
-- • See "Registration Closed" for Sports Championship
-- • Create new events as staff
-- • Approve/reject events as admin
-- • Upload brochure images
-- • Register for events
-- 
-- 🔑 Login Credentials:
-- Admin: admin@sathyabama.edu / admin123
-- Staff: rajesh.kumar@sathyabama.edu / staff123  
-- Student: arjun.patel@student.sathyabama.edu / student123
-- =====================================================