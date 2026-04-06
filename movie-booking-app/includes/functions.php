<?php
require_once __DIR__ . '/../config/db.php';
date_default_timezone_set('Asia/Kolkata');


// Quick migration patch to ensure DB handles locks correctly
try {
    $conn = getDBConnection();
    // Check if show_id exists in seat_locks
    $stmt = $conn->query("SHOW COLUMNS FROM seat_locks LIKE 'show_id'");
    if ($stmt->rowCount() == 0) {
        $conn->exec("ALTER TABLE seat_locks ADD COLUMN show_id INT NOT NULL AFTER seat_id");
        $conn->exec("ALTER TABLE seat_locks CHANGE user_id locked_by INT");
        $conn->exec("ALTER TABLE seat_locks CHANGE locked_until expires_at TIMESTAMP");
    }
} catch (Exception $e) {
    // Ignore if table doesn't exist yet
}

// Get movies with optional filtering, sorting, and search
function getAllMovies($genre = null, $format = null, $sort = 'newest', $searchTerm = null) {
    try {
        $conn = getDBConnection();
        $query = "SELECT * FROM movies WHERE 1=1";
        $params = [];

        if ($genre && $genre !== 'All') {
            $query .= " AND genre LIKE ?";
            $params[] = "%$genre%";
        }
        if ($format && $format !== 'All') {
            $query .= " AND format = ?";
            $params[] = $format;
        }
        if ($searchTerm) {
            $query .= " AND title LIKE ?";
            $params[] = "%$searchTerm%";
        }

        switch ($sort) {
            case 'rating': $query .= " ORDER BY rating DESC"; break;
            case 'alpha':  $query .= " ORDER BY title ASC"; break;
            case 'newest': 
            default:       $query .= " ORDER BY release_date DESC"; break;
        }

        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return []; }
}

// Get movie by ID
function getMovieById($id) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return null; }
}

// Get shows for a movie on a specific date with optional filters
function getShowsForMovie($movieId, $date = null, $format = null, $timeSlot = null) {
    if (!$date) $date = date('Y-m-d');
    try {
        $conn = getDBConnection();
        $query = "
            SELECT s.*, t.name as theater_name, t.location
            FROM shows s JOIN theaters t ON s.theater_id = t.id
            WHERE s.movie_id = ? AND s.show_date = ?
        ";
        $params = [$movieId, $date];

        if ($format && $format !== 'All') {
            $query .= " AND s.format = ?";
            $params[] = $format;
        }

        if ($timeSlot && $timeSlot !== 'All') {
            switch($timeSlot) {
                case 'Morning':   $query .= " AND HOUR(s.show_time) BETWEEN 6 AND 11"; break;
                case 'Afternoon': $query .= " AND HOUR(s.show_time) BETWEEN 12 AND 16"; break;
                case 'Evening':   $query .= " AND HOUR(s.show_time) BETWEEN 17 AND 20"; break;
                case 'Night':     $query .= " AND (HOUR(s.show_time) >= 21 OR HOUR(s.show_time) < 6)"; break;
            }
        }

        $query .= " ORDER BY s.show_time ASC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return []; }
}

// Get show details
function getShowById($showId) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("
            SELECT s.*, m.title, m.poster_url, m.format as movie_format, m.duration, t.name as theater_name, t.location
            FROM shows s JOIN movies m ON s.movie_id = m.id JOIN theaters t ON s.theater_id = t.id
            WHERE s.id = ?
        ");
        $stmt->execute([$showId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return null; }
}

// Get predicted occupancy percentage for a show (Deterministic Random)
function getShowOccupancy($showId) {
    // We use the show_id as a seed so it's consistent on refresh
    mt_srand($showId);
    // Return a percentage between 15% and 75%
    return mt_rand(15, 75);
}

// Get seat status for a show with simulated pre-filling
function getSeatStatusForShow($showId) {
    try {
        $conn = getDBConnection();
        $occupancyPercent = getShowOccupancy($showId);
        
        $stmt = $conn->prepare("
            SELECT s.id, s.seat_row, s.seat_number, s.seat_type,
                   CASE
                       WHEN b.id IS NOT NULL THEN 'booked'
                       WHEN sl.id IS NOT NULL AND sl.expires_at > NOW() THEN 'locked'
                       ELSE 'available'
                   END as status,
                   sl.locked_by, sl.expires_at as locked_until
            FROM seats s
            JOIN shows sh ON s.theater_id = sh.theater_id
            LEFT JOIN booking_details bd ON s.id = bd.seat_id
            LEFT JOIN bookings b ON bd.booking_id = b.id AND b.show_id = sh.id AND b.status = 'confirmed'
            LEFT JOIN seat_locks sl ON s.id = sl.seat_id AND sl.show_id = sh.id AND sl.expires_at > NOW()
            WHERE sh.id = ?
            ORDER BY s.seat_row DESC, s.seat_number ASC
        ");
        $stmt->execute([$showId]);
        $seats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Re-seed with showId to ensure the seat selection is also deterministic
        mt_srand($showId);
        foreach ($seats as &$seat) {
            if ($seat['status'] === 'available') {
                // If the random number is within our occupancy threshold, mark as booked
                if (mt_rand(1, 100) <= $occupancyPercent) {
                    $seat['status'] = 'booked';
                    $seat['is_simulated'] = true;
                }
            }
        }
        
        return $seats;
    } catch (Exception $e) { return []; }
}

// Clean expired locks
function cleanExpiredLocks() {
    try {
        $conn = getDBConnection();
        $conn->query("DELETE FROM seat_locks WHERE expires_at < NOW()");
    } catch (Exception $e) {}
}

// Format currency
function formatCurrency($amount) {
    return '₹' . number_format($amount, 0); // User wants 267 (no decimals needed for flat INR)
}

// Get user by ID
function getUserById($userId) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, name, email, phone, status, created_at FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return null; }
}

// Get Booking
function getBookingById($bookingId) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("
            SELECT b.*, s.show_date, s.show_time, m.title, t.name as theater_name, m.poster_url as movie_poster
            FROM bookings b
            JOIN shows s ON b.show_id = s.id
            JOIN movies m ON s.movie_id = m.id
            JOIN theaters t ON s.theater_id = t.id
            WHERE b.id = ?
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return null; }
}

// Get Booking details
function getBookingDetails($bookingId) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("
            SELECT bd.*, s.seat_row, s.seat_number, s.seat_type
            FROM booking_details bd
            JOIN seats s ON bd.seat_id = s.id
            WHERE bd.booking_id = ?
            ORDER BY s.seat_row, s.seat_number
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return []; }
}

// Generate unique booking ID
function generateBookingId() {
    return 'BK-' . strtoupper(substr(uniqid(), -6)) . rand(10, 99);
}
// Get all rentable movies with optional search
function getRentableMovies($searchTerm = null) {
    try {
        $conn = getDBConnection();
        $query = "SELECT * FROM movies WHERE is_rentable = 1";
        $params = [];
        
        if ($searchTerm) {
            $query .= " AND title LIKE ?";
            $params[] = "%$searchTerm%";
        }
        
        $query .= " ORDER BY title ASC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return []; }
}

// Check if a user has rented a specific movie
function isMovieRented($movieId, $userId) {
    if (!$userId) return false;
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id FROM movie_rentals WHERE movie_id = ? AND user_id = ? AND status = 'active' AND (expires_at IS NULL OR expires_at > NOW())");
        $stmt->execute([$movieId, $userId]);
        return (bool)$stmt->fetch();
    } catch (Exception $e) { return false; }
}

// Get all active rentals for a user
function getUserRentals($userId) {
    if (!$userId) return [];
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("
            SELECT r.*, m.title, m.poster_url, m.backdrop_url, m.duration, m.genre
            FROM movie_rentals r
            JOIN movies m ON r.movie_id = m.id
            WHERE r.user_id = ? AND r.status = 'active' AND (r.expires_at IS NULL OR r.expires_at > NOW())
            ORDER BY r.rented_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return []; }
}

// Process a move rental
function rentMovie($movieId, $userId) {
    try {
        $conn = getDBConnection();
        // Standard rental: 48 hours
        $stmt = $conn->prepare("INSERT INTO movie_rentals (user_id, movie_id, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 48 HOUR))");
        return $stmt->execute([$userId, $movieId]);
    } catch (Exception $e) { 
        error_log("Rental Error: " . $e->getMessage());
        return false; 
    }
}
?>