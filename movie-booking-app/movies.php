<?php
$pageTitle = 'Movies - AUTEUR Cinema';
require_once 'includes/header.php';
require_once 'includes/functions.php';

$movies = getAllMovies();
?>

<main class="pt-24 pb-20">
<!-- Header Section -->
<section class="max-w-screen-2xl mx-auto px-12 py-12">
<div class="flex items-end justify-between mb-12">
<div>
<h1 class="text-6xl font-headline font-extrabold tracking-tight">Movies</h1>
<p class="text-zinc-400 text-lg mt-4">Discover the latest blockbusters and timeless classics</p>
</div>
<div class="flex gap-4">
<button class="bg-surface-container-highest text-zinc-100 px-6 py-3 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-zinc-700 transition-colors">
    Filter <span class="material-symbols-outlined text-[18px]">filter_list</span>
</button>
<button class="bg-surface-container-highest text-zinc-100 px-6 py-3 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-zinc-700 transition-colors">
    Sort <span class="material-symbols-outlined text-[18px]">sort</span>
</button>
</div>
</div>

<!-- Movies Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
<?php foreach ($movies as $movie): ?>
<div class="group cursor-pointer">
<div class="relative aspect-[2/3] rounded-3xl overflow-hidden mb-6 editorial-shadow">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
     data-alt="<?php echo htmlspecialchars($movie['title']); ?>" 
     src="<?php echo htmlspecialchars($movie['poster_url'] ?? 'https://via.placeholder.com/300x450?text=' . urlencode($movie['title'])); ?>"/>
<div class="absolute top-4 right-4 glass-panel px-3 py-1.5 rounded-xl flex items-center gap-1.5">
<span class="material-symbols-outlined text-yellow-500 text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-xs font-bold text-white"><?php echo number_format($movie['rating'], 1); ?></span>
</div>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
<a href="movie_details.php?id=<?php echo $movie['id']; ?>" class="w-full bg-primary-container text-white py-3 rounded-xl font-bold uppercase tracking-tighter transform translate-y-4 group-hover:translate-y-0 transition-transform">Book Now</a>
</div>
</div>
<h4 class="text-xl font-headline font-bold group-hover:text-red-500 transition-colors"><?php echo htmlspecialchars($movie['title']); ?></h4>
<p class="text-zinc-500 text-sm font-medium mt-1"><?php echo htmlspecialchars($movie['genre']); ?></p>
<p class="text-zinc-600 text-xs mt-1"><?php echo date('M j, Y', strtotime($movie['release_date'])); ?> • <?php echo $movie['duration']; ?>min</p>
</div>
<?php endforeach; ?>
</div>
</section>
</main>

<?php require_once 'includes/footer.php'; ?>