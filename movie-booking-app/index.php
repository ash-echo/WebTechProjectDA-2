<?php
$pageTitle = 'AUTEUR | Cinematic Discovery';
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Get featured movies (first 5)
$movies = getAllMovies();
$featuredMovies = array_slice($movies, 0, 5);
?>

<main class="pt-20">
<!-- Hero Carousel Section -->
<section class="relative w-full h-[870px] overflow-hidden">
<div class="absolute inset-0">
<img class="w-full h-full object-cover scale-105 animate-pulse" data-alt="Cinematic wide shot of a futuristic neon city at night with deep reds and blues, moody atmospheric fog and cinematic lighting" src="https://images.unsplash.com/photo-1489599735734-79b4e62b8c2f?w=1920&h=1080&fit=crop"/>
<div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/40 to-transparent"></div>
<div class="absolute inset-0 bg-gradient-to-r from-surface via-transparent to-transparent"></div>
</div>
<div class="relative h-full max-w-screen-2xl mx-auto px-12 flex flex-col justify-end pb-24">
<div class="max-w-3xl space-y-6 animate-fade-in-up">
<div class="flex items-center gap-3">
<span class="bg-primary-container text-on-primary-container px-3 py-1 rounded-md text-xs font-bold tracking-widest uppercase animate-bounce">Now Playing</span>
<span class="text-zinc-300 text-sm font-medium">Experience Cinema Like Never Before</span>
</div>
<h1 class="text-7xl md:text-9xl font-headline font-extrabold tracking-tighter text-on-surface leading-[0.9] animate-slide-in-left">
    YOUTH
</h1>
<p class="text-xl text-zinc-300 font-body leading-relaxed max-w-xl animate-fade-in delay-300">
    A visually stunning journey through time and memory, exploring the fragility of existence in a rapidly changing world.
</p>
<div class="flex items-center gap-4 pt-4 animate-fade-in delay-500">
<a href="movie_details.php?id=1" class="bg-primary-container text-on-primary-container px-10 py-4 rounded-full font-bold text-lg flex items-center gap-3 hover:brightness-110 active:scale-95 transition-all editorial-shadow hover:shadow-2xl hover:shadow-red-500/30 transform hover:-translate-y-1">
<span class="material-symbols-outlined" data-icon="play_arrow" data-weight="fill" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
    Book Tickets
</a>
<a href="movie_details.php?id=1" class="glass-panel text-on-surface px-8 py-4 rounded-full font-bold text-lg flex items-center gap-3 hover:bg-white/10 transition-all border border-white/20 hover:border-white/40 transform hover:scale-105">
<span class="material-symbols-outlined" data-icon="info">info</span>
    View Details
</a>
</div>
</div>
</div>
<!-- Carousel Indicators -->
<div class="absolute right-12 bottom-24 flex flex-col gap-4 animate-pulse">
<div class="w-1.5 h-12 bg-primary-container rounded-full shadow-lg shadow-red-500/50"></div>
<div class="w-1.5 h-8 bg-zinc-700 rounded-full hover:bg-zinc-500 cursor-pointer transition-all hover:scale-110"></div>
<div class="w-1.5 h-8 bg-zinc-700 rounded-full hover:bg-zinc-500 cursor-pointer transition-all hover:scale-110"></div>
</div>
</section>

<!-- Quick Stats Section -->
<section class="max-w-screen-2xl mx-auto px-12 py-16">
<div class="grid grid-cols-2 md:grid-cols-4 gap-8">
<div class="text-center group cursor-pointer">
<div class="text-4xl font-black font-headline text-primary-container mb-2 group-hover:scale-110 transition-transform">7</div>
<div class="text-sm font-medium text-zinc-400 uppercase tracking-wider">Movies</div>
</div>
<div class="text-center group cursor-pointer">
<div class="text-4xl font-black font-headline text-primary-container mb-2 group-hover:scale-110 transition-transform">4</div>
<div class="text-sm font-medium text-zinc-400 uppercase tracking-wider">Cinemas</div>
</div>
<div class="text-center group cursor-pointer">
<div class="text-4xl font-black font-headline text-primary-container mb-2 group-hover:scale-110 transition-transform">24/7</div>
<div class="text-sm font-medium text-zinc-400 uppercase tracking-wider">Support</div>
</div>
<div class="text-center group cursor-pointer">
<div class="text-4xl font-black font-headline text-primary-container mb-2 group-hover:scale-110 transition-transform">4K</div>
<div class="text-sm font-medium text-zinc-400 uppercase tracking-wider">Quality</div>
</div>
</div>
</section>
<!-- Recommended Movies Section -->
<section class="max-w-screen-2xl mx-auto px-12 py-12">
<div class="flex items-end justify-between mb-12">
<div>
<h2 class="text-sm font-bold text-red-600 uppercase tracking-[0.3em] mb-2 animate-fade-in">Curated for You</h2>
<h3 class="text-5xl font-headline font-extrabold tracking-tight animate-slide-in-right">Recommended Movies</h3>
</div>
<a href="movies.php" class="text-zinc-400 font-bold hover:text-red-500 transition-all flex items-center gap-2 hover:scale-105 transform group">
    VIEW ALL <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
<?php foreach ($featuredMovies as $index => $movie): ?>
<!-- Movie Card -->
<div class="group cursor-pointer animate-fade-in-up" style="animation-delay: <?php echo $index * 0.1; ?>s">
<div class="relative aspect-[2/3] rounded-3xl overflow-hidden mb-6 editorial-shadow transform group-hover:scale-105 transition-all duration-500 hover:shadow-2xl hover:shadow-red-500/20">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" data-alt="<?php echo htmlspecialchars($movie['title']); ?>" src="<?php echo htmlspecialchars($movie['poster_url'] ?? 'https://via.placeholder.com/300x450?text=' . urlencode($movie['title'])); ?>"/>
<div class="absolute top-4 right-4 glass-panel px-3 py-1.5 rounded-xl flex items-center gap-1.5 animate-bounce">
<span class="material-symbols-outlined text-yellow-500 text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-xs font-bold text-white"><?php echo number_format($movie['rating'], 1); ?></span>
</div>
<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
<a href="movie_details.php?id=<?php echo $movie['id']; ?>" class="w-full bg-primary-container text-white py-3 rounded-xl font-bold uppercase tracking-tighter transform translate-y-4 group-hover:translate-y-0 transition-all hover:shadow-lg hover:shadow-red-500/30 hover:scale-105">
Book Now
</a>
</div>
</div>
<h4 class="text-xl font-headline font-bold group-hover:text-red-500 transition-colors transform group-hover:scale-105 origin-left"><?php echo htmlspecialchars($movie['title']); ?></h4>
<p class="text-zinc-500 text-sm font-medium mt-1 group-hover:text-zinc-300 transition-colors"><?php echo htmlspecialchars($movie['genre']); ?></p>
</div>
<?php endforeach; ?>
</div>
</section>
<!-- Newsletter / Editorial CTA -->
<section class="max-w-screen-2xl mx-auto px-12 py-32">
<div class="relative rounded-[3rem] overflow-hidden bg-zinc-900 px-12 py-24 flex items-center justify-between">
<div class="relative z-10 max-w-xl">
<h2 class="text-5xl font-headline font-extrabold mb-6">The Director's Cut</h2>
<p class="text-lg text-zinc-400 mb-10 leading-relaxed">Join our exclusive cinematic community. Get early access to premieres, member-only screenings, and editorial insights delivered weekly.</p>
<div class="flex gap-4">
<input class="flex-1 bg-surface-container-high border-none rounded-2xl px-6 py-4 focus:ring-1 ring-primary" placeholder="Enter your email" type="email"/>
<button class="bg-primary text-on-primary px-8 py-4 rounded-2xl font-bold hover:brightness-110 transition-all">Subscribe</button>
</div>
</div>
<div class="hidden lg:block absolute right-0 top-0 bottom-0 w-1/3 opacity-30">
<img class="w-full h-full object-cover" data-alt="Close up of a vintage movie projector with light beams cutting through dust particles in a dark theater" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAPXM3UOLCujucuN4bjfLzbPMpjdP2t71yGPQduGaPifu8CYMLhuzsJdSPykjQ_JKqgZXjog3kh3kFcyNwRMuyQWDsiYyWf_JeuxoVJgKoq_nncnZOuu95djvy3k8iduSk0x4RJDQtvOmATTQdeqjsN5DZfG7Q1Ae2MHkvV7pAodcgJtV2hx3okU-TxxC7Y1rl1__3CXqfCkpeU_RfU48e7gm9FsOrTxx20kvatj0iQkkUNL2JHPJD6y2w0tjS45ciXVklsZpvARSM"/>
</div>
</div>
</section>
</main>

<?php require_once 'includes/footer.php'; ?>