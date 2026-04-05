-- Movie Booking Database Schema - Enhanced Version
-- Run this in phpMyAdmin to create the database with complete mock data

CREATE DATABASE IF NOT EXISTS movie_booking;
USE movie_booking;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Movies table
CREATE TABLE IF NOT EXISTS movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    poster_url VARCHAR(500),
    backdrop_url VARCHAR(500),
    genre VARCHAR(255),
    format VARCHAR(50) DEFAULT 'Digital',
    release_date DATE,
    duration INT, -- in minutes
    rating DECIMAL(3,1),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Theaters table
CREATE TABLE IF NOT EXISTS theaters (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    capacity INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Shows table (movie screenings at specific theaters)
CREATE TABLE IF NOT EXISTS shows (
    id INT PRIMARY KEY AUTO_INCREMENT,
    movie_id INT NOT NULL REFERENCES movies(id),
    theater_id INT NOT NULL REFERENCES theaters(id),
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    format VARCHAR(50) DEFAULT 'Digital', -- 2D, 3D, IMAX, etc.
    price DECIMAL(8,2) NOT NULL
);

-- Seats table
CREATE TABLE IF NOT EXISTS seats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    theater_id INT NOT NULL REFERENCES theaters(id),
    seat_row VARCHAR(5) NOT NULL,
    seat_number INT NOT NULL,
    seat_type ENUM('PRIME', 'CLASSIC', 'VIP', 'DIRECTOR') DEFAULT 'CLASSIC',
    UNIQUE KEY unique_seat (theater_id, seat_row, seat_number)
);

-- Seat locks table (temporary reservations)
CREATE TABLE IF NOT EXISTS seat_locks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    seat_id INT NOT NULL REFERENCES seats(id),
    show_id INT NOT NULL,
    locked_by INT REFERENCES users(id),
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id VARCHAR(50) PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id),
    show_id INT NOT NULL REFERENCES shows(id),
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('confirmed', 'cancelled', 'refunded') DEFAULT 'confirmed'
);

-- Booking details table
CREATE TABLE IF NOT EXISTS booking_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id VARCHAR(50) NOT NULL REFERENCES bookings(id),
    seat_id INT NOT NULL REFERENCES seats(id),
    price DECIMAL(8,2) NOT NULL
);

-- =====================================
-- REBUILD TABLES FOR CLEAN STATE
-- =====================================
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE booking_details;
TRUNCATE TABLE bookings;
TRUNCATE TABLE seat_locks;
TRUNCATE TABLE seats;
TRUNCATE TABLE shows;
TRUNCATE TABLE theaters;
TRUNCATE TABLE movies;
SET FOREIGN_KEY_CHECKS = 1;


-- =====================================
-- SAMPLE DATA INJECTION
-- =====================================

-- Demo users (keeping existing if any, so we won't truncate users)
INSERT IGNORE INTO users (name, email, phone, password_hash, status) VALUES
('Demo User', 'demo@auteur.com', '+1-555-0123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');

-- Movies
INSERT INTO movies (title, description, poster_url, backdrop_url, genre, format, release_date, duration, rating) VALUES
('Leo', 'A cafe owner becomes a local hero, which draws the attention of a drug cartel who think he is their former member. An intense, stylistic action thriller that will leave you on the edge of your seat.', 'assets/img/posters/leo.jpg', 'assets/img/backdrops/leo.jpg', 'Action, Thriller', '4K Atmos • IMAX', '2023-10-19', 164, 7.9),
('Jailer', 'A retired jailer is forced to step back into the criminal underworld to avenge the alleged death of his honest police officer son. A high-octane vigilante story featuring the Superstar in an iconic role.', 'assets/img/posters/jailer.jpg', 'assets/img/backdrops/jailer.jpg', 'Action, Comedy, Crime', '4K Atmos', '2023-08-10', 168, 7.2),
('Vikram', 'A special investigator is assigned a case of serial killings, only to find the trail leading to a group of fearless heavily armed vigilantes and a drug syndicate.', 'assets/img/posters/vikram.jpg', 'assets/img/backdrops/vikram.jpg', 'Action, Thriller', 'Dolby Atmos', '2022-06-03', 175, 8.3),
('Dune: Part Two', 'Paul Atreides unites with Chani and the Fremen while on a warpath of revenge against the conspirators who destroyed his family. Facing a choice between the love of his life and the fate of the universe, he endeavors to prevent a terrible future only he can foresee.', 'assets/img/posters/dune.jpg', 'assets/img/backdrops/dune.jpg', 'Sci-Fi, Adventure', 'IMAX 70mm', '2024-03-01', 166, 8.8),
('Oppenheimer', 'The story of American scientist, J. Robert Oppenheimer, and his role in the development of the atomic bomb. A cinematic triumph exploring the delicate balance between genius, war, and the devastating cost of innovation.', 'assets/img/posters/oppenheimer.jpg', 'assets/img/backdrops/oppenheimer.jpg', 'Biography, Drama, History', 'IMAX 70mm', '2023-07-21', 180, 8.4),
('The Batman', 'When a sadistic serial killer begins murdering key political figures in Gotham, Batman is forced to investigate the city\'s hidden corruption and question his family\'s involvement.', 'assets/img/posters/batman.jpg', 'assets/img/backdrops/batman.jpg', 'Action, Crime, Drama', 'Dolby Vision', '2022-03-04', 176, 7.8);

-- Theaters
INSERT INTO theaters (id, name, location, capacity) VALUES
(1, 'IMAX Sphere Theater', '8th Avenue, Upper West Side, Manhattan', 300),
(2, 'Standard Dual-Aisle', '112 North 6th St, Williamsburg, Brooklyn', 150),
(3, 'VIP Velvet Lounge', '55 Wall Street, Financial District, Manhattan', 80),
(4, 'Director\'s Cut Studio', '1540 Broadway, Times Square, Manhattan', 50);

-- Insert Seats (Different maps per theater)
-- Theater 1: IMAX (300 seats) -> Curved stadium
INSERT INTO seats (theater_id, seat_row, seat_number, seat_type)
SELECT 1, row_name, seat_num, 'CLASSIC'
FROM (SELECT 'A' as row_name UNION SELECT 'B' UNION SELECT 'C' UNION SELECT 'D' UNION SELECT 'E' UNION SELECT 'F' UNION SELECT 'G' UNION SELECT 'H' UNION SELECT 'I' UNION SELECT 'J') r
CROSS JOIN (SELECT 1 as seat_num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25 UNION SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30) n;

-- Theater 2: Standard (150 Seats) -> Split block
INSERT INTO seats (theater_id, seat_row, seat_number, seat_type)
SELECT 2, row_name, seat_num, 'CLASSIC'
FROM (SELECT 'A' as row_name UNION SELECT 'B' UNION SELECT 'C' UNION SELECT 'D' UNION SELECT 'E' UNION SELECT 'F' UNION SELECT 'G' UNION SELECT 'H' UNION SELECT 'I' UNION SELECT 'J') r
CROSS JOIN (SELECT 1 as seat_num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15) n;

-- Theater 3: VIP Lounge (80 Seats) -> Heavily Spaced VIP
INSERT INTO seats (theater_id, seat_row, seat_number, seat_type)
SELECT 3, row_name, seat_num, 'VIP'
FROM (SELECT 'A' as row_name UNION SELECT 'B' UNION SELECT 'C' UNION SELECT 'D' UNION SELECT 'E' UNION SELECT 'F' UNION SELECT 'G' UNION SELECT 'H') r
CROSS JOIN (SELECT 1 as seat_num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10) n;

-- Theater 4: Director's Cut (50 Seats) -> Intimate
INSERT INTO seats (theater_id, seat_row, seat_number, seat_type)
SELECT 4, row_name, seat_num, 'DIRECTOR'
FROM (SELECT 'A' as row_name UNION SELECT 'B' UNION SELECT 'C' UNION SELECT 'D' UNION SELECT 'E') r
CROSS JOIN (SELECT 1 as seat_num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10) n;

-- Insert Shows dynamically across the next 7 days for multiple movies and theaters!
INSERT INTO shows (movie_id, theater_id, show_date, show_time, format, price) VALUES
-- Day 0 (Today)
(1, 1, CURDATE(), '10:00:00', '4K Atmos • IMAX', 24.00),
(1, 1, CURDATE(), '13:30:00', '4K Atmos • IMAX', 24.00),
(2, 2, CURDATE(), '14:00:00', 'Standard • Digital', 18.00),
(3, 3, CURDATE(), '18:00:00', 'Dolby Atmos', 30.00),
(4, 1, CURDATE(), '20:15:00', 'IMAX 70mm', 26.00),
(5, 4, CURDATE(), '19:00:00', 'Dolby Vision', 35.00),
(6, 2, CURDATE(), '21:00:00', 'Digital', 18.00),

-- Day 1 (Tomorrow)
(1, 2, CURDATE() + INTERVAL 1 DAY, '11:00:00', 'Standard • Digital', 18.00),
(2, 1, CURDATE() + INTERVAL 1 DAY, '13:30:00', '4K Atmos • IMAX', 24.00),
(3, 1, CURDATE() + INTERVAL 1 DAY, '17:00:00', '4K Atmos', 24.00),
(4, 3, CURDATE() + INTERVAL 1 DAY, '19:00:00', 'VIP Experience', 35.00),
(5, 1, CURDATE() + INTERVAL 1 DAY, '20:30:00', 'IMAX 70mm', 26.00),
(6, 4, CURDATE() + INTERVAL 1 DAY, '22:00:00', 'Director Cut Mode', 40.00),

-- Day 2 
(4, 1, CURDATE() + INTERVAL 2 DAY, '12:00:00', 'IMAX 70mm', 26.00),
(1, 3, CURDATE() + INTERVAL 2 DAY, '15:30:00', 'VIP Box', 35.00),
(2, 2, CURDATE() + INTERVAL 2 DAY, '18:00:00', 'Standard', 18.00),
(6, 1, CURDATE() + INTERVAL 2 DAY, '21:00:00', 'Dolby Vision', 26.00),

-- Day 3 
(5, 1, CURDATE() + INTERVAL 3 DAY, '11:30:00', 'IMAX 70mm', 26.00),
(3, 2, CURDATE() + INTERVAL 3 DAY, '14:45:00', 'Standard', 18.00),
(1, 1, CURDATE() + INTERVAL 3 DAY, '18:15:00', '4K Atmos', 24.00),
(4, 3, CURDATE() + INTERVAL 3 DAY, '21:30:00', 'VIP Experience', 35.00),

-- Day 4
(2, 1, CURDATE() + INTERVAL 4 DAY, '13:00:00', 'IMAX 70mm', 24.00),
(4, 1, CURDATE() + INTERVAL 4 DAY, '17:30:00', 'IMAX 70mm', 26.00),
(3, 3, CURDATE() + INTERVAL 4 DAY, '20:00:00', 'VIP Lounge', 35.00),
(5, 4, CURDATE() + INTERVAL 4 DAY, '21:30:00', 'Exclusive Viewing', 40.00),

-- Day 5
(1, 1, CURDATE() + INTERVAL 5 DAY, '10:30:00', 'IMAX 70mm', 24.00),
(6, 2, CURDATE() + INTERVAL 5 DAY, '13:30:00', 'Standard', 18.00),
(4, 1, CURDATE() + INTERVAL 5 DAY, '16:45:00', 'IMAX 70mm', 26.00),
(5, 3, CURDATE() + INTERVAL 5 DAY, '20:00:00', 'VIP Box', 35.00),

-- Day 6
(3, 1, CURDATE() + INTERVAL 6 DAY, '14:00:00', 'IMAX 70mm', 24.00),
(2, 4, CURDATE() + INTERVAL 6 DAY, '17:00:00', 'Boutique', 40.00),
(1, 3, CURDATE() + INTERVAL 6 DAY, '19:30:00', 'VIP Lounge', 35.00),
(4, 1, CURDATE() + INTERVAL 6 DAY, '21:45:00', 'IMAX 70mm', 26.00),

-- Day 7
(6, 1, CURDATE() + INTERVAL 7 DAY, '12:00:00', 'IMAX', 24.00),
(5, 1, CURDATE() + INTERVAL 7 DAY, '15:30:00', 'IMAX 70mm', 26.00),
(1, 2, CURDATE() + INTERVAL 7 DAY, '18:45:00', 'Standard', 18.00),
(4, 3, CURDATE() + INTERVAL 7 DAY, '21:00:00', 'VIP Lounge', 35.00);
