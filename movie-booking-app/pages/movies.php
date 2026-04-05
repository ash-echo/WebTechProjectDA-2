<?php
$pageTitle = 'Movies - CINEFLOW';
require_once '../includes/header.php';
require_once '../includes/functions.php';

$movies = getAllMovies();
?>

<main class="pt-24 pb-20 relative min-h-screen">
    <!-- Grid Background -->
    <div class="fixed inset-0 z-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-10 pointer-events-none mix-blend-screen"></div>

<!-- Header Section -->
<section class="max-w-screen-2xl mx-auto px-6 md:px-12 py-12 relative z-10 animate-fly-in">
<div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-6 mb-12 border-b border-outline-variant/20 pb-8">
<div>
<h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter uppercase text-white shadow-sm flex items-center gap-4">
    Movies <div class="w-3 h-3 bg-primary rounded-full animate-pulse-glow mt-4"></div>
</h1>
<p class="text-zinc-400 text-lg md:text-xl mt-4 max-w-2xl font-medium tracking-wide">
    Discover the latest blockbusters and timeless classics curated for the ultimate cinematic experience.
</p>
</div>
<div class="flex gap-4">
<button class="bg-surface-container-high text-zinc-100 px-6 py-4 rounded-full text-xs font-bold uppercase tracking-widest flex items-center gap-2 hover:bg-white hover:text-black transition-all btn-hover-fx border border-outline-variant/30">
    <span class="material-symbols-outlined text-[18px]">filter_list</span> Filter
</button>
<button class="bg-surface-container-high text-zinc-100 px-6 py-4 rounded-full text-xs font-bold uppercase tracking-widest flex items-center gap-2 hover:bg-white hover:text-black transition-all btn-hover-fx border border-outline-variant/30">
    <span class="material-symbols-outlined text-[18px]">sort</span> Sort
</button>
</div>
</div>

<!-- Movies Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 md:gap-10">
<?php if (empty($movies)): ?>
    <div class="col-span-full py-20 text-center">
        <div class="material-symbols-outlined text-6xl text-zinc-600 mb-4">movie_off</div>
        <h3 class="text-2xl font-bold text-zinc-400 mb-2">No Movies Found</h3>
        <p class="text-zinc-500">We couldn't load any movies at this time. Please ensure the database is imported.</p>
    </div>
<?php else: ?>
<?php foreach ($movies as $index => $movie): ?>
<div class="group cursor-pointer animate-fly-in" style="animation-delay: <?php echo min($index * 0.05, 0.5); ?>s">
<div class="relative aspect-[2/3] rounded-3xl overflow-hidden mb-6 editorial-shadow transform group-hover:scale-[1.03] group-hover:-translate-y-2 transition-all duration-500 hover:shadow-2xl hover:shadow-red-900/40 border border-white/5 bg-surface-container">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
     alt="<?php echo htmlspecialchars($movie['title']); ?>" 
     src="<?php echo BASE_URL . htmlspecialchars($movie['poster_url']); ?>"/>
<div class="absolute top-4 right-4 glass-panel px-3 py-1.5 rounded-xl flex items-center gap-1.5 transition-transform group-hover:scale-110">
<span class="material-symbols-outlined text-yellow-500 text-sm icon-filled">star</span>
<span class="text-xs font-bold text-white"><?php echo number_format($movie['rating'], 1); ?></span>
</div>
<div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6 duration-300">
<a href="<?php echo BASE_URL; ?>pages/movie_details.php?id=<?php echo $movie['id']; ?>" class="w-full bg-primary text-white py-4 rounded-xl font-bold uppercase tracking-widest text-sm text-center transform translate-y-8 group-hover:translate-y-0 transition-all duration-500 hover:brightness-110 shadow-xl shadow-red-900/50">Details & Tickets</a>
</div>
</div>
<h4 class="text-xl font-headline font-black text-white group-hover:text-primary transition-colors truncate tracking-tight"><?php echo htmlspecialchars($movie['title']); ?></h4>
<p class="text-zinc-400 text-sm font-medium mt-1 truncate"><?php echo htmlspecialchars($movie['genre']); ?></p>
<div class="flex items-center gap-2 mt-2">
    <span class="text-zinc-600 text-xs font-bold uppercase tracking-wider bg-surface-container px-2 py-1 rounded-md"><?php echo $movie['format']; ?></span>
    <span class="text-zinc-600 text-xs font-bold">• <?php echo $movie['duration']; ?>m</span>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</section>
</main>

<?php require_once '../includes/footer.php'; ?>