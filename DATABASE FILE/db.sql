-- Primary Entities
CREATE TABLE IF NOT EXISTS phc (
  phc_id VARCHAR(50) PRIMARY KEY,
  phc_name VARCHAR(100) NOT NULL,
  latitude DOUBLE NOT NULL,
  longitude DOUBLE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS admin (
  admin_id INT AUTO_INCREMENT PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  password TEXT NOT NULL,
  address TEXT NOT NULL,
  phone TEXT NOT NULL
);

-- Medical Staff
CREATE TABLE IF NOT EXISTS doctor (
  doctor_id INT AUTO_INCREMENT PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  password TEXT NOT NULL,
  address TEXT NOT NULL,
  phone TEXT NOT NULL,
  phc_id VARCHAR(50) NOT NULL,
  FOREIGN KEY (phc_id) REFERENCES phc(phc_id)
);

CREATE TABLE IF NOT EXISTS nurse (
  nurse_id INT AUTO_INCREMENT PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  password TEXT NOT NULL,
  address TEXT NOT NULL,
  phone TEXT NOT NULL,
  phc_id VARCHAR(50) NOT NULL,
  FOREIGN KEY (phc_id) REFERENCES phc(phc_id)
);

CREATE TABLE IF NOT EXISTS laboratorist (
  laboratorist_id INT AUTO_INCREMENT PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  password TEXT NOT NULL,
  address TEXT NOT NULL,
  phone TEXT NOT NULL,
  phc_id VARCHAR(50) NOT NULL,
  FOREIGN KEY (phc_id) REFERENCES phc(phc_id)
);

CREATE TABLE IF NOT EXISTS pharmacist (
  pharmacist_id INT AUTO_INCREMENT PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  password TEXT NOT NULL,
  address TEXT NOT NULL,
  phone TEXT NOT NULL,
  phc_id VARCHAR(50) NOT NULL,
  FOREIGN KEY (phc_id) REFERENCES phc(phc_id)
);

-- Patient Management
CREATE TABLE IF NOT EXISTS patient (
  patient_id INT AUTO_INCREMENT PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  password TEXT NOT NULL,
  address TEXT NOT NULL,
  phone TEXT NOT NULL,
  sex TEXT NOT NULL,
  birth_date TEXT NOT NULL,
  age INT NOT NULL,
  blood_group TEXT NOT NULL,
  account_opening_timestamp INT NOT NULL,
  phc_id VARCHAR(50) NOT NULL,
  pat_id VARCHAR(100),
  UNIQUE KEY (pat_id),
  FOREIGN KEY (phc_id) REFERENCES phc(phc_id)
);

CREATE TABLE IF NOT EXISTS appointment (
  appointment_id INT PRIMARY KEY,
  appointment_timestamp INT NOT NULL,
  doctor_id INT NOT NULL,
  patient_id INT NOT NULL,
  FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id),
  FOREIGN KEY (patient_id) REFERENCES patient(patient_id)
);

-- Attendance Tracking
CREATE TABLE IF NOT EXISTS attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT NOT NULL,
  timestamp INT NOT NULL,
  end_timestamp INT NULL,
  location VARCHAR(255) NOT NULL,
  status ENUM('present', 'completed', 'absent') DEFAULT 'present',
  duration INT DEFAULT 0,
  FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id)
);

CREATE TABLE IF NOT EXISTS doctor_attendance (
  attendance_id INT AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT NOT NULL,
  attendance_date DATE NOT NULL,
  status ENUM('present', 'absent') DEFAULT 'absent',
  duration INT DEFAULT 0,
  FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id)
);

-- Medical Records
CREATE TABLE IF NOT EXISTS prescription (
  prescription_id INT PRIMARY KEY,
  creation_timestamp INT NOT NULL,
  doctor_id INT NOT NULL,
  patient_id INT NOT NULL,
  case_history TEXT NOT NULL,
  medication TEXT NOT NULL,
  medication_from_pharmacist TEXT NOT NULL,
  description TEXT NOT NULL,
  FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id),
  FOREIGN KEY (patient_id) REFERENCES patient(patient_id)
);

CREATE TABLE IF NOT EXISTS diagnosis_report (
  diagnosis_report_id INT PRIMARY KEY,
  report_type TEXT NOT NULL,
  document_type TEXT NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  prescription_id INT NOT NULL,
  description TEXT NOT NULL,
  timestamp INT NOT NULL,
  laboratorist_id INT NOT NULL,
  FOREIGN KEY (prescription_id) REFERENCES prescription(prescription_id),
  FOREIGN KEY (laboratorist_id) REFERENCES laboratorist(laboratorist_id)
);

-- Hospital Resources
CREATE TABLE IF NOT EXISTS bed (
  bed_id INT PRIMARY KEY,
  bed_number TEXT NOT NULL,
  type TEXT NOT NULL,
  status INT NOT NULL,
  description VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS bed_allotment (
  bed_allotment_id INT PRIMARY KEY,
  bed_id INT NOT NULL,
  patient_id INT NOT NULL,
  allotment_timestamp DATE NOT NULL,
  discharge_timestamp DATE NOT NULL,
  FOREIGN KEY (bed_id) REFERENCES bed(bed_id),
  FOREIGN KEY (patient_id) REFERENCES patient(patient_id)
);

-- Medicine Management
CREATE TABLE IF NOT EXISTS medicine_category (
  medicine_category_id INT PRIMARY KEY,
  name TEXT NOT NULL,
  description TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS medicine (
  medicine_id INT PRIMARY KEY,
  name TEXT NOT NULL,
  medicine_category_id INT NOT NULL,
  description TEXT NOT NULL,
  price TEXT NOT NULL,
  manufacturing_company TEXT NOT NULL,
  status TEXT NOT NULL,
  FOREIGN KEY (medicine_category_id) REFERENCES medicine_category(medicine_category_id)
);

-- Communication
CREATE TABLE IF NOT EXISTS message (
  message_id INT PRIMARY KEY,
  message TEXT NOT NULL,
  user_from_type TEXT NOT NULL,
  user_from_id INT NOT NULL,
  user_to_type TEXT NOT NULL,
  user_type_id INT NOT NULL,
  timestamp TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS noticeboard (
  notice_id INT PRIMARY KEY,
  notice_title TEXT NOT NULL,
  notice TEXT NOT NULL,
  create_timestamp INT NOT NULL
);

-- System
CREATE TABLE IF NOT EXISTS settings (
  settings_id INT PRIMARY KEY,
  type TEXT NOT NULL,
  description TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS log (
  log_id INT PRIMARY KEY,
  type TEXT NOT NULL,
  timestamp INT NOT NULL,
  user_type INT NOT NULL,
  user_id INT NOT NULL,
  description TEXT NOT NULL,
  ip TEXT NOT NULL,
  location TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS report (
  report_id INT PRIMARY KEY,
  type TEXT NOT NULL,
  description TEXT NOT NULL,
  timestamp INT NOT NULL,
  doctor_id INT NOT NULL,
  patient_id INT NOT NULL,
  FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id),
  FOREIGN KEY (patient_id) REFERENCES patient(patient_id)
);

-- Add indexes for performance
CREATE INDEX idx_phc_id ON doctor(phc_id);
CREATE INDEX idx_pat_id ON patient(pat_id);
CREATE INDEX idx_doctor_phc ON doctor(phc_id);
CREATE INDEX idx_patient_phc ON patient(phc_id);

-- Set up foreign key relationships
ALTER TABLE nurse DROP FOREIGN KEY `nurse_phc_fk`;
ALTER TABLE nurse ADD CONSTRAINT `nurse_phc_fk` FOREIGN KEY (phc_id) REFERENCES phc(phc_id);

ALTER TABLE laboratorist DROP FOREIGN KEY `laboratorist_phc_fk`;
ALTER TABLE laboratorist ADD CONSTRAINT `laboratorist_phc_fk` FOREIGN KEY (phc_id) REFERENCES phc(phc_id);

ALTER TABLE pharmacist DROP FOREIGN KEY `pharmacist_phc_fk`;
ALTER TABLE pharmacist ADD CONSTRAINT `pharmacist_phc_fk` FOREIGN KEY (phc_id) REFERENCES phc(phc_id);

ALTER TABLE appointment DROP FOREIGN KEY `appointment_doctor_fk`;
ALTER TABLE appointment ADD CONSTRAINT `appointment_doctor_fk` FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id);

ALTER TABLE appointment DROP FOREIGN KEY `appointment_patient_fk`;
ALTER TABLE appointment ADD CONSTRAINT `appointment_patient_fk` FOREIGN KEY (patient_id) REFERENCES patient(patient_id);
