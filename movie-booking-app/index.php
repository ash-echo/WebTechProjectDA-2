<?php
$pageTitle = 'CINEFLOW | Cinematic Discovery';
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Get featured movies (first 5)
$movies = getAllMovies();
$featuredMovies = array_slice($movies, 0, 5);
$rentableMovies = getRentableMovies();
?>

<main class="pt-20">
<!-- Hero Carousel Section -->
<section class="relative w-full h-[85vh] md:h-[900px] overflow-hidden bg-black" id="hero-carousel">
    <?php if(!empty($featuredMovies)): ?>
        <?php foreach($featuredMovies as $index => $movie): ?>
        <div class="absolute inset-0 carousel-slide transition-all duration-[1500ms] ease-out <?php echo $index === 0 ? 'opacity-100 z-10 scale-100' : 'opacity-0 z-0 scale-[1.05]'; ?>" data-index="<?php echo $index; ?>">
            <img class="w-full h-full object-cover" aria-hidden="true" src="<?php echo BASE_URL . htmlspecialchars($movie['backdrop_url']); ?>"/>
            <div class="absolute inset-0 bg-gradient-to-t from-surface via-transparent to-transparent opacity-90"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-surface/90 via-surface/30 to-transparent"></div>
            
            <div class="absolute inset-0 max-w-screen-2xl mx-auto px-6 md:px-12 flex flex-col justify-end pb-24 md:pb-32">
                <div class="max-w-3xl space-y-6">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="bg-primary text-on-primary-container px-3 py-1 rounded-md text-xs font-bold tracking-widest uppercase">Now Playing</span>
                        <span class="text-zinc-300 text-sm font-medium tracking-wide border border-outline-variant/30 px-3 py-1 rounded-md bg-surface/50 backdrop-blur-sm"><?php echo htmlspecialchars($movie['format']); ?></span>
                    </div>
                    <h1 class="text-6xl md:text-8xl lg:text-9xl font-headline font-extrabold tracking-tighter text-white leading-none uppercase drop-shadow-2xl">
                        <?php echo htmlspecialchars($movie['title']); ?>
                    </h1>
                    <p class="text-lg md:text-xl text-zinc-300 font-medium leading-relaxed max-w-2xl drop-shadow-md hidden md:block">
                        <?php echo htmlspecialchars($movie['description']); ?>
                    </p>
                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        <a href="<?php echo BASE_URL; ?>pages/movie_details.php?id=<?php echo $movie['id']; ?>" class="bg-primary text-white hover:bg-white hover:text-primary px-10 py-4 border border-transparent rounded-full font-black text-sm uppercase tracking-[0.2em] flex items-center gap-3 active:scale-95 transition-all editorial-shadow">
                            <span class="material-symbols-outlined icon-filled">ticket</span>
                            Book Tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- Carousel Indicators -->
        <div class="absolute right-6 md:right-12 bottom-12 md:bottom-24 z-20 flex flex-col gap-3">
            <?php foreach($featuredMovies as $index => $movie): ?>
            <button onclick="goToSlide(<?php echo $index; ?>)" class="indicator-dot w-1.5 h-10 <?php echo $index === 0 ? 'bg-primary shadow-[0_0_15px_rgba(231,26,15,0.8)] h-16' : 'bg-zinc-700 hover:bg-zinc-500'; ?> rounded-full transition-all duration-500"></button>
            <?php endforeach; ?>
        </div>
        
    <?php else: ?>
        <!-- Fallback if Empty DB -->
        <div class="absolute inset-0 bg-surface flex items-center justify-center flex-col">
            <span class="material-symbols-outlined text-6xl text-zinc-600 mb-4 animate-bounce">database</span>
            <h2 class="text-2xl font-bold text-zinc-400">Database needs configuration</h2>
            <p class="text-zinc-500">Run setup_db.php to populate blockbuster movies.</p>
        </div>
    <?php endif; ?>
</section>

<!-- Quick Stats Section -->
<section class="max-w-screen-2xl mx-auto px-6 md:px-12 py-12 md:py-16 border-b border-outline-variant/10 relative z-10 -mt-10 bg-surface/50 backdrop-blur-xl rounded-t-[3rem] shadow-[0_-20px_40px_rgba(0,0,0,0.5)]">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="text-center group cursor-pointer hover:-translate-y-2 transition-transform duration-300">
            <div class="text-3xl md:text-5xl font-black font-headline text-primary mb-2">4K</div>
            <div class="text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] group-hover:text-white transition-colors">Ultra Visuals</div>
        </div>
        <div class="text-center group cursor-pointer hover:-translate-y-2 transition-transform duration-300">
            <div class="text-3xl md:text-5xl font-black font-headline text-primary mb-2">360°</div>
            <div class="text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] group-hover:text-white transition-colors">Dolby Atmos</div>
        </div>
        <div class="text-center group cursor-pointer hover:-translate-y-2 transition-transform duration-300">
            <div class="text-3xl md:text-5xl font-black font-headline text-primary mb-2">VIP</div>
            <div class="text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] group-hover:text-white transition-colors">Lounge Seating</div>
        </div>
        <div class="text-center group cursor-pointer hover:-translate-y-2 transition-transform duration-300">
            <div class="text-3xl md:text-5xl font-black font-headline text-primary mb-2">4</div>
            <div class="text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] group-hover:text-white transition-colors">Venues</div>
        </div>
    </div>
</section>

<!-- Recommended Movies Section -->
<section class="max-w-screen-2xl mx-auto px-6 md:px-12 py-24" id="showtimes">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
        <div>
            <h2 class="text-sm font-bold text-primary opacity-90 uppercase tracking-[0.3em] mb-3 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-primary animate-ping"></div> Now Showing
            </h2>
            <h3 class="text-5xl md:text-6xl font-headline font-black tracking-tighter text-white shadow-sm uppercase">Curated Masterpieces</h3>
        </div>
        <a href="<?php echo BASE_URL; ?>pages/movies.php" class="text-zinc-400 font-bold hover:text-white transition-all flex items-center gap-2 hover:translate-x-2 group text-sm uppercase tracking-widest border border-outline-variant/30 px-6 py-3 rounded-full">
            View All Series <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 md:gap-8">
        <?php foreach ($movies as $movie): ?>
        <!-- Movie Card -->
        <div class="group cursor-pointer">
            <div class="relative aspect-[2/3] rounded-2xl md:rounded-3xl overflow-hidden mb-5 md:mb-6 transform group-hover:scale-[1.03] group-hover:-translate-y-3 transition-all duration-500 shadow-[0_10px_30px_rgba(0,0,0,0.5)] hover:shadow-[0_20px_50px_rgba(231,26,15,0.3)] border border-white/5">
                <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="<?php echo htmlspecialchars($movie['title']); ?>" src="<?php echo BASE_URL . htmlspecialchars($movie['poster_url']); ?>"/>
                
                <div class="absolute top-3 right-3 md:top-4 md:right-4 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 flex items-center gap-1.5 transition-transform group-hover:scale-110">
                    <span class="material-symbols-outlined text-primary text-sm icon-filled">star</span>
                    <span class="text-xs font-bold text-white"><?php echo number_format($movie['rating'], 1); ?></span>
                </div>
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4 md:p-6">
                    <a href="<?php echo BASE_URL; ?>pages/movie_details.php?id=<?php echo $movie['id']; ?>" class="w-full bg-primary text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-[10px] md:text-xs text-center transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 hover:brightness-110">
                        Get Tickets
                    </a>
                </div>
            </div>
            <h4 class="text-lg md:text-xl font-headline font-black text-white group-hover:text-primary transition-colors truncate tracking-wide uppercase"><?php echo htmlspecialchars($movie['title']); ?></h4>
            <p class="text-zinc-500 text-xs md:text-sm font-bold mt-1.5 uppercase tracking-wider truncate"><?php echo htmlspecialchars($movie['genre']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

</section>

<!-- CineFlow Premiere (Rentals) -->
<?php if (!empty($rentableMovies)): ?>
<section class="max-w-screen-2xl mx-auto px-6 md:px-12 py-24 bg-surface-container-lowest rounded-[4rem] border border-white/5 my-12 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-16 relative z-10">
        <div class="space-y-4">
            <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] flex items-center gap-3">
                <span class="material-symbols-outlined text-sm icon-filled">workspace_premium</span> CINEFLOW PREMIERE
            </h2>
            <h3 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white uppercase leading-none">Own the Experience</h3>
            <p class="text-zinc-400 text-lg font-medium max-w-xl">Rent the latest global blockbusters. Watch anywhere, anytime in stunning 4K with Dolby Atmos.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>pages/rent.php" class="bg-white text-black px-10 py-4 rounded-full font-black text-sm uppercase tracking-widest hover:bg-primary hover:text-white transition-all btn-hover-fx mt-8 md:mt-0">Browse Store</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative z-10">
        <?php foreach ($rentableMovies as $rentMovie): ?>
        <div class="group relative aspect-[16/9] rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
            <img src="<?php echo BASE_URL . htmlspecialchars($rentMovie['backdrop_url']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
            
            <div class="absolute inset-0 p-8 md:p-12 flex flex-col justify-end">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-primary/20 text-primary border border-primary/30 px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase">Digital Premiere</span>
                    <span class="text-white/60 text-[10px] font-bold uppercase tracking-widest"><?php echo htmlspecialchars($rentMovie['format']); ?> • <?php echo $rentMovie['duration']; ?> MIN</span>
                </div>
                <h4 class="text-3xl md:text-5xl font-headline font-black text-white uppercase tracking-tighter mb-6"><?php echo htmlspecialchars($rentMovie['title']); ?></h4>
                <div class="flex items-center gap-4">
                    <a href="<?php echo BASE_URL; ?>pages/movie_details.php?id=<?php echo $rentMovie['id']; ?>" class="bg-primary text-white px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-widest hover:brightness-110 transition-all shadow-lg shadow-primary/20">Rent ₹<?php echo number_format($rentMovie['rent_price'], 0); ?></a>
                    <a href="<?php echo BASE_URL; ?>pages/movie_details.php?id=<?php echo $rentMovie['id']; ?>" class="bg-white/10 backdrop-blur-md text-white border border-white/10 px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-widest hover:bg-white/20 transition-all">Details</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Newsletter / Editorial CTA -->
<section class="max-w-screen-2xl mx-auto px-6 md:px-12 py-20 pb-32">
<div class="relative rounded-[3rem] overflow-hidden bg-surface-container-low border border-outline-variant/10 px-8 md:px-16 py-20 flex items-center justify-between group">
<!-- Glow effect -->
<div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[100px] group-hover:bg-primary/20 transition-all duration-700 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>

<div class="relative z-10 max-w-xl">
<h2 class="text-4xl md:text-5xl font-headline font-extrabold mb-6 text-white uppercase tracking-tighter">The Director's Cut</h2>
<p class="text-lg text-zinc-400 mb-10 leading-relaxed font-medium">Join our exclusive cinematic community. Get early access to premieres, member-only screenings, and editorial insights delivered weekly.</p>
<div class="flex flex-col sm:flex-row gap-4">
<input id="newsletterEmail" class="flex-1 bg-surface-container border border-outline-variant/30 rounded-2xl px-6 py-4 focus:ring-1 focus:border-primary focus:ring-primary text-white placeholder-zinc-500 outline-none transition-all font-medium text-lg" placeholder="Enter your email" type="email"/>
<button id="subscribeBtn" class="bg-white text-black px-8 py-4 rounded-2xl font-black btn-hover-fx text-sm uppercase tracking-[0.2em] whitespace-nowrap hover:bg-zinc-200 transition-colors">Subscribe</button>
</div>
<div id="newsletterMessage" class="mt-4 text-sm font-bold tracking-wide transition-all opacity-0"></div>

</div>
</div>
</section>

</main>

<script>
// Hero Carousel Logic
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.indicator-dot');
    if (slides.length === 0) return;
    
    let currentSlide = 0;
    const slideDuration = 6000;
    let timer;

    window.goToSlide = function(index) {
        clearInterval(timer);
        
        // Hide current
        slides[currentSlide].classList.remove('opacity-100', 'z-10', 'scale-100');
        slides[currentSlide].classList.add('opacity-0', 'z-0', 'scale-[1.05]');
        dots[currentSlide].classList.remove('bg-primary', 'shadow-[0_0_15px_rgba(231,26,15,0.8)]', 'h-16');
        dots[currentSlide].classList.add('bg-zinc-700', 'h-10');
        
        currentSlide = index;
        
        // Show new
        slides[currentSlide].classList.add('opacity-100', 'z-10', 'scale-100');
        slides[currentSlide].classList.remove('opacity-0', 'z-0', 'scale-[1.05]');
        dots[currentSlide].classList.add('bg-primary', 'shadow-[0_0_15px_rgba(231,26,15,0.8)]', 'h-16');
        dots[currentSlide].classList.remove('bg-zinc-700', 'h-10');
        
        startTimer();
    };

    function startTimer() {
        timer = setInterval(() => {
            let nextIndex = (currentSlide + 1) % slides.length;
            goToSlide(nextIndex);
        }, slideDuration);
    }
    
    startTimer();
    
    // Newsletter Subscription
    const subscribeBtn = document.getElementById('subscribeBtn');
    const newsletterEmail = document.getElementById('newsletterEmail');
    const newsletterMessage = document.getElementById('newsletterMessage');
    
    if (subscribeBtn) {
        subscribeBtn.addEventListener('click', function() {
            const email = newsletterEmail.value;
            if (!email) {
                showToast('Please enter an email address', 'error');
                return;
            }
            
            subscribeBtn.disabled = true;
            subscribeBtn.textContent = '...';
            
            const formData = new FormData();
            formData.append('email', email);
            
            fetch('<?php echo BASE_URL; ?>api/subscribe.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    newsletterMessage.textContent = data.message;
                    newsletterMessage.classList.replace('text-red-500', 'text-emerald-500');
                    newsletterMessage.classList.replace('opacity-0', 'opacity-100');
                    newsletterEmail.value = '';
                } else {
                    showToast(data.message, 'error');
                    newsletterMessage.textContent = data.message;
                    newsletterMessage.classList.add('text-red-500', 'opacity-100');
                }
            })
            .catch(e => {
                showToast('Network error', 'error');
            })
            .finally(() => {
                subscribeBtn.disabled = false;
                subscribeBtn.textContent = 'SUBSCRIBE';
            });
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>