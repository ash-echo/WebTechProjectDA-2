<?php
$pageTitle = 'Watching - CINEFLOW Premiere';
require_once '../includes/header.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireLogin(); // Ensure user is logged in

$movieId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$movie = getMovieById($movieId);

if (!$movie) {
    header('Location: ' . BASE_URL . 'pages/rent.php');
    exit();
}

// Check if rented
if (!isMovieRented($movieId, $_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'pages/rent_checkout.php?id=' . $movieId);
    exit();
}

// Map movie IDs to their specific filenames
$videoFileName = $movieId . ".mp4"; // Default
if ($movieId == 5) $videoFileName = "Oppenheimer.mp4";
if ($movieId == 6) $videoFileName = "Batman.mp4";

$videoSrc = BASE_URL . "assets/rent/" . $videoFileName;
?>

<main class="fixed inset-0 z-[100] bg-black flex items-center justify-center overflow-hidden">
    <!-- Back to Cinema -->
    <a href="<?php echo BASE_URL; ?>pages/my_rentals.php" class="absolute top-8 left-8 z-[110] flex items-center gap-4 bg-white/10 hover:bg-white text-white hover:text-black px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all backdrop-blur-md group">
        <span class="material-symbols-outlined text-sm transition-transform group-hover:-translate-x-1">arrow_back</span>
        Exit Cinema
    </a>

    <!-- Video Container -->
    <div class="relative w-full h-full group">
        <video id="cinePlayer" class="w-full h-full object-contain" controls autoplay playsinline controlsList="nodownload">
            <source src="<?php echo $videoSrc; ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- Overlay Text (Fades out) -->
        <div id="playerOverlay" class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center transition-opacity duration-1000 pointer-events-none z-10">
            <div class="text-center space-y-4 animate-fly-in">
                <span class="text-xs font-black text-primary uppercase tracking-[0.5em] block">NOW PLAYING</span>
                <h1 class="text-5xl md:text-8xl font-headline font-black text-white px-12 italic uppercase leading-none"><?php echo htmlspecialchars($movie['title']); ?></h1>
                <div class="flex items-center justify-center gap-4 text-zinc-400 font-bold tracking-widest uppercase text-[10px] pt-4">
                    <span>4K ULTRA HD</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-800"></span>
                    <span>DOLBY ATMOS</span>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('cinePlayer');
    const overlay = document.getElementById('playerOverlay');
    
    // Hide overlay after 3 seconds of play
    video.addEventListener('play', () => {
        setTimeout(() => {
            overlay.classList.add('opacity-0');
        }, 3000);
    });

    // Handle spacebar play/pause
    document.addEventListener('keydown', (e) => {
        if (e.code === 'Space') {
            e.preventDefault();
            if (video.paused) video.play();
            else video.pause();
        }
    });

    // Check if file exists (Optional: could handle 404 gracefully)
    video.addEventListener('error', (e) => {
        showToast('Movie file not found. Please check your rent folder.', 'error');
    });
});
</script>

<style>
    /* Hide scrollbars during playback */
    body { overflow: hidden !important; }
    
    /* Custom Video Styling if needed */
    video::-webkit-media-controls-panel {
        background-image: linear-gradient(transparent, rgba(0,0,0,0.8)) !important;
    }
</style>

<?php require_once '../includes/footer.php'; ?>
