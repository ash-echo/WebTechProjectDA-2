# 🎬 CINEFLOW: Project Technical Overview & PPT Guide

This document is your master reference for presenting **CineFlow**. It breaks down the technical architecture, core functionalities, and the marriage between PHP and MySQL.

---

## 🏗️ Slide 1-2: Project Introduction & Branding
- **Project Name**: CineFlow - Cinematic Discovery & Digital Premiere.
- **Problem Statement**: Traditional movie booking sites are often cluttered and lack a "Digital Premiere" alternative for home viewing.
- **The Vision**: A premium, "glassmorphic" web application that bridges the gap between theatrical booking and digital rentals.
- **Key Visuals**: Show the Home Page with the "CineFlow Premiere" section and the Hero Carousel.

---

## 🛠️ Slide 3: The Tech Stack (The "How It Works")
- **Frontend**: 
    - **Vanilla CSS**: Custom design system using Flexbox/Grid. No bloated frameworks.
    - **Glassmorphism**: Backdrop filters and high-contrast color palettes (Red/Black/Surface).
    - **JS (ES6)**: Real-time UI updates, Local Storage for Favorites, and Trailer Modals.
- **Backend (PHP 8.x)**:
    - **MVC-ish Logic**: Clear separation between `includes/` (logic), `api/` (data), and `pages/` (view).
    - **PDO (PHP Data Objects)**: Secure database interactions using prepared statements to prevent SQL Injection.
- **Database (MySQL)**:
    - Relational schema hosted via XAMPP.

---

## 🔒 Slide 4: Security & Authentication (PHP + SMTP)
**Functionality**:
- **Secure Auth**: Password Hashing using `password_hash()`.
- **The OTP System**:
    - Uses **SMTP (Simple Mail Transfer Protocol)** via Brevo/Gmail to send a 6-digit One Time Password.
    - **PHP Integration**: `mail_helper.php` handles connection and delivery.
    - **Logic**: OTP is generated in the `api/signup.php` and verified before a user can log in.
- **Password Recovery**: A full "Forgot Password" flow that validates user identity via a temporary token/OTP.

---

## 🔍 Slide 5: Discovery & Search (SQL Logic)
**Functionality**:
- **Global Search**: A functional search bar in the header.
- **PHP/SQL Implementation**:
    - Uses the `LIKE '%search%'` operator.
    - **Smart Redirection**: If on the rentals page, it filters specifically for rentals (`is_rentable = 1`).
- **Unified Filtering**:
    - Multi-criteria filtering (Genre, Format, Search Term) implemented in a single `getAllMovies()` PHP function.

---

## 🎫 Slide 6: The Booking Engine (Advanced Logic)
**Functionality**:
- **Showtime Selection**: Dynamic showtimes pulled from the `shows` table.
- **Deterministic Occupancy Simulation**: 
    - **The Magic**: Since it's a demo, we use a seed (`mt_srand(show_id)`) to randomly fill 30-70% of seats statically.
    - **Usecase**: "Fast Filling" badges appear automatically to create urgency.
    - **Logic**: Users cannot book seats that the simulation has "taken."

---

## 🍿 Slide 7: CineFlow Premiere (The Business Aspect)
**Functionality**:
- **Digital Rentals**: A secondary store for digital ownership (₹199 price point).
- **Checkout system**: A streamlined checkout flow with stored-card simulation.
- **Database Tracking**: `movie_rentals` table tracks purchase time and a 48-hour expiration window.
- **Access Control**: Users can only access `watch.php` if a valid rental record is found in the database.

---

## 🎥 Slide 8: The Cinema Player (`watch.php`)
**Tech Details**:
- **HTML5 Player**: Optimized for high-bitrate `.mp4` video.
- **Dynamic File Mapping**: PHP maps the Movie ID to its asset (e.g., ID 6 ➡️ `Batman.mp4`).
- **Interactive Controls**: 
    - Custom play/pause overlays.
    - Keyboard shortcut support (Spacebar integration).

---

## 📊 Slide 9: Database Architecture (Relations)
**Core Tables**:
1. `users`: Stores profiles, hashed passwords, and verification status.
2. `movies`: Central catalog (Title, Description, Trailers, Rental Price).
3. `shows`: Links movies to theaters, dates, and times.
4. `theaters`: Venue details and layouts.
5. `bookings`: Parent table for ticket purchases.
6. `movie_rentals`: Tracks digital purchases and expiration.

---

## 🚀 Slide 10: Summary & Conclusion
- **Scalability**: The database is ready for hundreds of movies and thousands of users.
- **Performance**: High use of image optimization and minimalist CSS/JS for fast load times.
- **Final Note**: "CineFlow isn't just a booking site; it's a complete entertainment platform built with modern web security and premium UX at its heart."

---

> [!TIP]
> **Presentation Tip**: Open the website live and search for "Batman". Then click "Watch Now" to show the transition between the store and the player. This usually "wows" the jury!
