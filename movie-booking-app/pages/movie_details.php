<?php
$pageTitle = 'Movie Details - CINEFLOW';
require_once '../includes/header.php';
require_once '../includes/functions.php';

$movieId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$movie = getMovieById($movieId);

if (!$movie) {
    header('Location: ' . BASE_URL . 'pages/movies.php');
    exit();
}

$movies = getAllMovies();
$shows = getShowsForMovie($movieId);
try {
    $stmt = getDBConnection()->prepare("SELECT * FROM shows WHERE movie_id = ? AND show_date >= CURDATE()");
    $stmt->execute([$movieId]);
    $upcomingShows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hasShows = count($upcomingShows) > 0;
} catch (Exception $e) { $hasShows = false; }
?>

<main class="relative pt-0 min-h-screen">
<!-- Hero Background Section -->
<section class="relative h-[85vh] min-h-[600px] w-full overflow-hidden">
<div class="absolute inset-0 z-0">
<img alt="Cinematic background" class="w-full h-full object-cover animate-pulse-glow" src="<?php echo BASE_URL . htmlspecialchars($movie['backdrop_url'] ?? $movie['poster_url']); ?>"/>
<div class="absolute inset-0 editorial-gradient"></div>
</div>
<!-- Content Overlay -->
<div class="relative z-10 h-full flex items-end pb-16 max-w-screen-2xl mx-auto px-6 md:px-12 animate-fly-in">
<div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 w-full items-end">
<!-- Left Column: Poster -->
<div class="hidden md:block md:col-span-4 lg:col-span-3 pb-8">
<div class="group relative aspect-[2/3] w-full rounded-2xl overflow-hidden shadow-2xl shadow-black/60 transform -rotate-2 hover:rotate-0 hover:-translate-y-4 transition-all duration-500 border border-white/10">
<img alt="Movie Poster" class="w-full h-full object-cover" src="<?php echo BASE_URL . htmlspecialchars($movie['poster_url']); ?>"/>
<div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm cursor-pointer">
<button class="w-20 h-20 bg-primary/80 text-white rounded-full flex items-center justify-center transform scale-75 group-hover:scale-100 transition-all duration-500 hover:bg-primary shadow-lg shadow-red-900/50">
<span class="material-symbols-outlined text-5xl icon-filled">play_arrow</span>
</button>
</div>
<div class="absolute bottom-4 left-0 right-0 text-center pointer-events-none">
<span class="bg-black/80 backdrop-blur-md text-white text-[10px] px-4 py-1.5 rounded-full uppercase tracking-widest font-bold">Watch Trailer</span>
</div>
</div>
</div>
<!-- Right Column: Details -->
<div class="col-span-1 md:col-span-8 lg:col-span-9 flex flex-col items-start gap-6 pb-8">
<div class="space-y-4">
<div class="flex items-center gap-4">
<span class="bg-primary text-white text-[10px] font-black px-3 py-1 rounded tracking-widest uppercase shadow-sm">Now Showing</span>
<div class="flex items-center gap-1.5 text-yellow-500 bg-surface-container-high px-3 py-1 rounded-full border border-outline-variant/30">
<span class="material-symbols-outlined text-sm icon-filled">star</span>
<span class="font-headline font-bold text-sm text-white"><?php echo number_format($movie['rating'], 1); ?>/10</span>
</div>
</div>
<h1 class="text-5xl md:text-7xl lg:text-8xl font-headline font-black tracking-tighter text-white uppercase leading-[0.9] drop-shadow-2xl">
    <?php echo htmlspecialchars($movie['title']); ?>
</h1>
</div>
<div class="flex flex-wrap items-center gap-3">
<div class="glass-panel px-4 py-2.5 rounded-xl flex items-center gap-2 hover:bg-white/10 transition-colors cursor-default">
<span class="material-symbols-outlined text-zinc-400 text-sm">theaters</span>
<span class="text-sm font-semibold text-zinc-200"><?php echo htmlspecialchars($movie['genre']); ?></span>
</div>
<div class="glass-panel px-4 py-2.5 rounded-xl flex items-center gap-2 hover:bg-white/10 transition-colors cursor-default">
<span class="material-symbols-outlined text-zinc-400 text-sm">calendar_today</span>
<span class="text-sm font-semibold text-zinc-200"><?php echo date('M j, Y', strtotime($movie['release_date'])); ?></span>
</div>
<div class="glass-panel px-4 py-2.5 rounded-xl flex items-center gap-2 hover:bg-white/10 transition-colors cursor-default">
<span class="material-symbols-outlined text-zinc-400 text-sm">schedule</span>
<span class="text-sm font-semibold text-zinc-200"><?php echo $movie['duration']; ?> min</span>
</div>
<div class="glass-panel px-4 py-2.5 rounded-xl flex items-center gap-2 hover:bg-white/10 transition-colors cursor-default border-primary/30">
<span class="material-symbols-outlined text-primary text-sm">4k</span>
<span class="text-sm font-semibold text-zinc-200"><?php echo htmlspecialchars($movie['format']); ?></span>
</div>
</div>

<div class="flex flex-wrap items-center gap-4 mt-6">
<?php if ($hasShows): ?>
<a href="<?php echo BASE_URL; ?>pages/showtimes.php?movie_id=<?php echo $movie['id']; ?>" class="relative overflow-hidden group bg-primary text-white px-10 py-5 rounded-full font-headline font-black text-lg tracking-widest uppercase shadow-xl shadow-red-900/40 hover:scale-105 active:scale-95 transition-all">
    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
    <span class="relative flex items-center gap-3">
        <span class="material-symbols-outlined">confirmation_number</span>
        Book Tickets
    </span>
</a>
<?php else: ?>
<button disabled class="bg-surface-container-highest text-zinc-500 px-10 py-5 rounded-full font-headline font-black text-lg tracking-widest uppercase cursor-not-allowed">
    No Shows Available
</button>
<?php endif; ?>
<button class="w-16 h-16 rounded-full glass-panel flex items-center justify-center text-white hover:bg-red-500/20 hover:text-red-500 hover:border-red-500/50 transition-all btn-hover-fx group">
<span class="material-symbols-outlined text-2xl group-hover:scale-110 transition-transform">favorite</span>
</button>
<button class="w-16 h-16 rounded-full glass-panel flex items-center justify-center text-white hover:bg-blue-500/20 hover:text-blue-400 hover:border-blue-500/50 transition-all btn-hover-fx group">
<span class="material-symbols-outlined text-2xl group-hover:scale-110 transition-transform">share</span>
</button>
</div>
</div>
</div>
</div>
</section>

<!-- Body Content -->
<section class="max-w-screen-2xl mx-auto px-6 md:px-12 py-20 bg-surface">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24">
<!-- Main Content Area -->
<div class="lg:col-span-8 space-y-20 animate-fly-in" style="animation-delay: 0.2s;">
<!-- About Section -->
<div class="space-y-8">
<div class="flex items-center gap-6">
<h2 class="text-3xl md:text-4xl font-headline font-black tracking-tight uppercase text-white">Synopsis</h2>
<div class="h-px flex-1 bg-gradient-to-r from-outline-variant/50 to-transparent"></div>
</div>
<p class="text-zinc-400 text-lg md:text-xl leading-relaxed max-w-4xl font-medium tracking-wide">
    <?php echo nl2br(htmlspecialchars($movie['description'])); ?>
</p>
</div>

<!-- Showtimes Quick View -->
<?php if (!empty($shows)): ?>
<div class="space-y-8">
<div class="flex items-center gap-6">
<h2 class="text-3xl md:text-4xl font-headline font-black tracking-tight uppercase text-white">Quick Book</h2>
<div class="h-px flex-1 bg-gradient-to-r from-outline-variant/50 to-transparent"></div>
<a href="<?php echo BASE_URL; ?>pages/showtimes.php?movie_id=<?php echo $movie['id']; ?>" class="text-primary text-sm font-bold uppercase tracking-widest hover:text-white transition-colors flex items-center gap-1">All Shows <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
</div>

<div class="space-y-6">
<?php 
// Group quick shows by theater
$quickShows = array_slice($shows, 0, 5); // Just show a few
$theaters = [];
foreach ($quickShows as $s) {
    $theaters[$s['theater_name']][] = $s;
}

foreach ($theaters as $theaterName => $tShows): 
?>
<div class="bg-surface-container-low p-6 md:p-8 rounded-3xl border border-outline-variant/10 hover:border-outline-variant/30 transition-colors">
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-2">
<h3 class="text-xl font-bold font-headline text-white"><?php echo htmlspecialchars($theaterName); ?></h3>
<span class="text-xs font-bold uppercase tracking-widest text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">Available Today</span>
</div>
<div class="flex flex-wrap gap-4">
<?php foreach ($tShows as $show): ?>
<a href="<?php echo BASE_URL; ?>pages/seat_selection.php?show_id=<?php echo $show['id']; ?>" class="group/time relative overflow-hidden bg-surface-container-high border border-outline-variant/20 hover:border-primary/50 rounded-2xl p-4 cursor-pointer transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-primary/20 min-w-[120px]">
<p class="text-center text-white font-black text-xl group-hover/time:text-primary transition-colors"><?php echo date('H:i', strtotime($show['show_time'])); ?></p>
<p class="text-center text-[10px] uppercase tracking-widest text-zinc-500 mt-1 font-bold"><?php echo htmlspecialchars($show['format']); ?></p>
</a>
<?php endforeach; ?>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
</div>

<!-- Sidebar Content -->
<div class="lg:col-span-4 space-y-12 animate-fly-in" style="animation-delay: 0.4s;">
<div class="space-y-8 sticky top-32">
<div class="flex items-center gap-4">
<h2 class="text-2xl font-headline font-black tracking-tight uppercase text-white">More Like This</h2>
<div class="h-px flex-1 bg-gradient-to-r from-outline-variant/50 to-transparent"></div>
</div>
<div class="grid grid-cols-2 gap-4">
<?php
$similarCount = 0;
foreach ($movies as $similarMovie):
    if ($similarMovie['id'] == $movieId) continue;
    if ($similarCount >= 4) break;
    $similarCount++;
?>
<a href="<?php echo BASE_URL; ?>pages/movie_details.php?id=<?php echo $similarMovie['id']; ?>" class="group relative aspect-[3/4] rounded-2xl overflow-hidden cursor-pointer border border-white/5">
<img alt="<?php echo htmlspecialchars($similarMovie['title']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="<?php echo BASE_URL . htmlspecialchars($similarMovie['poster_url']); ?>"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity flex items-end p-4">
<div class="transform translate-y-2 group-hover:translate-y-0 transition-transform w-full">
    <p class="text-white text-sm font-black truncate"><?php echo htmlspecialchars($similarMovie['title']); ?></p>
    <p class="text-primary text-[10px] font-bold uppercase tracking-widest mt-1">View Details</p>
</div>
</div>
</a>
<?php endforeach; ?>
</div>
</div>
</div>
</div>
</section>
</main>

<style>
.editorial-gradient {
    background: linear-gradient(to bottom, rgba(16, 16, 16, 0) 0%, rgba(16, 16, 16, 0.6) 60%, rgba(16, 16, 16, 1) 100%);
}
</style>

<?php require_once '../includes/footer.php'; ?>