-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2022 at 06:03 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hmsci`
--

-- --------------------------------------------------------

--
-- Drop the `admin` table if it already exists
DROP TABLE IF EXISTS `admin`;

-- Recreate the `admin` table
CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Insert sample data into the `admin` table
INSERT INTO `admin` (`admin_id`, `name`, `email`, `password`, `address`, `phone`) VALUES
(1, 'Karthik Raja', 'admin@tamilnadu.com', 'Admin@123', 'Chennai, Tamil Nadu', '9876543210');

-- --------------------------------------------------------

-- Ensure foreign key dependencies are created in the correct order
-- Drop tables in reverse dependency order to avoid conflicts
DROP TABLE IF EXISTS `appointment`;
DROP TABLE IF EXISTS `bed_allotment`;
DROP TABLE IF EXISTS `bed`;
DROP TABLE IF EXISTS `diagnosis_report`;
DROP TABLE IF EXISTS `doctor`;
DROP TABLE IF EXISTS `email_template`;
DROP TABLE IF EXISTS `laboratorist`;
DROP TABLE IF EXISTS `log`;
DROP TABLE IF EXISTS `medicine`;
DROP TABLE IF EXISTS `medicine_category`;
DROP TABLE IF EXISTS `message`;
DROP TABLE IF EXISTS `noticeboard`;
DROP TABLE IF EXISTS `nurse`;
DROP TABLE IF EXISTS `patient`;
DROP TABLE IF EXISTS `pharmacist`;
DROP TABLE IF EXISTS `phc`;
DROP TABLE IF EXISTS `prescription`;
DROP TABLE IF EXISTS `report`;
DROP TABLE IF EXISTS `settings`;

-- Recreate the `phc` table first as it is referenced by other tables
CREATE TABLE `phc` (
  `phc_id` VARCHAR(50) PRIMARY KEY,
  `phc_name` VARCHAR(100) NOT NULL,
  `latitude` DOUBLE NOT NULL,
  `longitude` DOUBLE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Insert new PHC data
INSERT INTO `phc` (`phc_id`, `phc_name`, `latitude`, `longitude`, `password`, `email`) VALUES
('PHC001', 'PHC Chennai South', 12.988893318736778, 79.97096040580465 , 'phc001', 'phc001@phc.com'),
('PHC002', 'PHC Chennai East', 13.0500, 80.3000, 'phc002', 'phc002@phc.com'),
('PHC003', 'PHC Chennai West', 13.0800, 80.2000, 'phc003', 'phc003@phc.com'),
('PHC004', 'PHC Chennai North', 13.0800, 80.2000, 'phc004', 'phc004@phc.com');

-- Recreate the `patient` table
CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `password` longtext COLLATE utf8_unicode_ci NOT NULL,
  `address` longtext COLLATE utf8_unicode_ci NOT NULL,
  `phone` longtext COLLATE utf8_unicode_ci NOT NULL,
  `sex` longtext COLLATE utf8_unicode_ci NOT NULL,
  `birth_date` longtext COLLATE utf8_unicode_ci NOT NULL,
  `age` int(11) NOT NULL,
  `blood_group` longtext COLLATE utf8_unicode_ci NOT NULL,
  `account_opening_timestamp` int(11) NOT NULL,
  `phc_id` VARCHAR(50) NOT NULL,
  `pat_id` VARCHAR(100) UNIQUE,
  PRIMARY KEY (`patient_id`),
  FOREIGN KEY (`phc_id`) REFERENCES `phc`(`phc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Remove any existing triggers to avoid conflicts
DROP TRIGGER IF EXISTS `before_insert_patient`;

-- Insert 20 patients, 5 for each `phc_id`
INSERT INTO `patient` (`patient_id`, `name`, `email`, `password`, `address`, `phone`, `sex`, `birth_date`, `age`, `blood_group`, `account_opening_timestamp`, `phc_id`, `pat_id`) VALUES
(1, 'Patient A1', 'a1@phc001.com', 'pass123', 'Address A1', '9876543211', 'male', '1990-01-01', 33, 'A+', 1651084361, 'PHC001', 'PHC0011'),
(2, 'Patient A2', 'a2@phc001.com', 'pass123', 'Address A2', '9876543212', 'female', '1992-02-02', 31, 'B+', 1651084362, 'PHC001', 'PHC0012'),
(3, 'Patient A3', 'a3@phc001.com', 'pass123', 'Address A3', '9876543213', 'male', '1994-03-03', 29, 'O+', 1651084363, 'PHC001', 'PHC0013'),
(4, 'Patient A4', 'a4@phc001.com', 'pass123', 'Address A4', '9876543214', 'female', '1996-04-04', 27, 'AB+', 1651084364, 'PHC001', 'PHC0014'),
(5, 'Patient A5', 'a5@phc001.com', 'pass123', 'Address A5', '9876543215', 'male', '1998-05-05', 25, 'A-', 1651084365, 'PHC001', 'PHC0015'),

(6, 'Patient B1', 'b1@phc002.com', 'pass123', 'Address B1', '9876543221', 'female', '1991-01-01', 32, 'B-', 1651084366, 'PHC002', 'PHC0026'),
(7, 'Patient B2', 'b2@phc002.com', 'pass123', 'Address B2', '9876543222', 'male', '1993-02-02', 30, 'O-', 1651084367, 'PHC002', 'PHC0027'),
(8, 'Patient B3', 'b3@phc002.com', 'pass123', 'Address B3', '9876543223', 'female', '1995-03-03', 28, 'AB-', 1651084368, 'PHC002', 'PHC0028'),
(9, 'Patient B4', 'b4@phc002.com', 'pass123', 'Address B4', '9876543224', 'male', '1997-04-04', 26, 'A+', 1651084369, 'PHC002', 'PHC0029'),
(10, 'Patient B5', 'b5@phc002.com', 'pass123', 'Address B5', '9876543225', 'female', '1999-05-05', 24, 'B+', 1651084370, 'PHC002', 'PHC00210'),

(11, 'Patient C1', 'c1@phc003.com', 'pass123', 'Address C1', '9876543231', 'male', '1990-06-01', 33, 'O+', 1651084371, 'PHC003', 'PHC00311'),
(12, 'Patient C2', 'c2@phc003.com', 'pass123', 'Address C2', '9876543232', 'female', '1992-07-02', 31, 'AB+', 1651084372, 'PHC003', 'PHC00312'),
(13, 'Patient C3', 'c3@phc003.com', 'pass123', 'Address C3', '9876543233', 'male', '1994-08-03', 29, 'A-', 1651084373, 'PHC003', 'PHC00313'),
(14, 'Patient C4', 'c4@phc003.com', 'pass123', 'Address C4', '9876543234', 'female', '1996-09-04', 27, 'B-', 1651084374, 'PHC003', 'PHC00314'),
(15, 'Patient C5', 'c5@phc003.com', 'pass123', 'Address C5', '9876543235', 'male', '1998-10-05', 25, 'O-', 1651084375, 'PHC003', 'PHC00315'),

(16, 'Patient D1', 'd1@phc004.com', 'pass123', 'Address D1', '9876543241', 'female', '1991-11-01', 32, 'AB-', 1651084376, 'PHC004', 'PHC00416'),
(17, 'Patient D2', 'd2@phc004.com', 'pass123', 'Address D2', '9876543242', 'male', '1993-12-02', 30, 'A+', 1651084377, 'PHC004', 'PHC00417'),
(18, 'Patient D3', 'd3@phc004.com', 'pass123', 'Address D3', '9876543243', 'female', '1995-01-03', 28, 'B+', 1651084378, 'PHC004', 'PHC00418'),
(19, 'Patient D4', 'd4@phc004.com', 'pass123', 'Address D4', '9876543244', 'male', '1997-02-04', 26, 'O+', 1651084379, 'PHC004', 'PHC00419'),
(20, 'Patient D5', 'd5@phc004.com', 'pass123', 'Address D5', '9876543245', 'female', '1999-03-05', 24, 'AB+', 1651084380, 'PHC004', 'PHC00420');

-- Re-enable the `before_insert_patient` trigger
DELIMITER $$
CREATE TRIGGER `before_insert_patient`
BEFORE INSERT ON `patient`
FOR EACH ROW
BEGIN
  SET NEW.pat_id = CONCAT(NEW.phc_id, NEW.patient_id);
END$$
DELIMITER ;

-- Recreate the `doctor` table
CREATE TABLE `doctor` (
  `doctor_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `password` longtext COLLATE utf8_unicode_ci NOT NULL,
  `address` longtext COLLATE utf8_unicode_ci NOT NULL,
  `phone` longtext COLLATE utf8_unicode_ci NOT NULL,
  `phc_id` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`doctor_id`),
  FOREIGN KEY (`phc_id`) REFERENCES `phc`(`phc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Insert new Doctor data
INSERT INTO `doctor` (`doctor_id`, `name`, `email`, `password`, `address`, `phone`, `phc_id`) VALUES
(1, 'Dr. John Doe', 'john.doe@doctor.com', 'doc123', 'Chennai Central, Tamil Nadu', '9876543210', 'PHC001'),
(2, 'Dr. Jane Smith', 'jane.smith@doctor.com', 'doc456', 'Chennai East, Tamil Nadu', '9876543211', 'PHC002'),
(3, 'Dr. Emily Davis', 'emily.davis@doctor.com', 'doc789', 'Chennai West, Tamil Nadu', '9876543212', 'PHC003'),
(4, 'Dr. Suresh Kumar', 'suresh@doctor.com', 'doc789', 'Chennai North, Tamil Nadu', '9876543217', 'PHC005'),
(5, 'Dr. Meena Devi', 'meena@doctor.com', 'doc101', 'Chennai North, Tamil Nadu', '9876543218', 'PHC005'),
(6, 'Dr. Rajesh Kumar', 'rajesh@doctor.com', 'doc102', 'Chennai North, Tamil Nadu', '9876543219', 'PHC005'),
(7, 'Dr. Anitha Reddy', 'anitha@doctor.com', 'doc103', 'Chennai South, Tamil Nadu', '9876543220', 'PHC006'),
(8, 'Dr. Kiran Kumar', 'kiran@doctor.com', 'doc104', 'Chennai South, Tamil Nadu', '9876543221', 'PHC006'),
(9, 'Dr. Priyanka Sharma', 'priyanka@doctor.com', 'doc105', 'Chennai South, Tamil Nadu', '9876543222', 'PHC006'),
(10, 'Dr. Arjun Reddy', 'arjun@doctor.com', 'doc106', 'Chennai East, Tamil Nadu', '9876543223', 'PHC007'),
(11, 'Dr. Kavitha Nair', 'kavitha@doctor.com', 'doc107', 'Chennai East, Tamil Nadu', '9876543224', 'PHC007'),
(12, 'Dr. Manoj Verma', 'manoj@doctor.com', 'doc108', 'Chennai East, Tamil Nadu', '9876543225', 'PHC007');

-- Create the attendance table for real-time location tracking
CREATE TABLE IF NOT EXISTS `attendance` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `doctor_id` INT(11) NOT NULL,
    `timestamp` INT(11) NOT NULL,
    `end_timestamp` INT(11) DEFAULT NULL,
    `location` VARCHAR(255) NOT NULL,
    `status` ENUM('present', 'completed', 'absent') DEFAULT 'present',
    `duration` INT(11) DEFAULT 0 COMMENT 'Duration in minutes',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`doctor_id`) REFERENCES `doctor`(`doctor_id`) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Insert sample attendance data
INSERT INTO `attendance` (`doctor_id`, `timestamp`, `location`, `status`, `duration`) VALUES
(1, UNIX_TIMESTAMP(), '12.988893318736778, 79.97096040580465', 'present', 0),
(2, UNIX_TIMESTAMP(), '13.0500, 80.3000', 'present', 0),
(3, UNIX_TIMESTAMP(), '13.0800, 80.2000', 'present', 0);

-- Recreate the `doctor_attendance` table
CREATE TABLE `doctor_attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `doctor_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` ENUM('present', 'absent') NOT NULL,
  `duration` INT(11) NOT NULL COMMENT 'Duration in minutes',
  PRIMARY KEY (`attendance_id`),
  FOREIGN KEY (`doctor_id`) REFERENCES `doctor`(`doctor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Insert sample data into the `doctor_attendance` table
INSERT INTO `doctor_attendance` (`attendance_id`, `doctor_id`, `attendance_date`, `status`, `duration`) VALUES
(1, 1, '2025-04-21', 'present', 480),
(2, 1, '2025-04-22', 'present', 450),
(3, 1, '2025-04-23', 'absent', 0),
(4, 1, '2025-04-24', 'present', 420),
(5, 2, '2025-04-21', 'absent', 0),
(6, 3, '2025-04-21', 'present', 300)
ON DUPLICATE KEY UPDATE `duration` = VALUES(`duration`);

-- Recreate the `appointment` table
CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `appointment_timestamp` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  PRIMARY KEY (`appointment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Insert sample data into the `appointment` table
INSERT INTO `appointment` (`appointment_id`, `appointment_timestamp`, `doctor_id`, `patient_id`) VALUES
(1, 1651183200, 1, 1),
(2, 1651096800, 2, 2),
(3, 1651176030, 3, 3)
ON DUPLICATE KEY UPDATE `appointment_timestamp` = 1651176030;

-- Recreate the `bed` table
CREATE TABLE `bed` (
  `bed_id` int(11) NOT NULL,
  `bed_number` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` longtext NOT NULL COMMENT 'ward,cabin,ICU',
  `status` int(11) NOT NULL DEFAULT '0',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`bed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bed`
--

INSERT INTO `bed` (`bed_id`, `bed_number`, `type`, `status`, `description`) VALUES
(1, 'T1', 'ward', 0, 'Ward 1 - Chennai'),
(2, 'T2', 'ICU', 1, 'ICU 1 - Coimbatore'),
(3, 'T3', 'cabin', 0, 'Cabin 1 - Chennai')
ON DUPLICATE KEY UPDATE `description` = 'Cabin 1 - Chennai';

-- Recreate the `bed_allotment` table
CREATE TABLE `bed_allotment` (
  `bed_allotment_id` int(11) NOT NULL,
  `bed_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `allotment_timestamp` date NOT NULL,
  `discharge_timestamp` date NOT NULL,
  PRIMARY KEY (`bed_allotment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bed_allotment`
--

INSERT INTO `bed_allotment` (`bed_allotment_id`, `bed_id`, `patient_id`, `allotment_timestamp`, `discharge_timestamp`) VALUES
(1, 1, 1, '2022-04-01', '2022-04-10'),
(2, 2, 2, '2022-04-05', '2022-04-15'),
(3, 3, 3, '2022-05-01', '2022-05-10')
ON DUPLICATE KEY UPDATE `discharge_timestamp` = '2022-05-10';

-- Recreate the `diagnosis_report` table
CREATE TABLE `diagnosis_report` (
  `diagnosis_report_id` int(11) NOT NULL,
  `report_type` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'xray,blood test',
  `document_type` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'text,photo',
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `laboratorist_id` int(11) NOT NULL,
  PRIMARY KEY (`diagnosis_report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `diagnosis_report`
--

INSERT INTO `diagnosis_report` (`diagnosis_report_id`, `report_type`, `document_type`, `file_name`, `prescription_id`, `description`, `timestamp`, `laboratorist_id`) VALUES
(1, 'xray', 'photo', 'xray_tamilnadu.jpg', 1, 'Chest X-ray - Chennai', 1651168181, 1),
(2, 'blood test', 'text', 'blood_test_report.txt', 2, 'Blood test for anemia', 1651177030, 2)
ON DUPLICATE KEY UPDATE `description` = 'Blood test for anemia';

-- Recreate the `email_template` table
CREATE TABLE `email_template` (
  `email_template_id` int(11) NOT NULL,
  `task` longtext CHARACTER SET latin1 NOT NULL,
  `subject` longtext CHARACTER SET latin1 NOT NULL,
  `body` longtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`email_template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Insert sample data into the `email_template` table
INSERT INTO `email_template` (`email_template_id`, `task`, `subject`, `body`) VALUES
(1, 'Appointment Confirmation', 'Your Appointment is Confirmed', 'Dear Patient, your appointment is confirmed.'),
(2, 'Test Results', 'Your Test Results are Ready', 'Dear Patient, your test results are ready.'),
(3, 'Vaccination Reminder', 'Vaccination Camp Reminder', 'Dear Patient, please attend the vaccination camp on 15th May.')
ON DUPLICATE KEY UPDATE `body` = 'Dear Patient, please attend the vaccination camp on 15th May.';

-- Recreate the `laboratorist` table
CREATE TABLE `laboratorist` (
  `laboratorist_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `password` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phc_id` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`laboratorist_id`),
  FOREIGN KEY (`phc_id`) REFERENCES `phc`(`phc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `laboratorist`
--

INSERT INTO `laboratorist` (`laboratorist_id`, `name`, `email`, `password`, `address`, `phone`, `phc_id`) VALUES
(1, 'Ravi Kumar', 'ravi@lab.com', 'lab123', 'Madurai, Tamil Nadu', '9876543213', 'PHC003');

-- Insert 3 laboratorists for each PHC
INSERT INTO laboratorist (laboratorist_id, name, email, password, address, phone, phc_id) VALUES
(2, 'Laboratorist A', 'labora@phc.com', 'lab456', 'Chennai North, Tamil Nadu', '9876543247', 'PHC004'),
(3, 'Laboratorist B', 'laborb@phc.com', 'lab789', 'Chennai North, Tamil Nadu', '9876543248', 'PHC004'),
(4, 'Laboratorist C', 'laborc@phc.com', 'lab101', 'Chennai North, Tamil Nadu', '9876543249', 'PHC004');

-- Recreate the `log` table
CREATE TABLE `log` (
  `log_id` int(11) NOT NULL,
  `type` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `location` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`log_id`, `type`, `timestamp`, `user_type`, `user_id`, `description`, `ip`, `location`) VALUES
(1, 'login', 1651168181, 1, 1, 'Admin logged in', '192.168.1.1', 'Chennai'),
(2, 'logout', 1651173030, 1, 1, 'Admin logged out', '192.168.1.2', 'Coimbatore')
ON DUPLICATE KEY UPDATE `description` = 'Admin logged out';

-- Recreate the `medicine` table
CREATE TABLE `medicine` (
  `medicine_id` int(11) NOT NULL,
  `name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `medicine_category_id` int(11) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `price` longtext COLLATE utf8_unicode_ci NOT NULL,
  `manufacturing_company` longtext COLLATE utf8_unicode_ci NOT NULL,
  `status` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`medicine_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`medicine_id`, `name`, `medicine_category_id`, `description`, `price`, `manufacturing_company`, `status`) VALUES
(1, 'Paracetamol', 1, 'Pain reliever', '10', 'Tamil Nadu Pharma', 'Available'),
(2, 'Amoxicillin', 1, 'Antibiotic', '20', 'Tamil Nadu Pharma', 'Available')
ON DUPLICATE KEY UPDATE `description` = 'Antibiotic';

-- Recreate the `medicine_category` table
CREATE TABLE `medicine_category` (
  `medicine_category_id` int(11) NOT NULL,
  `name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`medicine_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `medicine_category`
--

INSERT INTO `medicine_category` (`medicine_category_id`, `name`, `description`) VALUES
(1, 'Painkillers', 'Medicines for pain relief'),
(2, 'Antibiotics', 'Medicines for bacterial infections')
ON DUPLICATE KEY UPDATE `description` = 'Medicines for bacterial infections';

-- Recreate the `message` table
CREATE TABLE `message` (
  `message_id` int(11) NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `user_from_type` longtext COLLATE utf8_unicode_ci NOT NULL,
  `user_from_id` int(11) NOT NULL,
  `user_to_type` longtext COLLATE utf8_unicode_ci NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `timestamp` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `message`, `user_from_type`, `user_from_id`, `user_to_type`, `user_type_id`, `timestamp`) VALUES
(1, 'Hello, how can I help you?', 'doctor', 1, 'patient', 1, 1651168181),
(2, 'Please take your medication on time.', 'doctor', 2, 'patient', 2, 1651174030)
ON DUPLICATE KEY UPDATE `message` = 'Please take your medication on time.';

-- Recreate the `noticeboard` table
CREATE TABLE `noticeboard` (
  `notice_id` int(11) NOT NULL,
  `notice_title` longtext COLLATE utf8_unicode_ci NOT NULL,
  `notice` longtext COLLATE utf8_unicode_ci NOT NULL,
  `create_timestamp` int(11) NOT NULL,
  PRIMARY KEY (`notice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `noticeboard`
--

INSERT INTO `noticeboard` (`notice_id`, `notice_title`, `notice`, `create_timestamp`) VALUES
(1, 'Holiday Notice', 'Hospital will be closed on Tamil New Year.', 1651168181),
(2, 'Vaccination Camp', 'Free vaccination camp on 15th May.', 1651175030)
ON DUPLICATE KEY UPDATE `notice` = 'Free vaccination camp on 15th May.';

-- Recreate the `nurse` table
CREATE TABLE `nurse` (
  `nurse_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `password` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phc_id` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`nurse_id`),
  FOREIGN KEY (`phc_id`) REFERENCES `phc`(`phc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nurse`
--

INSERT INTO `nurse` (`nurse_id`, `name`, `email`, `password`, `address`, `phone`, `phc_id`) VALUES
(1, 'Nisha Ramesh', 'nisha@nurse.com', 'nurse123', 'Trichy, Tamil Nadu', '9876543214', 'PHC001');

-- Insert 3 nurses for each PHC
INSERT INTO nurse (nurse_id, name, email, password, address, phone, phc_id) VALUES
(2, 'Nurse Asha', 'asha@nurse.com', 'nurse456', 'Chennai North, Tamil Nadu', '9876543229', 'PHC004'),
(3, 'Nurse Rani', 'rani@nurse.com', 'nurse789', 'Chennai North, Tamil Nadu', '9876543230', 'PHC004'),
(4, 'Nurse Priya', 'priya@nurse.com', 'nurse101', 'Chennai North, Tamil Nadu', '9876543231', 'PHC004'),
(5, 'Nurse Kavitha', 'kavitha@nurse.com', 'nurse102', 'Chennai South, Tamil Nadu', '9876543232', 'PHC005'),
(6, 'Nurse Anjali', 'anjali@nurse.com', 'nurse103', 'Chennai South, Tamil Nadu', '9876543233', 'PHC005'),
(7, 'Nurse Sneha', 'sneha@nurse.com', 'nurse104', 'Chennai South, Tamil Nadu', '9876543234', 'PHC005'),
(8, 'Nurse Rekha', 'rekha@nurse.com', 'nurse105', 'Chennai East, Tamil Nadu', '9876543235', 'PHC006'),
(9, 'Nurse Divya', 'divya@nurse.com', 'nurse106', 'Chennai East, Tamil Nadu', '9876543236', 'PHC006'),
(10, 'Nurse Meera', 'meera@nurse.com', 'nurse107', 'Chennai East, Tamil Nadu', '9876543237', 'PHC006'),
(11, 'Nurse Lakshmi', 'lakshmi@nurse.com', 'nurse108', 'Chennai West, Tamil Nadu', '9876543238', 'PHC007'),
(12, 'Nurse Radha', 'radha@nurse.com', 'nurse109', 'Chennai West, Tamil Nadu', '9876543239', 'PHC007'),
(13, 'Nurse Shalini', 'shalini@nurse.com', 'nurse110', 'Chennai West, Tamil Nadu', '9876543240', 'PHC007');

-- Recreate the `pharmacist` table
CREATE TABLE `pharmacist` (
  `pharmacist_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `password` longtext COLLATE utf8_unicode_ci NOT NULL,
  `address` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phc_id` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`pharmacist_id`),
  FOREIGN KEY (`phc_id`) REFERENCES `phc`(`phc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pharmacist`
--

INSERT INTO `pharmacist` (`pharmacist_id`, `name`, `email`, `password`, `address`, `phone`, `phc_id`) VALUES
(1, 'Manoj Kumar', 'manoj@pharmacist.com', 'pharma123', 'Salem, Tamil Nadu', '9876543216', 'PHC002');

-- Insert 3 pharmacists for each PHC
INSERT INTO pharmacist (pharmacist_id, name, email, password, address, phone, phc_id) VALUES
(2, 'Pharmacist A', 'pharmaa@phc.com', 'pharma456', 'Chennai North, Tamil Nadu', '9876543244', 'PHC004'),
(3, 'Pharmacist B', 'pharmab@phc.com', 'pharma789', 'Chennai North, Tamil Nadu', '9876543245', 'PHC004'),
(4, 'Pharmacist C', 'pharmac@phc.com', 'pharma101', 'Chennai North, Tamil Nadu', '9876543246', 'PHC004');

-- Recreate the `prescription` table
CREATE TABLE `prescription` (
  `prescription_id` int(11) NOT NULL,
  `creation_timestamp` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `case_history` longtext COLLATE utf8_unicode_ci NOT NULL,
  `medication` longtext COLLATE utf8_unicode_ci NOT NULL,
  `medication_from_pharmacist` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`prescription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`prescription_id`, `creation_timestamp`, `doctor_id`, `patient_id`, `case_history`, `medication`, `medication_from_pharmacist`, `description`) VALUES
(1, 1651170030, 1, 1, 'Fever and cold', 'Paracetamol 500mg', 'Take one tablet daily', 'Follow up in 3 days'),
(2, 1651171030, 2, 2, 'Headache', 'Ibuprofen 200mg', 'Take one tablet twice daily', 'Follow up in 5 days')
ON DUPLICATE KEY UPDATE `case_history` = 'Headache', `medication` = 'Ibuprofen 200mg';

-- Recreate the `report` table
CREATE TABLE `report` (
  `report_id` int(11) NOT NULL,
  `type` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'operation,birth,death',
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`report_id`, `type`, `description`, `timestamp`, `doctor_id`, `patient_id`) VALUES
(1, 'operation', 'Appendix surgery', 1651161137, 1, 1),
(2, 'birth', 'Normal delivery', 1651172030, 2, 2)
ON DUPLICATE KEY UPDATE `description` = 'Normal delivery';

-- Recreate the `settings` table
CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL,
  `type` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES
(1, 'system_name', 'Tamil Nadu Hospital Management System');

ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `bed`
--
ALTER TABLE `bed`
  MODIFY `bed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `bed_allotment`
--
ALTER TABLE `bed_allotment`
  MODIFY `bed_allotment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `diagnosis_report`
--
ALTER TABLE `diagnosis_report`
  MODIFY `diagnosis_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `email_template`
--
ALTER TABLE `email_template`
  MODIFY `email_template_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `laboratorist`
--
ALTER TABLE `laboratorist`
  MODIFY `laboratorist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `medicine_category`
--
ALTER TABLE `medicine_category`
  MODIFY `medicine_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `noticeboard`
--
ALTER TABLE `noticeboard`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `nurse`
--
ALTER TABLE `nurse`
  MODIFY `nurse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pharmacist`
--
ALTER TABLE `pharmacist`
  MODIFY `pharmacist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

-- Drop the `blood_bank` and `blood_donor` tables entirely.

DROP TABLE IF EXISTS `blood_bank`;
DROP TABLE IF EXISTS `blood_donor`;

-- Remove any references to these tables in other tables or queries.

-- Drop the `language` table
DROP TABLE IF EXISTS `language`;

-- Remove references to other languages in the application
-- Ensure only English is used as the default language in all relevant tables and queries.

-- Example: Update any table or query that references multiple languages to use only English.
-- For instance, if there are columns for multiple languages, keep only the English column.

-- Drop the `payment` and `invoice` tables entirely.
DROP TABLE IF EXISTS `payment`;
DROP TABLE IF EXISTS `invoice`;

-- Ensure `phc_id` in all user tables references the `phc` table.
ALTER TABLE doctor ADD FOREIGN KEY (phc_id) REFERENCES phc(phc_id);
ALTER TABLE patient ADD FOREIGN KEY (phc_id) REFERENCES phc(phc_id);
ALTER TABLE nurse ADD FOREIGN KEY (phc_id) REFERENCES phc(phc_id);
ALTER TABLE pharmacist ADD FOREIGN KEY (phc_id) REFERENCES phc(phc_id);
ALTER TABLE laboratorist ADD FOREIGN KEY (phc_id) REFERENCES phc(phc_id);

-- Update sample data to include `phc_id` for all users.
UPDATE doctor SET phc_id = 'PHC001' WHERE doctor_id = 1;
UPDATE patient SET phc_id = 'PHC002' WHERE patient_id = 1;
UPDATE nurse SET phc_id = 'PHC003' WHERE nurse_id = 1;
UPDATE pharmacist SET phc_id = 'PHC001' WHERE pharmacist_id = 1;
UPDATE laboratorist SET phc_id = 'PHC002' WHERE laboratorist_id = 1;

-- Update PHC table

-- Update Doctor table

-- Update Patient table

-- Update Nurse table

-- Update Pharmacist table

-- Update Laboratorist table

-- Ensure foreign key references are updated to use the new primary keys
ALTER TABLE doctor ADD FOREIGN KEY (phc_id) REFERENCES phc(phc_id);
ALTER TABLE patient ADD FOREIGN KEY (phc_id) REFERENCES phc(phc_id);
ALTER TABLE nurse ADD FOREIGN KEY (phc_id) REFERENCES phc(phc_id);
ALTER TABLE pharmacist ADD FOREIGN KEY (phc_id) REFERENCES phc(phc_id);
ALTER TABLE laboratorist ADD FOREIGN KEY (phc_id) REFERENCES phc(phc_id);

-- Drop the `accountant` table if it exists
DROP TABLE IF EXISTS `accountant`;

-- Remove any references to the `accountant` table in queries or foreign keys
-- Ensure no dependencies exist for the `accountant` table

-- Update queries or views that reference the `accountant` table
-- Example: Replace `accountant` references with valid tables or remove them entirely

-- Ensure all foreign key relationships are updated to exclude `accountant`
-- Example: Remove any foreign key constraints related to `accountant`

-- Drop the `accountant` table
DROP TABLE IF EXISTS `accountant`;

-- Remove any references to the `accountant` table in other tables or queries
-- Ensure no foreign keys or dependencies exist for the `accountant` table

-- Ensure only `pat_id` is set as the `PRIMARY KEY` by first dropping the existing primary key

-- Update foreign key relationships to remove references to `user_id`
ALTER TABLE `doctor` DROP FOREIGN KEY `doctor_ibfk_1`;
ALTER TABLE `doctor` ADD FOREIGN KEY (`phc_id`) REFERENCES `phc`(`phc_id`);

ALTER TABLE `nurse` DROP FOREIGN KEY `nurse_ibfk_1`;
ALTER TABLE `nurse` ADD FOREIGN KEY (`phc_id`) REFERENCES `phc`(`phc_id`);

ALTER TABLE `patient` DROP FOREIGN KEY `patient_ibfk_1`;
ALTER TABLE `patient` ADD FOREIGN KEY (`phc_id`) REFERENCES `phc`(`phc_id`);

ALTER TABLE `laboratorist` DROP FOREIGN KEY `laboratorist_ibfk_1`;
ALTER TABLE `laboratorist` ADD FOREIGN KEY (`phc_id`) REFERENCES `phc`(`phc_id`);

ALTER TABLE `pharmacist` DROP FOREIGN KEY `pharmacist_ibfk_1`;
ALTER TABLE `pharmacist` ADD FOREIGN KEY (`phc_id`) REFERENCES `phc`(`phc_id`);


-- Ensure `pat_id` is a unique key
ALTER TABLE `patient`
ADD UNIQUE (`pat_id`);

-- Remove invalid PHC entries
DELETE FROM `phc` WHERE `phc_id` IN ('PHC005', 'PHC006', 'PHC007');

-- Update Doctor data to use valid PHC IDs
UPDATE `doctor` SET `phc_id` = 'PHC001' WHERE `doctor_id` = 4;
UPDATE `doctor` SET `phc_id` = 'PHC002' WHERE `doctor_id` = 5;
UPDATE `doctor` SET `phc_id` = 'PHC003' WHERE `doctor_id` = 6;
UPDATE `doctor` SET `phc_id` = 'PHC004' WHERE `doctor_id` = 7;
UPDATE `doctor` SET `phc_id` = 'PHC001' WHERE `doctor_id` = 8;
UPDATE `doctor` SET `phc_id` = 'PHC002' WHERE `doctor_id` = 9;
UPDATE `doctor` SET `phc_id` = 'PHC003' WHERE `doctor_id` = 10;
UPDATE `doctor` SET `phc_id` = 'PHC004' WHERE `doctor_id` = 11;
UPDATE `doctor` SET `phc_id` = 'PHC001' WHERE `doctor_id` = 12;


