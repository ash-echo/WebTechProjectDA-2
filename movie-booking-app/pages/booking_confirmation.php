<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireLogin();

$pageTitle = 'Booking Confirmed - CINEFLOW';
require_once '../includes/header.php';

$bookingId = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';
if (!$bookingId) {
    header('Location: ' . BASE_URL . 'pages/movies.php');
    exit();
}

$booking = getBookingById($bookingId);
if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    header('Location: ' . BASE_URL . 'pages/movies.php');
    exit();
}

$show = getShowById($booking['show_id']);
$bookingDetails = getBookingDetails($bookingId);
$user = getUserById($_SESSION['user_id']);
?>

<main class="pt-24 pb-40 px-6 max-w-4xl mx-auto relative z-10 animate-fly-in">
    <!-- Confetti / Success Background -->
    <div class="absolute inset-0 z-0 bg-gradient-to-t from-transparent via-green-500/5 to-transparent pointer-events-none"></div>

<!-- Success Header -->
<div class="text-center mb-16 relative z-10">
<div class="inline-flex items-center justify-center w-24 h-24 bg-green-500/10 rounded-full mb-8 relative">
    <div class="absolute inset-0 bg-green-500/20 rounded-full animate-ping"></div>
    <span class="material-symbols-outlined text-6xl text-green-500 icon-filled drop-shadow-[0_0_15px_rgba(34,197,94,0.5)]">check_circle</span>
</div>
<h1 class="text-5xl md:text-7xl font-black font-headline tracking-tighter uppercase leading-none mb-4 text-white">
    Booking Complete
</h1>
<p class="text-xl text-zinc-400 font-medium">
    Your tickets for <strong class="text-white"><?php echo htmlspecialchars($show['title']); ?></strong> are ready!
</p>
<div class="mt-8 px-8 py-4 bg-surface-container-high rounded-full border border-green-500/20 inline-flex items-center gap-4">
    <span class="text-zinc-500 font-black uppercase tracking-widest text-xs">Booking ID</span>
    <span class="text-green-400 font-mono text-xl font-bold tracking-wider leading-none"><?php echo htmlspecialchars($bookingId); ?></span>
</div>
</div>

<div class="flex flex-col items-center gap-6 relative z-10">
    <div class="bg-surface-container-low rounded-3xl p-8 md:p-12 border border-outline-variant/10 shadow-2xl w-full max-w-2xl text-center">
        <span class="material-symbols-outlined text-4xl text-primary mb-4 icon-filled">confirmation_number</span>
        <h2 class="text-2xl font-black font-headline tracking-tight uppercase text-white mb-2">View Your Tickets</h2>
        <p class="text-zinc-400 mb-8 max-w-md mx-auto">Access your digital tickets, scan QR codes at the cinema, and view your purchase history in your dashboard.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo BASE_URL; ?>pages/tickets.php" class="px-10 py-5 bg-primary text-white font-black uppercase tracking-[0.2em] text-sm rounded-2xl shadow-[0_0_40px_rgba(231,26,15,0.4)] hover:brightness-110 active:scale-95 transition-all text-center">
                Go to My Tickets
            </a>
            <a href="<?php echo BASE_URL; ?>pages/movies.php" class="px-10 py-5 bg-surface-container-high border border-outline-variant/30 rounded-2xl font-bold uppercase tracking-[0.2em] text-sm text-zinc-300 hover:bg-white hover:text-black hover:border-white transition-all text-center">
                Browse Movies
            </a>
        </div>
    </div>
</div>
</main>

<script>
// Small celebratory confetti effect if running locally
if (typeof window.confetti === 'function') {
    confetti({
        particleCount: 150,
        spread: 100,
        origin: { y: 0.3 },
        colors: ['#22c55e', '#ffffff', '#e71a0f']
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>