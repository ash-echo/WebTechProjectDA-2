-- Movie Booking Database Schema
-- Run this in phpMyAdmin to create the database

CREATE DATABASE IF NOT EXISTS movie_booking;
USE movie_booking;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Movies table
CREATE TABLE movies (
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
CREATE TABLE theaters (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    capacity INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Shows table (movie screenings at specific theaters)
CREATE TABLE shows (
    id INT PRIMARY KEY AUTO_INCREMENT,
    movie_id INT NOT NULL,
    theater_id INT NOT NULL,
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    format VARCHAR(50) DEFAULT 'Digital', -- 2D, 3D, IMAX, etc.
    price DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id),
    FOREIGN KEY (theater_id) REFERENCES theaters(id),
    UNIQUE KEY unique_show (movie_id, theater_id, show_date, show_time)
);

-- Seats table (seats for each theater)
CREATE TABLE seats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    theater_id INT NOT NULL,
    seat_row CHAR(1) NOT NULL,
    seat_number INT NOT NULL,
    seat_type VARCHAR(50) DEFAULT 'CLASSIC', -- PRIME, CLASSIC, etc.
    FOREIGN KEY (theater_id) REFERENCES theaters(id),
    UNIQUE KEY unique_seat (theater_id, seat_row, seat_number)
);

-- Seat locks table (temporary locks for seat selection)
CREATE TABLE seat_locks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    seat_id INT NOT NULL,
    show_id INT NOT NULL,
    locked_by INT,
    session_id VARCHAR(255),
    locked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    FOREIGN KEY (seat_id) REFERENCES seats(id),
    FOREIGN KEY (show_id) REFERENCES shows(id),
    FOREIGN KEY (locked_by) REFERENCES users(id)
);

-- Bookings table
CREATE TABLE bookings (
    id VARCHAR(20) PRIMARY KEY,
    user_id INT NOT NULL,
    show_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(8,2) NOT NULL,
    status ENUM('confirmed', 'cancelled', 'refunded') DEFAULT 'confirmed',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (show_id) REFERENCES shows(id)
);

-- Booking details table (seats in each booking)
CREATE TABLE booking_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id VARCHAR(20) NOT NULL,
    seat_id INT NOT NULL,
    price DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (seat_id) REFERENCES seats(id)
);

-- Sample data

-- Demo user
INSERT INTO users (name, email, phone, password_hash, status) VALUES
('Demo User', 'demo@auteur.com', '+1-555-0123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');

INSERT INTO movies (title, description, poster_url, genre, format, release_date, duration, rating) VALUES
('Youth', 'A visually stunning journey through time and memory, exploring the fragility of existence in a rapidly changing world.', 'https://images.unsplash.com/photo-1489599735734-79b4e62b8c2f?w=400', 'Comedy, Drama', '4K Atmos • IMAX', '2015-05-24', 124, 8.9),
('Oppenheimer', 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.', 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=400', 'Biography, Drama, History', '4K Atmos • IMAX', '2023-07-21', 180, 8.3),
('The Last Echo', 'A sci-fi thriller about time travel and consequences.', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=400', 'Sci-Fi, Thriller', 'Digital 3D', '2024-01-15', 135, 8.9),
('Rhythm of Silence', 'A musical drama about passion and perseverance.', 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400', 'Musical, Drama', 'Dolby Atmos', '2024-03-10', 142, 9.2),
('Obsidian', 'A mystery horror film set in abandoned mines.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400', 'Mystery, Horror', 'Digital', '2024-02-20', 118, 8.5),
('Beyond the Clouds', 'An animated adventure for all ages.', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=400', 'Animation, Adventure', 'Digital 3D', '2024-04-05', 105, 8.7),
('Velocity X', 'High-octane action with car chases and explosions.', 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=400', 'Action, Crime', '4K Atmos', '2024-05-12', 148, 9.0);

INSERT INTO theaters (name, location, capacity) VALUES
('The Vijay Park Multiplex', '8th Avenue, Upper West Side, Manhattan', 300),
('Cinema Art House Brooklyn', '112 North 6th St, Brooklyn', 150),
('Downtown Premiere Theater', 'Wall Street, Financial District', 250),
('Grand Premiere Hall', 'Times Square, Manhattan', 400);
-- Sample data

-- Demo user
INSERT INTO users (name, email, phone, password_hash, status) VALUES
('Demo User', 'demo@auteur.com', '+1-555-0123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');

INSERT INTO movies (title, description, poster_url, genre, format, release_date, duration, rating) VALUES
('Youth', 'A visually stunning journey through time and memory, exploring the fragility of existence in a rapidly changing world.', 'https://images.unsplash.com/photo-1489599735734-79b4e62b8c2f?w=400', 'Comedy, Drama', '4K Atmos • IMAX', '2015-05-24', 124, 8.9),
('Oppenheimer', 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.', 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=400', 'Biography, Drama, History', '4K Atmos • IMAX', '2023-07-21', 180, 8.3),
('The Last Echo', 'A sci-fi thriller about time travel and consequences.', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=400', 'Sci-Fi, Thriller', 'Digital 3D', '2024-01-15', 135, 8.9),
('Rhythm of Silence', 'A musical drama about passion and perseverance.', 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400', 'Musical, Drama', 'Dolby Atmos', '2024-03-10', 142, 9.2),
('Obsidian', 'A mystery horror film set in abandoned mines.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400', 'Mystery, Horror', 'Digital', '2024-02-20', 118, 8.5),
('Beyond the Clouds', 'An animated adventure for all ages.', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=400', 'Animation, Adventure', 'Digital 3D', '2024-04-05', 105, 8.7),
('Velocity X', 'High-octane action with car chases and explosions.', 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=400', 'Action, Crime', '4K Atmos', '2024-05-12', 148, 9.0);

INSERT INTO theaters (name, location, capacity) VALUES
('The Vijay Park Multiplex', '8th Avenue, Upper West Side, Manhattan', 300),
('Cinema Art House Brooklyn', '112 North 6th St, Brooklyn', 150),
('Downtown Premiere Theater', 'Wall Street, Financial District', 250),
('Grand Premiere Hall', 'Times Square, Manhattan', 400);

-- Insert seats for theater 1 (300 seats)
INSERT INTO seats (theater_id, seat_row, seat_number, seat_type) VALUES
-- PRIME tier (rows G-F, higher price)
(1, 'G', 1, 'PRIME'), (1, 'G', 2, 'PRIME'), (1, 'G', 3, 'PRIME'), (1, 'G', 4, 'PRIME'), (1, 'G', 5, 'PRIME'), (1, 'G', 6, 'PRIME'), (1, 'G', 7, 'PRIME'), (1, 'G', 8, 'PRIME'), (1, 'G', 9, 'PRIME'), (1, 'G', 10, 'PRIME'),
(1, 'F', 1, 'PRIME'), (1, 'F', 2, 'PRIME'), (1, 'F', 3, 'PRIME'), (1, 'F', 4, 'PRIME'), (1, 'F', 5, 'PRIME'), (1, 'F', 6, 'PRIME'), (1, 'F', 7, 'PRIME'), (1, 'F', 8, 'PRIME'), (1, 'F', 9, 'PRIME'), (1, 'F', 10, 'PRIME'),
-- CLASSIC tier (rows E-A, standard price)
(1, 'E', 1, 'CLASSIC'), (1, 'E', 2, 'CLASSIC'), (1, 'E', 3, 'CLASSIC'), (1, 'E', 4, 'CLASSIC'), (1, 'E', 5, 'CLASSIC'), (1, 'E', 6, 'CLASSIC'), (1, 'E', 7, 'CLASSIC'), (1, 'E', 8, 'CLASSIC'), (1, 'E', 9, 'CLASSIC'), (1, 'E', 10, 'CLASSIC'),
(1, 'D', 1, 'CLASSIC'), (1, 'D', 2, 'CLASSIC'), (1, 'D', 3, 'CLASSIC'), (1, 'D', 4, 'CLASSIC'), (1, 'D', 5, 'CLASSIC'), (1, 'D', 6, 'CLASSIC'), (1, 'D', 7, 'CLASSIC'), (1, 'D', 8, 'CLASSIC'), (1, 'D', 9, 'CLASSIC'), (1, 'D', 10, 'CLASSIC'),
(1, 'C', 1, 'CLASSIC'), (1, 'C', 2, 'CLASSIC'), (1, 'C', 3, 'CLASSIC'), (1, 'C', 4, 'CLASSIC'), (1, 'C', 5, 'CLASSIC'), (1, 'C', 6, 'CLASSIC'), (1, 'C', 7, 'CLASSIC'), (1, 'C', 8, 'CLASSIC'), (1, 'C', 9, 'CLASSIC'), (1, 'C', 10, 'CLASSIC'),
(1, 'B', 1, 'CLASSIC'), (1, 'B', 2, 'CLASSIC'), (1, 'B', 3, 'CLASSIC'), (1, 'B', 4, 'CLASSIC'), (1, 'B', 5, 'CLASSIC'), (1, 'B', 6, 'CLASSIC'), (1, 'B', 7, 'CLASSIC'), (1, 'B', 8, 'CLASSIC'), (1, 'B', 9, 'CLASSIC'), (1, 'B', 10, 'CLASSIC'),
(1, 'A', 1, 'CLASSIC'), (1, 'A', 2, 'CLASSIC'), (1, 'A', 3, 'CLASSIC'), (1, 'A', 4, 'CLASSIC'), (1, 'A', 5, 'CLASSIC'), (1, 'A', 6, 'CLASSIC'), (1, 'A', 7, 'CLASSIC'), (1, 'A', 8, 'CLASSIC'), (1, 'A', 9, 'CLASSIC'), (1, 'A', 10, 'CLASSIC');

-- Insert shows
INSERT INTO shows (movie_id, theater_id, show_date, show_time, format, price) VALUES
(1, 1, CURDATE(), '18:30:00', '4K Atmos • IMAX', 24.00),
(1, 1, CURDATE(), '20:15:00', 'Standard • Digital', 18.00),
(1, 1, CURDATE(), '21:30:00', '4K Atmos • IMAX', 24.00),
(1, 1, CURDATE(), '22:00:00', 'Digital • 7.1', 20.00),
(1, 1, CURDATE(), '23:15:00', 'IMAX 3D', 26.00),
(1, 2, CURDATE(), '17:45:00', 'Director\'s Cut', 22.00),
(1, 2, CURDATE(), '21:00:00', '35mm Projection', 25.00),
(2, 1, CURDATE(), '19:00:00', '4K Atmos • IMAX', 24.00),
(2, 1, CURDATE(), '21:45:00', 'Digital 3D', 22.00),
(3, 3, CURDATE(), '20:00:00', 'Digital 3D', 20.00),
(4, 4, CURDATE(), '18:00:00', 'Dolby Atmos', 22.00),
(5, 2, CURDATE(), '22:30:00', 'Digital', 18.00),
(6, 1, CURDATE() + INTERVAL 1 DAY, '14:00:00', 'Digital 3D', 16.00),
(7, 3, CURDATE() + INTERVAL 1 DAY, '19:30:00', '4K Atmos', 24.00);