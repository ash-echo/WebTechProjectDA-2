<?php
require_once __DIR__ . '/../config/db.php';

// Get all movies
function getAllMovies() {
    try {
        $conn = getDBConnection();
        $stmt = $conn->query("SELECT * FROM movies ORDER BY release_date DESC");
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $movies;
    } catch (Exception $e) {
        error_log('Error getting movies: ' . $e->getMessage());
        return [];
    } finally {
        closeDBConnection($conn);
    }
}

// Get movie by ID
function getMovieById($id) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Error getting movie: ' . $e->getMessage());
        return null;
    } finally {
        closeDBConnection($conn);
    }
}

// Get shows for a movie on a specific date
function getShowsForMovie($movieId, $date = null) {
    if (!$date) {
        $date = date('Y-m-d');
    }

    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("
            SELECT s.*, t.name as theater_name, t.location
            FROM shows s
            JOIN theaters t ON s.theater_id = t.id
            WHERE s.movie_id = ? AND s.show_date = ?
            ORDER BY s.show_time ASC
        ");
        $stmt->execute([$movieId, $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Error getting shows: ' . $e->getMessage());
        return [];
    } finally {
        closeDBConnection($conn);
    }
}

// Get show details
function getShowById($showId) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("
            SELECT s.*, m.title, m.poster_url, m.format, m.duration, t.name as theater_name, t.location
            FROM shows s
            JOIN movies m ON s.movie_id = m.id
            JOIN theaters t ON s.theater_id = t.id
            WHERE s.id = ?
        ");
        $stmt->execute([$showId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Error getting show: ' . $e->getMessage());
        return null;
    } finally {
        closeDBConnection($conn);
    }
}

// Get seats for a theater
function getSeatsForTheater($theaterId) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM seats WHERE theater_id = ? ORDER BY seat_row DESC, seat_number ASC");
        $stmt->execute([$theaterId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Error getting seats: ' . $e->getMessage());
        return [];
    } finally {
        closeDBConnection($conn);
    }
}

// Get seat status for a show (available, booked, locked)
function getSeatStatusForShow($showId) {
    try {
        $conn = getDBConnection();
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Error getting seat status: ' . $e->getMessage());
        return [];
    } finally {
        closeDBConnection($conn);
    }
}

// Clean expired locks
function cleanExpiredLocks() {
    try {
        $conn = getDBConnection();
        $conn->query("DELETE FROM seat_locks WHERE expires_at < NOW()");
    } catch (Exception $e) {
        error_log('Error cleaning locks: ' . $e->getMessage());
    } finally {
        closeDBConnection($conn);
    }
}

// Generate booking ID
function generateBookingId() {
    return 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
}

// Get user by ID
function getUserById($userId) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, name, email, phone, status, created_at FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Error getting user: ' . $e->getMessage());
        return null;
    } finally {
        closeDBConnection($conn);
    }
}

// Get booking by ID
function getBookingById($bookingId) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("
            SELECT b.*, s.show_date, s.show_time, m.title, t.name as theater_name
            FROM bookings b
            JOIN shows s ON b.show_id = s.id
            JOIN movies m ON s.movie_id = m.id
            JOIN theaters t ON s.theater_id = t.id
            WHERE b.id = ?
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Error getting booking: ' . $e->getMessage());
        return null;
    } finally {
        closeDBConnection($conn);
    }
}

// Get booking details
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
    } catch (Exception $e) {
        error_log('Error getting booking details: ' . $e->getMessage());
        return [];
    } finally {
        closeDBConnection($conn);
    }
}

// Format currency
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

// Sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>