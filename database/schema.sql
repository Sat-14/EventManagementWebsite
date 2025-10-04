-- Event Management Database Schema
-- Created for Event Management Website

CREATE DATABASE IF NOT EXISTS event_management;
USE event_management;

-- Users/Sign-up table
CREATE TABLE IF NOT EXISTS sign_up (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(50) DEFAULT 'user',
    username VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Events table
CREATE TABLE IF NOT EXISTS create_event (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    organizer_name VARCHAR(255) NOT NULL,
    event_title VARCHAR(255) NOT NULL,
    event_desc TEXT,
    category VARCHAR(100),
    eventtype ENUM('Single Participant', 'Team Event') DEFAULT 'Single Participant',
    min_team INT DEFAULT 0,
    max_team INT DEFAULT 0,
    event_rules TEXT,
    startdate DATE NOT NULL,
    enddate DATE NOT NULL,
    event_venue VARCHAR(255),
    time TIME,
    event_price DECIMAL(10,2) DEFAULT 0.00,
    event_thumbnail VARCHAR(255),
    event_sponsors TEXT,
    event_prizes TEXT,
    publish_event ENUM('yes', 'no') DEFAULT 'no',
    open_closed ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_organizer (organizer_name),
    INDEX idx_dates (startdate, enddate),
    INDEX idx_status (publish_event, open_closed)
);

-- Single event registrations table
CREATE TABLE IF NOT EXISTS singleevent_registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    roll_no VARCHAR(50),
    college VARCHAR(255),
    dept_name VARCHAR(100),
    email VARCHAR(255) NOT NULL,
    mobile_no VARCHAR(20),
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    paid_amount DECIMAL(10,2) DEFAULT 0.00,
    stripe_payment_id VARCHAR(255),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES create_event(event_id) ON DELETE CASCADE,
    INDEX idx_event (event_id),
    INDEX idx_payment (payment_status)
);

-- Team event registrations table
CREATE TABLE IF NOT EXISTS teamevent_registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    team_name VARCHAR(255) NOT NULL,
    team_leader_name VARCHAR(255) NOT NULL,
    team_leader_email VARCHAR(255) NOT NULL,
    team_leader_mobile VARCHAR(20),
    team_size INT NOT NULL,
    college VARCHAR(255),
    dept_name VARCHAR(100),
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    paid_amount DECIMAL(10,2) DEFAULT 0.00,
    stripe_payment_id VARCHAR(255),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES create_event(event_id) ON DELETE CASCADE,
    INDEX idx_event (event_id),
    INDEX idx_payment (payment_status)
);

-- Team members table
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_registration_id INT NOT NULL,
    member_name VARCHAR(255) NOT NULL,
    member_email VARCHAR(255) NOT NULL,
    member_roll_no VARCHAR(50),
    member_mobile VARCHAR(20),
    FOREIGN KEY (team_registration_id) REFERENCES teamevent_registration(id) ON DELETE CASCADE
);

-- Feedback table
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    feedback TEXT NOT NULL,
    stars INT DEFAULT 1 CHECK (stars >= 1 AND stars <= 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES create_event(event_id) ON DELETE CASCADE,
    INDEX idx_event (event_id),
    INDEX idx_rating (stars)
);

-- Gallery table
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    image_name VARCHAR(255) NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    caption TEXT,
    uploaded_by VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES create_event(event_id) ON DELETE SET NULL,
    INDEX idx_event (event_id)
);

-- Notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES create_event(event_id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_read_status (is_read)
);

-- Create default admin user (password: admin123)
INSERT INTO sign_up (role, username, full_name, email, password) VALUES
('admin', 'admin', 'Administrator', 'admin@eventmanagement.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE username = username;

-- Sample events data
INSERT INTO create_event (
    organizer_name, event_title, event_desc, category, eventtype,
    min_team, max_team, event_rules, startdate, enddate,
    event_venue, time, event_price, publish_event, open_closed
) VALUES
(
    'Administrator',
    'Sample Coding Competition',
    'A competitive programming event for students',
    'Technical',
    'Team Event',
    2,
    4,
    'Teams must have 2-4 members, Laptops required, No internet access during competition',
    DATE_ADD(CURDATE(), INTERVAL 30 DAY),
    DATE_ADD(CURDATE(), INTERVAL 30 DAY),
    'Computer Lab A',
    '10:00:00',
    50.00,
    'yes',
    'open'
),
(
    'Administrator',
    'Photography Workshop',
    'Learn the basics of digital photography',
    'Creative',
    'Single Participant',
    0,
    0,
    'Bring your own camera, Basic photography knowledge helpful',
    DATE_ADD(CURDATE(), INTERVAL 15 DAY),
    DATE_ADD(CURDATE(), INTERVAL 15 DAY),
    'Art Studio',
    '14:00:00',
    25.00,
    'yes',
    'open'
)
ON DUPLICATE KEY UPDATE event_title = event_title;