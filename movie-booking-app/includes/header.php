<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $pageTitle ?? 'AUTEUR | Cinematic Discovery'; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                        "surface": "#131313",
                        "inverse-surface": "#e5e2e1",
                        "surface-dim": "#131313",
                        "on-primary-container": "#fffdff",
                        "surface-container-lowest": "#0e0e0e",
                        "on-surface-variant": "#e8bcb5",
                        "surface-container": "#201f1f",
                        "on-tertiary-container": "#fffcff",
                        "tertiary-container": "#0073e2",
                        "inverse-on-surface": "#313030",
                        "surface-container-low": "#1c1b1b",
                        "secondary-container": "#90190f",
                        "on-secondary-container": "#ff9f90",
                        "on-background": "#e5e2e1",
                        "tertiary-fixed": "#d6e3ff",
                        "on-surface": "#e5e2e1",
                        "surface-container-highest": "#353534",
                        "inverse-primary": "#c00000",
                        "surface-container-high": "#2a2a2a",
                        "surface-bright": "#393939",
                        "on-secondary-fixed": "#410000",
                        "primary-fixed-dim": "#ffb4a8",
                        "on-error-container": "#ffdad6",
                        "background": "#131313",
                        "surface-tint": "#ffb4a8",
                        "on-error": "#690005",
                        "on-secondary-fixed-variant": "#8c160d",
                        "outline-variant": "#5e3f3a",
                        "error-container": "#93000a",
                        "primary-fixed": "#ffdad4",
                        "primary-container": "#e71a0f",
                        "secondary": "#ffb4a8",
                        "tertiary": "#aac7ff",
                        "primary": "#ffb4a8",
                        "error": "#ffb4ab",
                        "on-primary-fixed-variant": "#930000",
                        "on-tertiary": "#002f64",
                        "tertiary-fixed-dim": "#aac7ff",
                        "on-tertiary-fixed-variant": "#00458d",
                        "surface-variant": "#353534",
                        "outline": "#af8781"
                    },
                    fontFamily: {
                        "headline": ["Plus Jakarta Sans"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1.5rem", "3xl": "1.5rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-panel {
            background: rgba(53, 53, 52, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .editorial-shadow {
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4);
        }

        /* Custom Animations */
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes slide-in-left {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slide-in-right {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out;
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out;
        }
        .animate-slide-in-left {
            animation: slide-in-left 1s ease-out;
        }
        .animate-slide-in-right {
            animation: slide-in-right 1s ease-out;
        }
        .delay-300 {
            animation-delay: 0.3s;
        }
        .delay-500 {
            animation-delay: 0.5s;
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary-container selection:text-on-primary-container">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-zinc-950/80 backdrop-blur-xl shadow-2xl shadow-black/40">
<div class="flex items-center justify-between px-8 py-4 w-full max-w-screen-2xl mx-auto">
<!-- Brand -->
<div class="flex items-center gap-12">
<a href="index.php" class="text-2xl font-black tracking-tighter text-red-600 uppercase font-headline">AUTEUR</a>
<!-- Search Bar (on_left) -->
<div class="hidden md:flex items-center bg-surface-container-high px-4 py-2 rounded-xl min-w-[320px] group focus-within:ring-1 ring-outline-variant/30 transition-all">
<span class="material-symbols-outlined text-zinc-500 mr-2" data-icon="search">search</span>
<input class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder-zinc-500 text-on-surface" placeholder="Search for movies..." type="text"/>
</div>
</div>
<!-- Navigation Links -->
<div class="hidden lg:flex items-center gap-8">
<a class="text-red-500 font-bold border-b-2 border-red-500 pb-1 font-['Inter'] text-sm uppercase tracking-wide" href="movies.php">Movies</a>
<a class="text-zinc-400 font-medium hover:text-zinc-100 transition-colors font-['Inter'] text-sm uppercase tracking-wide" href="showtimes.php">Showtimes</a>
</div>
<!-- Trailing Actions -->
<div class="flex items-center gap-6">
<button class="flex items-center gap-2 text-zinc-400 hover:text-zinc-100 transition-colors group">
<span class="text-sm font-semibold uppercase tracking-wider">New York</span>
<span class="material-symbols-outlined text-sm group-hover:rotate-180 transition-transform" data-icon="keyboard_arrow_down">keyboard_arrow_down</span>
</button>
<?php if (isset($_SESSION['user_id'])): ?>
    <div class="flex items-center gap-4">
        <span class="text-zinc-300">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="logout.php" class="text-zinc-400 hover:text-zinc-100 transition-colors">Logout</a>
    </div>
<?php else: ?>
    <a href="login.php" class="bg-primary-container text-on-primary-container px-6 py-2.5 rounded-xl font-bold text-sm tracking-wide hover:brightness-110 active:scale-95 transition-all">Sign In</a>
<?php endif; ?>
<button class="material-symbols-outlined text-zinc-100 hover:bg-zinc-800/50 p-2 rounded-lg transition-all" data-icon="menu">menu</button>
</div>
</div>
</nav>