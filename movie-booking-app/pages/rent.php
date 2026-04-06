<?php
$pageTitle = 'Digital Store - CINEFLOW';
require_once '../includes/header.php';
require_once '../includes/functions.php';

$search = $_GET['search'] ?? null;
$rentableMovies = getRentableMovies($search);
?>

<main class="pt-32 pb-24 min-h-screen bg-surface">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-12">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
            <div class="space-y-4">
                <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] flex items-center gap-3">
                    <span class="material-symbols-outlined text-sm icon-filled">storefront</span> <?php echo $search ? "Search Results" : "DIGITAL STORE"; ?>
                </h2>
                <h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white uppercase leading-none italic">
                    <?php echo $search ? "Matches: " . htmlspecialchars($search) : "CineFlow Premiere"; ?>
                </h1>
                <p class="text-zinc-400 text-lg font-medium max-w-2xl leading-relaxed italic">Experience cinema-grade entertainment from the comfort of your home. Rent the legendary classics and modern masterpieces in 4K Ultra HD.</p>
            </div>
            
            <div class="flex items-center gap-4 bg-surface-container-low p-2 rounded-2xl border border-white/5">
                <div class="px-6 py-3 bg-primary/10 border border-primary/20 rounded-xl">
                    <span class="text-xs font-bold text-primary uppercase tracking-widest">Active Rentals: 
                        <?php echo count(getUserRentals($_SESSION['user_id'] ?? 0)); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Movie Grid -->
        <?php if (!empty($rentableMovies)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php foreach ($rentableMovies as $movie): 
                $isRented = isMovieRented($movie['id'], $_SESSION['user_id'] ?? 0);
            ?>
            <div class="group relative bg-surface-container-low rounded-[2.5rem] overflow-hidden border border-white/5 hover:border-primary/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/10">
                <!-- Backdrop/Poster Container -->
                <div class="relative aspect-video overflow-hidden">
                    <img src="<?php echo BASE_URL . htmlspecialchars($movie['backdrop_url'] ?? $movie['poster_url']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-surface-container-low via-transparent to-transparent"></div>
                    
                    <?php if ($isRented): ?>
                    <div class="absolute top-4 right-4 bg-emerald-500 text-white px-4 py-1.5 rounded-full text-[10px] font-black tracking-widest uppercase shadow-lg">Owned</div>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <div class="p-8 md:p-10 space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]"><?php echo htmlspecialchars($movie['format']); ?></span>
                        <div class="w-1 h-1 rounded-full bg-zinc-700"></div>
                        <span class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]"><?php echo htmlspecialchars($movie['genre']); ?></span>
                    </div>
                    
                    <h3 class="text-3xl font-headline font-black text-white uppercase tracking-tighter leading-none"><?php echo htmlspecialchars($movie['title']); ?></h3>
                    
                    <p class="text-zinc-400 text-sm line-clamp-2 font-medium leading-relaxed"><?php echo htmlspecialchars($movie['description']); ?></p>
                    
                    <div class="pt-4 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Rental Price</span>
                            <span class="text-2xl font-black text-white">₹<?php echo number_format($movie['rent_price'], 0); ?></span>
                        </div>
                        
                        <?php if ($isRented): ?>
                        <a href="<?php echo BASE_URL; ?>pages/watch.php?id=<?php echo $movie['id']; ?>" class="bg-emerald-500 text-white px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-widest hover:brightness-110 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm icon-filled">play_circle</span> Watch
                        </a>
                        <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>pages/movie_details.php?id=<?php echo $movie['id']; ?>" class="bg-white text-black px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-widest hover:bg-primary hover:text-white transition-all">Rent Now</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="bg-surface-container-low rounded-[3rem] p-20 text-center border border-dashed border-white/10">
            <span class="material-symbols-outlined text-6xl text-zinc-700 mb-6">movie_filter</span>
            <h2 class="text-2xl font-headline font-black text-zinc-400 uppercase tracking-widest">Coming Soon to Premiere</h2>
            <p class="text-zinc-500 mt-2">We're curating the best cinematic experiences for you.</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
