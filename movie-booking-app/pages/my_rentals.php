<?php
$pageTitle = 'My Library - CINEFLOW';
require_once '../includes/header.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireLogin(); // Ensure user is logged in

$userRentals = getUserRentals($_SESSION['user_id']);
?>

<main class="pt-32 pb-40 min-h-screen bg-surface">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-12">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
            <div class="space-y-4">
                <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] flex items-center gap-3">
                    <span class="material-symbols-outlined text-sm icon-filled">inventory_2</span> MY COLLECTION
                </h2>
                <h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white uppercase italic leading-none">Your Digital Library</h1>
                <p class="text-zinc-400 text-lg font-medium max-w-2xl leading-relaxed">Your curated collection of premium cinematic experiences. Watch, rewatch, and enjoy your rentals before they expire.</p>
            </div>
            
            <a href="<?php echo BASE_URL; ?>pages/rent.php" class="bg-white/5 border border-white/10 hover:border-primary/50 text-white px-8 py-4 rounded-full font-black text-xs uppercase tracking-widest hover:bg-primary/10 transition-all flex items-center gap-3">
                <span class="material-symbols-outlined text-sm">add_shopping_cart</span> Visit Store
            </a>
        </div>

        <!-- Rentals Grid -->
        <?php if (!empty($userRentals)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach ($userRentals as $rental): 
                $expiresAt = strtotime($rental['expires_at']);
                $timeLeft = $expiresAt - time();
                $hoursLeft = round($timeLeft / 3600);
                $isCritical = $hoursLeft < 12;
            ?>
            <div class="group relative bg-surface-container-low rounded-[2.5rem] overflow-hidden border border-white/5 hover:border-white/20 transition-all duration-500 flex flex-col h-full shadow-2xl">
                <!-- Thumbnail -->
                <div class="relative aspect-video overflow-hidden">
                    <img src="<?php echo BASE_URL . htmlspecialchars($rental['backdrop_url'] ?? $rental['poster_url']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-surface-container-low via-transparent to-transparent"></div>
                    
                    <div class="absolute top-4 right-4 bg-emerald-500/20 text-emerald-500 border border-emerald-500/30 px-4 py-1.5 rounded-full text-[10px] font-black tracking-widest uppercase backdrop-blur-md">Active</div>
                </div>

                <!-- Info -->
                <div class="p-8 flex-1 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]"><?php echo htmlspecialchars($rental['genre']); ?></span>
                        </div>
                        <h3 class="text-2xl font-headline font-black text-white uppercase italic tracking-tighter leading-none group-hover:text-primary transition-colors"><?php echo htmlspecialchars($rental['title']); ?></h3>
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/5 space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1 italic">Expires In</span>
                                <span class="text-sm font-black <?php echo $isCritical ? 'text-red-500' : 'text-zinc-200'; ?> flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                    <?php echo $hoursLeft; ?> Hours
                                </span>
                            </div>
                            <a href="<?php echo BASE_URL; ?>pages/watch.php?id=<?php echo $rental['movie_id']; ?>" class="w-14 h-14 bg-white text-black rounded-2xl flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-lg active:scale-95 group/play relative overflow-hidden">
                                <div class="absolute inset-0 bg-primary/10 translate-y-full group-hover/play:translate-y-0 transition-transform"></div>
                                <span class="material-symbols-outlined text-2xl icon-filled relative z-10">play_arrow</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="bg-surface-container-low rounded-[4rem] p-24 text-center border border-dashed border-white/10 space-y-8">
            <div class="w-24 h-24 rounded-full bg-surface flex items-center justify-center mx-auto border border-white/5 shadow-2xl">
                <span class="material-symbols-outlined text-4xl text-zinc-700">movie_off</span>
            </div>
            <div class="space-y-2">
                <h2 class="text-3xl font-headline font-black text-white uppercase tracking-widest italic">Digital Library Empty</h2>
                <p class="text-zinc-500 max-w-lg mx-auto font-medium">You haven't rented any movies yet. Explore our Premiere Store to start your collection.</p>
            </div>
            <a href="<?php echo BASE_URL; ?>pages/rent.php" class="inline-flex items-center gap-3 bg-primary text-white px-10 py-5 rounded-full font-black text-sm uppercase tracking-widest hover:brightness-110 transition-all shadow-xl shadow-primary/20">
                Browse Premiere Store
            </a>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
