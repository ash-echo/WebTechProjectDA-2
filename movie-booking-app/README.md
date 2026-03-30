# AUTEUR Cinema - Movie Booking System

A modern, fully-functional movie booking web application built with PHP, MySQL, and Tailwind CSS. Features a complete booking flow from movie selection to payment confirmation.

## 🚀 Features

- **Complete Booking Flow**: Browse movies → Select showtimes → Choose seats → Payment → Confirmation
- **User Authentication**: Secure login/signup with session management
- **Real-time Seat Selection**: Dynamic seat map with locking mechanism to prevent double booking
- **Responsive Design**: Modern UI with Tailwind CSS and Material Symbols
- **Database Integration**: MySQL with proper relationships and constraints
- **Security**: Prepared statements, input sanitization, and session protection
- **XAMPP Compatible**: Ready to run on local XAMPP server

## 🛠️ Tech Stack

- **Backend**: PHP 7+
- **Database**: MySQL (via phpMyAdmin)
- **Frontend**: HTML5, Tailwind CSS, JavaScript
- **Icons**: Material Symbols
- **Server**: Apache (XAMPP)

## 📁 Project Structure

```
movie-booking-app/
├── api/                    # API endpoints
│   ├── lock_seats.php     # Seat locking for selection
│   ├── login.php          # User authentication
│   ├── process_payment.php # Payment processing
│   └── signup.php         # User registration
├── config/                # Configuration files
│   └── db.php            # Database connection
├── includes/             # Shared components
│   ├── auth.php          # Authentication functions
│   ├── functions.php     # Utility functions
│   ├── header.php        # Site header/navigation
│   └── footer.php        # Site footer
├── database_schema.sql   # Database schema and sample data
├── index.php            # Home page
├── movies.php           # Movie listing
├── movie_details.php    # Individual movie details
├── showtimes.php        # Theater/showtime selection
├── seat_selection.php   # Interactive seat selection
├── checkout.php         # Payment form
├── booking_confirmation.php # Booking confirmation
├── login.php            # Login page
├── signup.php           # Registration page
└── README.md           # This file
```

## 🚀 Quick Start

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- Web browser

### Installation

1. **Clone/Download the project** into your XAMPP htdocs folder:
   ```
   C:\xampp\htdocs\movie-booking-app\
   ```

2. **Start XAMPP** and ensure Apache and MySQL are running.

3. **Create the database**:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `movie_booking`
   - Import the `database_schema.sql` file

4. **Configure database connection**:
   - Open `config/db.php`
   - Update credentials if needed (default should work with XAMPP)

5. **Access the application**:
   - Open your browser and go to: `http://localhost/movie-booking-app/`

## 👤 Demo Credentials

- **Email**: demo@auteur.com
- **Password**: demo123

## 📊 Database Schema

The application uses a relational database with the following main tables:

- `users` - User accounts and authentication
- `movies` - Movie catalog with details
- `theaters` - Cinema locations
- `shows` - Movie screenings at specific theaters/times
- `seats` - Seat inventory for each theater
- `seat_locks` - Temporary seat reservations during booking
- `bookings` - Confirmed ticket purchases
- `booking_details` - Individual seats in each booking

## 🔐 Security Features

- Password hashing with bcrypt
- Prepared statements to prevent SQL injection
- Input sanitization and validation
- Session-based authentication
- CSRF protection on forms
- Seat locking to prevent race conditions

## 🎨 UI/UX Features

- **Responsive Design**: Works on desktop, tablet, and mobile
- **Dark Theme**: Modern cinema aesthetic
- **Interactive Seat Map**: Visual seat selection with real-time updates
- **Loading States**: Smooth transitions and feedback
- **Error Handling**: User-friendly error messages
- **Accessibility**: Proper semantic HTML and ARIA labels

## 🔧 Key Functionality

### Seat Selection System
- Real-time seat availability checking
- Temporary locking (10-minute expiry) during selection
- Prevents double booking with database transactions
- Visual feedback for available/selected/booked seats

### Booking Flow
1. **Browse Movies**: Grid layout with posters and ratings
2. **View Details**: Movie information with available showtimes
3. **Select Theater/Time**: Choose from available screenings
4. **Pick Seats**: Interactive seat map with pricing tiers
5. **Checkout**: Payment form with order summary
6. **Confirmation**: Booking details and digital receipt

### Authentication
- Secure registration with validation
- Login with remember-me option
- Session management with automatic logout
- Protected routes requiring authentication

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Ensure MySQL is running in XAMPP
   - Check credentials in `config/db.php`
   - Verify database name matches schema

2. **Page Not Loading**:
   - Check Apache is running
   - Verify file paths and permissions
   - Check PHP error logs

3. **Seat Selection Not Working**:
   - Ensure JavaScript is enabled
   - Check browser console for errors
   - Verify AJAX endpoints are accessible

4. **Login Issues**:
   - Use demo credentials: demo@auteur.com / demo123
   - Check session configuration
   - Clear browser cookies if needed

## 📝 Development Notes

- All database queries use PDO with prepared statements
- Functions are organized in `includes/functions.php`
- API endpoints return JSON responses
- Frontend uses vanilla JavaScript for interactivity
- CSS is utility-first with Tailwind classes

## 🤝 Contributing

This is a complete, production-ready application. For modifications:

1. Test all changes thoroughly
2. Maintain security best practices
3. Update documentation as needed
4. Ensure XAMPP compatibility

## 📄 License

This project is provided as-is for educational and demonstration purposes.

---

**Built with ❤️ for modern web development practices**