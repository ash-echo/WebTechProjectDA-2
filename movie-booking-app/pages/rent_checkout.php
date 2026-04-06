<?php
$pageTitle = 'Checkout - CINEFLOW Premiere';
require_once '../includes/header.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireLogin(); // Ensure user is logged in

$movieId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$movie = getMovieById($movieId);

if (!$movie || !$movie['is_rentable']) {
    header('Location: ' . BASE_URL . 'pages/rent.php');
    exit();
}

// Check if already rented
if (isMovieRented($movieId, $_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'pages/watch.php?id=' . $movieId);
    exit();
}

$user = getUserById($_SESSION['user_id']);
?>

<main class="pt-32 pb-40 min-h-screen bg-surface">
    <div class="max-w-7xl mx-auto px-6 md:px-12 grid grid-cols-1 lg:grid-cols-12 gap-16">
        
        <!-- Left: Checkout Details -->
        <div class="lg:col-span-7 space-y-12">
            <div class="space-y-4">
                <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em] flex items-center gap-3">
                    <span class="material-symbols-outlined text-sm icon-filled">shopping_cart</span> SECURE CHECKOUT
                </h2>
                <h1 class="text-4xl md:text-6xl font-headline font-black tracking-tighter text-white uppercase italic">Complete Rental</h1>
            </div>

            <!-- Rental Agreement -->
            <div class="bg-surface-container-low p-10 rounded-[3rem] border border-white/5 space-y-8">
                <div class="flex items-start gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary">verified_user</span>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-xl font-bold font-headline text-white uppercase italic">Rental Terms</h4>
                        <p class="text-zinc-400 text-sm leading-relaxed">By completing this purchase, you'll have 48 hours to finish watching the movie. The 48-hour window starts the moment you click "Watch Now".</p>
                    </div>
                </div>

                <div class="h-px bg-white/5"></div>

                <div class="flex items-start gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-blue-500">devices</span>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-xl font-bold font-headline text-white uppercase italic">Multi-Device Access</h4>
                        <p class="text-zinc-400 text-sm leading-relaxed">Watch on your TV, tablet, phone, or laptop. Your rental is linked to your CineFlow account.</p>
                    </div>
                </div>
            </div>

            <!-- Payment Simulation -->
            <div class="space-y-6">
                <h3 class="text-xs font-black text-zinc-500 uppercase tracking-widest pl-2">Payment Method</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div class="p-6 rounded-3xl bg-surface-container-high border-2 border-primary border-primary flex items-center justify-between group cursor-pointer shadow-lg shadow-primary/10">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary text-3xl">credit_card</span>
                            <div>
                                <p class="text-white font-bold text-sm">Stored Card</p>
                                <p class="text-zinc-500 text-xs text-zinc-400">ENDING IN 8829</p>
                            </div>
                        </div>
                        <div class="w-6 h-6 rounded-full border-4 border-primary bg-primary shrink-0 transition-all shadow-[0_0_10px_rgba(231,26,15,0.4)]"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Order Summary Sidebar -->
        <div class="lg:col-span-5 relative">
            <div class="sticky top-32 space-y-8">
                <div class="bg-surface-container-low rounded-[3rem] border border-white/5 p-10 space-y-10 shadow-2xl relative overflow-hidden">
                    <!-- Glow -->
                    <div class="absolute -top-20 -right-20 w-40 h-40 bg-primary/20 rounded-full blur-[60px] pointer-events-none"></div>

                    <!-- Movie Info -->
                    <div class="flex items-center gap-6">
                        <img src="<?php echo BASE_URL . htmlspecialchars($movie['poster_url']); ?>" class="w-24 h-32 object-cover rounded-2xl shadow-xl border border-white/10">
                        <div class="space-y-2">
                            <span class="text-[10px] font-black text-primary uppercase tracking-[0.3em]">RENTAL TICKET</span>
                            <h3 class="text-2xl font-headline font-black text-white uppercase italic leading-none"><?php echo htmlspecialchars($movie['title']); ?></h3>
                            <p class="text-zinc-500 text-xs font-bold uppercase tracking-widest"><?php echo htmlspecialchars($movie['format']); ?> • UHD</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-zinc-500 font-bold uppercase tracking-widest italic text-[10px]">Rental Amount</span>
                            <span class="text-white font-black">₹<?php echo number_format($movie['rent_price'], 0); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-zinc-500 font-bold uppercase tracking-widest italic text-[10px]">Digital Surcharge</span>
                            <span class="text-white font-black">₹0</span>
                        </div>
                        <div class="h-px bg-white/5 my-6"></div>
                        <div class="flex justify-between items-end">
                            <span class="text-zinc-500 font-bold uppercase tracking-widest italic text-[10px] pb-1">Total Due</span>
                            <span class="text-5xl font-headline font-black text-primary drop-shadow-[0_0_15px_rgba(231,26,15,0.4)] uppercase">₹<?php echo number_format($movie['rent_price'], 0); ?></span>
                        </div>
                    </div>

                    <form id="rentalForm" class="space-y-6">
                        <input type="hidden" name="movie_id" value="<?php echo $movieId; ?>">
                        
                        <div class="space-y-4">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" required class="w-5 h-5 rounded-md border-white/10 bg-surface-container text-primary focus:ring-0 focus:ring-offset-0 ring-offset-black transition-all">
                                <span class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest leading-none group-hover:text-zinc-300 transition-colors">I AGREE TO THE CINEFLOW TERMS OF SERVICE</span>
                            </label>
                        </div>

                        <button type="submit" id="payBtn" class="w-full bg-primary text-white py-6 rounded-2xl font-black text-sm uppercase tracking-[0.3em] shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-4">
                            <span id="btnText">Secure Purchase</span>
                            <span id="spinner" class="material-symbols-outlined text-xl hidden animate-spin">sync</span>
                        </button>
                    </form>

                    <div class="flex items-center justify-center gap-8 opacity-40 grayscale group hover:grayscale-0 transition-all duration-700">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-4">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-6">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-4">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.getElementById('rentalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('payBtn');
    const text = document.getElementById('btnText');
    const spinner = document.getElementById('spinner');
    
    btn.disabled = true;
    text.textContent = 'Processing Payment...';
    spinner.classList.remove('hidden');
    
    // Simulate payment delay
    setTimeout(() => {
        // Send to API (which we need to create)
        const formData = new FormData();
        formData.append('movie_id', <?php echo $movieId; ?>);
        
        fetch('<?php echo BASE_URL; ?>api/process_rental.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('Rental Successful! Enjoy your movie.', 'success');
                setTimeout(() => {
                    window.location.href = '<?php echo BASE_URL; ?>pages/watch.php?id=' + <?php echo $movieId; ?>;
                }, 1500);
            } else {
                showToast(data.message, 'error');
                btn.disabled = false;
                text.textContent = 'Secure Purchase';
                spinner.classList.add('hidden');
            }
        });
    }, 2000);
});
</script>

<?php require_once '../includes/footer.php'; ?>
