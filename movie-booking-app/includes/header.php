<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Ensure BASE_URL is defined
if (!defined('BASE_URL')) {
    define('BASE_URL', '/WebTechProject/movie-booking-app/');
}
?>
<!DOCTYPE html>
<html class="dark scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $pageTitle ?? 'CINEFLOW | Cinematic Discovery'; ?></title>
    <!-- Tailwind CSS with container queries -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL,GRAD,opsz@100..700,0..1,-50..200,20..48&display=swap" rel="stylesheet"/>
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-secondary": "#690000",
                        "on-primary-fixed": "#410000",
                        "on-primary": "#690000",
                        "on-tertiary-fixed": "#001b3e",
                        "secondary-fixed-dim": "#ffb4a8",
                        "secondary-fixed": "#ffdad4",
                        "surface": "#101010",
                        "inverse-surface": "#e5e2e1",
                        "surface-dim": "#131313",
                        "on-primary-container": "#fffdff",
                        "surface-container-lowest": "#0a0a0a",
                        "on-surface-variant": "#e8bcb5",
                        "surface-container": "#181818",
                        "on-tertiary-container": "#fffcff",
                        "tertiary-container": "#0073e2",
                        "inverse-on-surface": "#313030",
                        "surface-container-low": "#141414",
                        "secondary-container": "#90190f",
                        "on-secondary-container": "#ff9f90",
                        "on-background": "#e5e2e1",
                        "tertiary-fixed": "#d6e3ff",
                        "on-surface": "#eee",
                        "surface-container-highest": "#333",
                        "inverse-primary": "#c00000",
                        "surface-container-high": "#222",
                        "surface-bright": "#393939",
                        "on-secondary-fixed": "#410000",
                        "primary-fixed-dim": "#ffb4a8",
                        "on-error-container": "#ffdad6",
                        "background": "#0f0f0f",
                        "surface-tint": "#ffb4a8",
                        "on-error": "#690005",
                        "on-secondary-fixed-variant": "#8c160d",
                        "outline-variant": "#444",
                        "error-container": "#93000a",
                        "primary-fixed": "#ffdad4",
                        "primary-container": "#e71a0f",
                        "secondary": "#ffb4a8",
                        "tertiary": "#aac7ff",
                        "primary": "#ff4444",
                        "error": "#ffb4ab",
                        "on-primary-fixed-variant": "#930000",
                        "on-tertiary": "#002f64",
                        "tertiary-fixed-dim": "#aac7ff",
                        "on-tertiary-fixed-variant": "#00458d",
                        "surface-variant": "#353534",
                        "outline": "#777"
                    },
                    fontFamily: {
                        "headline": ["Plus Jakarta Sans"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    },
                    keyframes: {
                        'fly-in': {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        'pulse-glow': {
                            '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                            '50%': { opacity: '.8', transform: 'scale(1.05)' },
                        }
                    },
                    animation: {
                        'fly-in': 'fly-in 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards',
                        'pulse-glow': 'pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            transition: all 0.3s ease;
        }
        .icon-filled {
            font-variation-settings: 'FILL' 1;
        }
        .glass-panel {
            background: rgba(24, 24, 24, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .editorial-shadow {
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4);
        }
        /* Custom Scrolling */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #e71a0f; }
        
        /* Micro Interactions */
        .btn-hover-fx {
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
        }
        .btn-hover-fx:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 25px -5px rgba(231, 26, 15, 0.4);
        }
        .btn-hover-fx:active {
            transform: translateY(1px) scale(0.98);
        }
        
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: theme('colors.primary');
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary-container selection:text-on-primary-container antialiased">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 transition-all duration-500 glass-panel border-b border-white/5 bg-black/40" id="main-nav">
    <div id="nav-container" class="flex items-center justify-between px-6 md:px-8 py-5 w-full max-w-screen-2xl mx-auto transition-all duration-500">
        <!-- Brand -->
        <div class="flex items-center gap-12">
            <a href="<?php echo BASE_URL; ?>index.php" class="text-2xl font-black tracking-tighter text-red-600 uppercase font-headline hover:scale-105 transition-transform origin-left">CINEFLOW</a>
            
            <!-- Search Bar (Desktop) -->
            <?php 
                $currentFile = basename($_SERVER['PHP_SELF']);
                $searchAction = ($currentFile === 'rent.php') ? BASE_URL . 'pages/rent.php' : BASE_URL . 'pages/movies.php';
            ?>
            <form action="<?php echo $searchAction; ?>" method="GET" class="hidden lg:flex items-center bg-surface-container px-4 py-2.5 rounded-full min-w-[320px] group focus-within:ring-2 ring-primary/50 transition-all border border-outline-variant/30 focus-within:bg-zinc-900 shadow-inner">
                <span class="material-symbols-outlined text-zinc-500 mr-2 group-focus-within:text-primary transition-colors">search</span>
                <input name="search" class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder-zinc-500 text-on-surface px-0 outline-none" placeholder="Search movies..." type="text" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"/>
                <button type="submit" class="hidden"></button>
            </form>
        </div>
        
        <!-- Navigation Links -->
        <div class="hidden md:flex items-center gap-8">
            <a class="nav-link text-zinc-300 hover:text-white font-medium font-['Inter'] text-sm uppercase tracking-wide transition-colors <?php echo strpos($_SERVER['REQUEST_URI'], 'movies.php') !== false ? 'active text-white' : ''; ?>" href="<?php echo BASE_URL; ?>pages/movies.php">Movies</a>
            <a class="nav-link text-zinc-300 hover:text-white font-medium font-['Inter'] text-sm uppercase tracking-wide transition-colors <?php echo strpos($_SERVER['REQUEST_URI'], 'rent.php') !== false ? 'active text-white' : ''; ?>" href="<?php echo BASE_URL; ?>pages/rent.php">Rent Movies</a>
            <?php if (isset($_SESSION['user_id'])): ?>
            <a class="nav-link text-zinc-300 hover:text-white font-medium font-['Inter'] text-sm uppercase tracking-wide transition-colors <?php echo strpos($_SERVER['REQUEST_URI'], 'tickets.php') !== false ? 'active text-white' : ''; ?>" href="<?php echo BASE_URL; ?>pages/tickets.php">My Tickets</a>
            <?php endif; ?>
        </div>
        
        <!-- Trailing Actions -->
        <div class="flex items-center gap-4 md:gap-6">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="hidden sm:flex items-center gap-4 relative group cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary-container to-red-900 flex items-center justify-center font-bold font-headline ring-2 ring-transparent group-hover:ring-primary/50 transition-all btn-hover-fx">
                        <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                    </div>
                    <!-- Dropdown -->
                    <div class="absolute right-0 top-full mt-2 w-48 glass-panel rounded-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right group-hover:translate-y-0 translate-y-2">
                        <a href="<?php echo BASE_URL; ?>pages/tickets.php" class="flex items-center gap-3 px-4 py-2 hover:bg-white/10 transition-colors text-sm">
                            <span class="material-symbols-outlined text-sm">confirmation_number</span> My Tickets
                        </a>
                         <a href="<?php echo BASE_URL; ?>pages/my_rentals.php" class="flex items-center gap-3 px-4 py-2 hover:bg-white/10 transition-colors text-sm">
                            <span class="material-symbols-outlined text-sm">movie_filter</span> My Rentals
                        </a>
                        <div class="h-px bg-white/10 my-1"></div>
                        <a href="<?php echo BASE_URL; ?>pages/logout.php" class="flex items-center gap-3 px-4 py-2 hover:bg-white/10 transition-colors text-sm text-red-400">
                            <span class="material-symbols-outlined text-sm">logout</span> Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>pages/login.php" class="bg-primary text-on-primary-container px-6 py-2.5 rounded-full font-bold text-sm tracking-wide btn-hover-fx hover:brightness-110">Sign In</a>
            <?php endif; ?>
            
            <!-- Mobile Menu Toggle -->
            <button class="md:hidden material-symbols-outlined text-zinc-100 hover:bg-zinc-800 p-2 rounded-full transition-colors btn-hover-fx" onclick="toggleMobileMenu()">menu</button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden glass-panel border-t border-outline-variant/30 flex flex-col gap-4 p-6 absolute w-full top-full left-0 animate-fly-in">
        <a href="<?php echo BASE_URL; ?>pages/movies.php" class="text-lg font-bold">Movies</a>
        <a href="<?php echo BASE_URL; ?>pages/rent.php" class="text-lg font-bold">Rent Movies</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?php echo BASE_URL; ?>pages/tickets.php" class="text-lg font-bold text-primary">My Tickets</a>
            <a href="<?php echo BASE_URL; ?>pages/my_rentals.php" class="text-lg font-bold text-tertiary">My Rentals</a>
            <a href="<?php echo BASE_URL; ?>pages/logout.php" class="text-lg font-bold text-zinc-500">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }

    // Scroll effect for nav
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('main-nav');
        const container = document.getElementById('nav-container');
        if (window.scrollY > 20) {
            nav.classList.add('shadow-2xl', 'shadow-black/50', 'border-white/10', 'bg-black/90');
            nav.classList.remove('border-white/5', 'bg-black/40');
            container.classList.replace('py-5', 'py-3');
        } else {
            nav.classList.remove('shadow-2xl', 'shadow-black/50', 'border-white/10', 'bg-black/90');
            nav.classList.add('border-white/5', 'bg-black/40');
            container.classList.replace('py-3', 'py-5');
        }
    });
</script>
<!-- Toastify Script -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    function showToast(message, type = 'success') {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "bottom",
            position: "center",
            style: {
                background: type === 'success' ? "linear-gradient(to right, #00b09b, #96c93d)" : "linear-gradient(to right, #e71a0f, #930000)",
                borderRadius: "12px",
                fontFamily: "Inter, sans-serif",
                fontWeight: "600",
                boxShadow: "0 10px 30px rgba(0,0,0,0.5)"
            }
        }).showToast();
    }
</script>