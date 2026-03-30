<?php
$pageTitle = 'Movie Details - AUTEUR Cinema';
require_once 'includes/header.php';
require_once 'includes/functions.php';

$movieId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$movie = getMovieById($movieId);

if (!$movie) {
    header('Location: movies.php');
    exit();
}

// Get shows for today
$shows = getShowsForMovie($movieId);
?>

<main class="relative pt-0">
<!-- Hero Background Section -->
<section class="relative h-[870px] w-full overflow-hidden">
<div class="absolute inset-0 z-0">
<img alt="Cinematic background" class="w-full h-full object-cover" data-alt="Wide cinematic shot of a luxury mountain resort pool at sunset with dramatic orange and teal lighting and soft mist" src="<?php echo htmlspecialchars($movie['backdrop_url'] ?? $movie['poster_url'] ?? 'https://via.placeholder.com/1920x1080?text=' . urlencode($movie['title'])); ?>"/>
<div class="absolute inset-0 editorial-gradient"></div>
</div>
<!-- Content Overlay -->
<div class="relative z-10 h-full flex items-end pb-16 max-w-screen-2xl mx-auto px-8 md:px-12">
<div class="grid grid-cols-1 md:grid-cols-12 gap-12 w-full items-end">
<!-- Left Column: Poster -->
<div class="hidden md:block md:col-span-4 lg:col-span-3">
<div class="group relative aspect-[2/3] w-full rounded-2xl overflow-hidden shadow-2xl shadow-black/60 transform -rotate-1 hover:rotate-0 transition-transform duration-500">
<img alt="Movie Poster" class="w-full h-full object-cover" data-alt="Abstract cinematic movie poster with high contrast lighting, featuring a silhouette of an older man looking at a mountain range" src="<?php echo htmlspecialchars($movie['poster_url'] ?? 'https://via.placeholder.com/300x450?text=' . urlencode($movie['title'])); ?>"/>
<div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors flex items-center justify-center">
<button class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/30 hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-white text-4xl" data-weight="fill">play_arrow</span>
</button>
</div>
<div class="absolute bottom-4 left-0 right-0 text-center">
<span class="bg-black/60 backdrop-blur-md text-white text-[10px] px-3 py-1 rounded-full uppercase tracking-widest font-bold">Trailer</span>
</div>
</div>
</div>
<!-- Right Column: Details -->
<div class="col-span-1 md:col-span-8 lg:col-span-9 flex flex-col items-start gap-6">
<div class="space-y-2">
<div class="flex items-center gap-3">
<span class="bg-primary-container text-on-primary-container text-[10px] font-black px-2 py-0.5 rounded tracking-tighter uppercase">Premiering</span>
<div class="flex items-center gap-1 text-primary">
<span class="material-symbols-outlined text-sm" data-weight="fill">star</span>
<span class="font-headline font-bold text-lg"><?php echo number_format($movie['rating'], 1); ?>/10</span>
<span class="text-zinc-400 text-sm font-medium ml-1">19.6K+ Votes</span>
</div>
</div>
<h1 class="text-6xl md:text-8xl font-headline font-black tracking-tighter text-on-surface uppercase leading-[0.9]"><?php echo htmlspecialchars($movie['title']); ?></h1>
</div>
<div class="flex flex-wrap items-center gap-4">
<div class="glass-panel px-4 py-2 rounded-xl flex items-center gap-2">
<span class="material-symbols-outlined text-zinc-400 text-sm">theaters</span>
<span class="text-sm font-semibold"><?php echo htmlspecialchars($movie['genre']); ?></span>
</div>
<div class="glass-panel px-4 py-2 rounded-xl flex items-center gap-2">
<span class="material-symbols-outlined text-zinc-400 text-sm">calendar_today</span>
<span class="text-sm font-semibold"><?php echo date('M j, Y', strtotime($movie['release_date'])); ?></span>
</div>
<div class="glass-panel px-4 py-2 rounded-xl flex items-center gap-2">
<span class="material-symbols-outlined text-zinc-400 text-sm">language</span>
<span class="text-sm font-semibold">English</span>
</div>
<div class="glass-panel px-4 py-2 rounded-xl flex items-center gap-2">
<span class="material-symbols-outlined text-zinc-400 text-sm">schedule</span>
<span class="text-sm font-semibold"><?php echo $movie['duration']; ?>m</span>
</div>
</div>
<div class="flex items-center gap-6 mt-4">
<?php if (!empty($shows)): ?>
<a href="showtimes.php?movie_id=<?php echo $movie['id']; ?>" class="bg-primary-container text-on-primary-container px-12 py-5 rounded-2xl font-headline font-black text-xl tracking-tight shadow-xl shadow-red-900/20 hover:brightness-110 active:scale-95 transition-all">
    Book Tickets
</a>
<?php else: ?>
<span class="text-zinc-400 px-12 py-5 rounded-2xl font-headline font-black text-xl tracking-tight">No Shows Available</span>
<?php endif; ?>
<button class="glass-panel text-on-surface p-5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
<span class="material-symbols-outlined">favorite</span>
</button>
<button class="glass-panel text-on-surface p-5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
<span class="material-symbols-outlined">share</span>
</button>
</div>
</div>
</div>
</div>
</section>
<!-- Body Content -->
<section class="max-w-screen-2xl mx-auto px-8 md:px-12 py-20 bg-surface">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-20">
<!-- Main Content Area -->
<div class="lg:col-span-8 space-y-16">
<!-- About Section -->
<div class="space-y-6">
<div class="flex items-center gap-4">
<h2 class="text-3xl font-headline font-extrabold tracking-tight">About the Movie</h2>
<div class="h-px flex-1 bg-surface-container-highest"></div>
</div>
<p class="text-zinc-400 text-lg leading-relaxed max-w-3xl">
    <?php echo nl2br(htmlspecialchars($movie['description'])); ?>
</p>
</div>
<!-- Showtimes Section -->
<?php if (!empty($shows)): ?>
<div class="space-y-8">
<div class="flex items-center gap-4">
<h2 class="text-3xl font-headline font-extrabold tracking-tight">Showtimes</h2>
<div class="h-px flex-1 bg-surface-container-highest"></div>
</div>
<div class="space-y-6">
<?php foreach ($shows as $show): ?>
<div class="bg-surface-container-low p-6 rounded-2xl">
<div class="flex items-center justify-between mb-4">
<h3 class="text-xl font-bold"><?php echo htmlspecialchars($show['theater_name']); ?></h3>
<span class="text-sm text-zinc-400"><?php echo htmlspecialchars($show['location']); ?></span>
</div>
<div class="flex flex-wrap gap-3">
<a href="seat_selection.php?show_id=<?php echo $show['id']; ?>" class="bg-primary-container text-on-primary-container px-6 py-3 rounded-xl font-bold hover:brightness-110 transition-all">
    <?php echo date('g:i A', strtotime($show['show_time'])); ?> - <?php echo htmlspecialchars($show['format']); ?> - <?php echo formatCurrency($show['price']); ?>
</a>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
</div>
<!-- Sidebar Content -->
<div class="lg:col-span-4 space-y-12">
<!-- Similar Movies Bento -->
<div class="space-y-6">
<h2 class="text-2xl font-headline font-extrabold tracking-tight">Similar Movies</h2>
<div class="grid grid-cols-2 gap-4">
<?php
$similarMovies = array_slice($movies, 0, 4); // Just show first 4 for now
foreach ($similarMovies as $similarMovie):
if ($similarMovie['id'] == $movieId) continue;
?>
<div class="group relative aspect-[3/4] rounded-xl overflow-hidden cursor-pointer">
<img alt="<?php echo htmlspecialchars($similarMovie['title']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?php echo htmlspecialchars($similarMovie['poster_url'] ?? 'https://via.placeholder.com/300x450?text=' . urlencode($similarMovie['title'])); ?>"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
<p class="text-white text-sm font-bold"><?php echo htmlspecialchars($similarMovie['title']); ?></p>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
</div>
</section>
</main>

<style>
.editorial-gradient {
    background: linear-gradient(to bottom, rgba(19, 19, 19, 0) 0%, rgba(19, 19, 19, 0.8) 50%, rgba(19, 19, 19, 1) 100%);
}
</style>

<?php require_once 'includes/footer.php'; ?>