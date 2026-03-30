-- Movie Booking Database Schema - Enhanced Version
-- Run this in phpMyAdmin to create the database with complete mock data

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
    movie_id INT NOT NULL REFERENCES movies(id),
    theater_id INT NOT NULL REFERENCES theaters(id),
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    format VARCHAR(50) DEFAULT 'Digital', -- 2D, 3D, IMAX, etc.
    price DECIMAL(8,2) NOT NULL
);

-- Seats table
CREATE TABLE seats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    theater_id INT NOT NULL REFERENCES theaters(id),
    seat_row VARCHAR(5) NOT NULL,
    seat_number INT NOT NULL,
    seat_type ENUM('PRIME', 'CLASSIC') DEFAULT 'CLASSIC',
    UNIQUE KEY unique_seat (theater_id, seat_row, seat_number)
);

-- Seat locks table (temporary reservations)
CREATE TABLE seat_locks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    seat_id INT NOT NULL REFERENCES seats(id),
    user_id INT REFERENCES users(id),
    locked_until TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE bookings (
    id VARCHAR(50) PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id),
    show_id INT NOT NULL REFERENCES shows(id),
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('confirmed', 'cancelled', 'refunded') DEFAULT 'confirmed'
);

-- Booking details table
CREATE TABLE booking_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id VARCHAR(50) NOT NULL REFERENCES bookings(id),
    seat_id INT NOT NULL REFERENCES seats(id),
    price DECIMAL(8,2) NOT NULL
);

-- Sample data

-- Demo users
INSERT INTO users (name, email, phone, password_hash, status) VALUES
('Demo User', 'demo@auteur.com', '+1-555-0123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('John Smith', 'john@example.com', '+1-555-0456', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('Sarah Johnson', 'sarah@example.com', '+1-555-0789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');

-- Enhanced movies with detailed descriptions and high-quality images
INSERT INTO movies (title, description, poster_url, backdrop_url, genre, format, release_date, duration, rating) VALUES
('Youth', 'A visually stunning journey through time and memory, exploring the fragility of existence in a rapidly changing world. Follow a group of lifelong friends as they navigate the complexities of growing older while desperately holding onto the dreams and passions of their youth. A cinematic masterpiece that captures the bittersweet beauty of human connection and the relentless passage of time.', 'https://images.unsplash.com/photo-1489599735734-79b4e62b8c2f?w=400&h=600&fit=crop', 'https://images.unsplash.com/photo-1489599735734-79b4e62b8c2f?w=1920&h=1080&fit=crop', 'Comedy, Drama', '4K Atmos • IMAX', '2015-05-24', 124, 8.9),

('Oppenheimer', 'The definitive story of American scientist J. Robert Oppenheimer and his pivotal role in the development of the atomic bomb during World War II. A gripping biographical thriller that explores the moral complexities of scientific discovery, the devastating consequences of innovation, and the heavy burden of genius. Winner of multiple Academy Awards.', 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=400&h=600&fit=crop', 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=1920&h=1080&fit=crop', 'Biography, Drama, History', '4K Atmos • IMAX', '2023-07-21', 180, 8.3),

('The Last Echo', 'A mind-bending sci-fi thriller about time travel, quantum entanglement, and the devastating consequences of altering reality. When a brilliant physicist discovers a way to communicate with his deceased wife through quantum echoes, he must confront the terrifying ripple effects of changing the past. A cerebral journey through grief, love, and the nature of time itself.', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=400&h=600&fit=crop', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=1920&h=1080&fit=crop', 'Sci-Fi, Thriller', 'Digital 3D', '2024-01-15', 135, 8.9),

('Rhythm of Silence', 'A soul-stirring musical drama about passion, perseverance, and the transformative power of music. Follow an aspiring composer who discovers that the most beautiful melodies emerge not from sound, but from the sacred spaces between the notes. A heartfelt exploration of love, loss, creativity, and the healing power of silence in an increasingly noisy world.', 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400&h=600&fit=crop', 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=1920&h=1080&fit=crop', 'Musical, Drama', 'Dolby Atmos', '2024-03-10', 142, 9.2),

('Obsidian', 'A claustrophobic mystery horror film set deep within abandoned obsidian mines. When a team of miners unearths an ancient artifact that awakens something primordial, paranoia spreads like wildfire. As they descend deeper into madness, they must confront both the external horrors lurking in the darkness and the terrifying demons within their own minds.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=600&fit=crop', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1920&h=1080&fit=crop', 'Mystery, Horror', 'Digital', '2024-02-20', 118, 8.5),

('Beyond the Clouds', 'A whimsical animated adventure for all ages about friendship, courage, and seeing the world from different perspectives. When a curious young cloud dreams of exploring the world below, she embarks on an epic journey that teaches her about bravery, loyalty, and the beauty of embracing differences. A heartwarming tale that reminds us all that home is where the heart is.', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=400&h=600&fit=crop', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=1920&h=1080&fit=crop', 'Animation, Adventure', 'Digital 3D', '2024-04-05', 105, 8.7),

('Velocity X', 'High-octane action thriller with heart-pounding car chases, explosive stunts, and edge-of-your-seat suspense. A former Formula 1 champion is pulled back into the dangerous world of underground street racing when his daughter mysteriously disappears. Every turn brings him closer to the truth, but also closer to his own destruction in this adrenaline-fueled ride.', 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=400&h=600&fit=crop', 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=1920&h=1080&fit=crop', 'Action, Crime', '4K Atmos', '2024-05-12', 148, 9.0);

-- Theaters with detailed locations
INSERT INTO theaters (name, location, capacity) VALUES
('The Vijay Park Multiplex', '8th Avenue, Upper West Side, Manhattan, NY 10024', 300),
('Cinema Art House Brooklyn', '112 North 6th St, Williamsburg, Brooklyn, NY 11249', 150),
('Downtown Premiere Theater', '55 Wall Street, Financial District, Manhattan, NY 10005', 250),
('Grand Premiere Hall', '1540 Broadway, Times Square, Manhattan, NY 10036', 400);

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

-- Insert shows with multiple time slots
INSERT INTO shows (movie_id, theater_id, show_date, show_time, format, price) VALUES
-- Today shows
(1, 1, CURDATE(), '10:00:00', '4K Atmos • IMAX', 24.00),
(1, 1, CURDATE(), '13:30:00', 'Standard • Digital', 18.00),
(1, 1, CURDATE(), '17:00:00', '4K Atmos • IMAX', 24.00),
(1, 1, CURDATE(), '20:15:00', 'Digital • 7.1', 20.00),
(1, 1, CURDATE(), '22:45:00', 'IMAX 3D', 26.00),

(2, 1, CURDATE(), '11:30:00', '4K Atmos • IMAX', 24.00),
(2, 1, CURDATE(), '15:00:00', 'Digital 3D', 22.00),
(2, 1, CURDATE(), '18:30:00', '4K Atmos • IMAX', 24.00),
(2, 1, CURDATE(), '21:45:00', 'Digital 3D', 22.00),

(3, 2, CURDATE(), '12:00:00', 'Digital 3D', 20.00),
(3, 2, CURDATE(), '15:30:00', 'Digital 3D', 20.00),
(3, 2, CURDATE(), '19:00:00', 'Digital 3D', 20.00),
(3, 2, CURDATE(), '22:00:00', 'Digital 3D', 20.00),

(4, 3, CURDATE(), '11:00:00', 'Dolby Atmos', 22.00),
(4, 3, CURDATE(), '14:30:00', 'Dolby Atmos', 22.00),
(4, 3, CURDATE(), '18:00:00', 'Dolby Atmos', 22.00),
(4, 3, CURDATE(), '21:15:00', 'Dolby Atmos', 22.00),

(5, 2, CURDATE(), '13:00:00', 'Digital', 18.00),
(5, 2, CURDATE(), '16:30:00', 'Digital', 18.00),
(5, 2, CURDATE(), '20:00:00', 'Digital', 18.00),
(5, 2, CURDATE(), '23:00:00', 'Digital', 18.00),

(6, 4, CURDATE(), '10:30:00', 'Digital 3D', 16.00),
(6, 4, CURDATE(), '13:00:00', 'Digital 3D', 16.00),
(6, 4, CURDATE(), '15:30:00', 'Digital 3D', 16.00),
(6, 4, CURDATE(), '18:00:00', 'Digital 3D', 16.00),

(7, 3, CURDATE(), '12:30:00', '4K Atmos', 24.00),
(7, 3, CURDATE(), '16:00:00', '4K Atmos', 24.00),
(7, 3, CURDATE(), '19:30:00', '4K Atmos', 24.00),
(7, 3, CURDATE(), '22:30:00', '4K Atmos', 24.00),

-- Tomorrow shows
(1, 1, CURDATE() + INTERVAL 1 DAY, '11:00:00', '4K Atmos • IMAX', 24.00),
(2, 2, CURDATE() + INTERVAL 1 DAY, '14:00:00', 'Digital 3D', 22.00),
(3, 3, CURDATE() + INTERVAL 1 DAY, '16:30:00', 'Digital 3D', 20.00),
(4, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00', 'Dolby Atmos', 22.00),
(5, 1, CURDATE() + INTERVAL 1 DAY, '21:30:00', 'Digital', 18.00),
(6, 2, CURDATE() + INTERVAL 1 DAY, '10:00:00', 'Digital 3D', 16.00),
(7, 4, CURDATE() + INTERVAL 1 DAY, '15:00:00', '4K Atmos', 24.00),

-- Day after tomorrow
(1, 2, CURDATE() + INTERVAL 2 DAY, '13:00:00', 'Director\'s Cut', 22.00),
(2, 3, CURDATE() + INTERVAL 2 DAY, '16:00:00', '35mm Projection', 25.00),
(3, 4, CURDATE() + INTERVAL 2 DAY, '19:30:00', 'Digital 3D', 20.00),
(4, 1, CURDATE() + INTERVAL 2 DAY, '22:00:00', 'Dolby Atmos', 22.00);

