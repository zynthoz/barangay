CREATE DATABASE IF NOT EXISTS brms_db;
USE brms_db;

CREATE TABLE tbl_users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100),
  role VARCHAR(20),
  username VARCHAR(50),
  password VARCHAR(100),
  email VARCHAR(100)
);

CREATE TABLE tbl_logs (
  log_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  action VARCHAR(255),
  date_time DATETIME
);

CREATE TABLE tbl_residents (
  resident_id INT AUTO_INCREMENT PRIMARY KEY,
  resident_name VARCHAR(100),
  date_of_birth DATE,
  gender VARCHAR(20),
  contact_information VARCHAR(100),
  address VARCHAR(255)
);

CREATE TABLE tbl_households (
  household_id INT AUTO_INCREMENT PRIMARY KEY,
  household_head VARCHAR(100),
  address VARCHAR(255),
  contact_information VARCHAR(100),
  number_of_members INT
);

CREATE TABLE tbl_officials (
  official_id INT AUTO_INCREMENT PRIMARY KEY,
  official_name VARCHAR(100),
  position VARCHAR(100),
  contact_information VARCHAR(100)
);

CREATE TABLE tbl_incident_reports (
  report_id INT AUTO_INCREMENT PRIMARY KEY,
  resident_id INT,
  official_id INT,
  incident_type VARCHAR(100),
  incident_details TEXT,
  date_reported DATE
);

CREATE TABLE tbl_projects (
  project_id INT AUTO_INCREMENT PRIMARY KEY,
  project_name VARCHAR(100),
  project_description TEXT,
  start_date DATE,
  end_date DATE,
  project_status VARCHAR(50)
);

-- Insert default admin user
INSERT INTO tbl_users (full_name, role, username, password, email) VALUES ('System Admin', 'Admin', 'admin', 'admin', 'admin@brms.local');
