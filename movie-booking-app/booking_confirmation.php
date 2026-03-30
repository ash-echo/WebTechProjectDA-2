<?php
$pageTitle = 'Booking Confirmed - AUTEUR Cinema';
require_once 'includes/header.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireLogin();

$bookingId = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';
if (!$bookingId) {
    header('Location: movies.php');
    exit();
}

$booking = getBookingById($bookingId);
if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    header('Location: movies.php');
    exit();
}

$show = getShowById($booking['show_id']);
$bookingDetails = getBookingDetails($bookingId);
$user = getUserById($_SESSION['user_id']);
?>

<main class="pt-24 pb-40 px-6 max-w-4xl mx-auto">
<!-- Success Header -->
<div class="text-center mb-16">
<div class="inline-flex items-center justify-center w-20 h-20 bg-green-500/20 rounded-full mb-6">
<span class="material-symbols-outlined text-4xl text-green-400">check_circle</span>
</div>
<h1 class="text-5xl md:text-6xl font-black font-headline tracking-tighter uppercase leading-none mb-4">
    Booking Confirmed
</h1>
<p class="text-xl text-zinc-400 font-medium">
    Your tickets have been booked successfully!
</p>
<div class="mt-6 px-6 py-3 bg-surface-container-high rounded-xl border border-green-500/20 inline-block">
<span class="text-green-400 font-bold">Booking ID:</span>
<span class="text-zinc-100 font-mono ml-2"><?php echo htmlspecialchars($bookingId); ?></span>
</div>
</div>

<div class="grid lg:grid-cols-2 gap-12">
<!-- Booking Details -->
<div class="space-y-8">
<div class="glass-panel rounded-2xl p-8">
<h2 class="text-2xl font-black font-headline uppercase tracking-tight mb-6">Movie Details</h2>

<div class="space-y-4">
<div class="flex items-start gap-4">
<div class="w-16 h-24 bg-zinc-800 rounded-lg flex-shrink-0 overflow-hidden">
<img src="<?php echo htmlspecialchars($show['poster_url']); ?>" alt="<?php echo htmlspecialchars($show['title']); ?>" class="w-full h-full object-cover">
</div>
<div class="flex-1 space-y-2">
<h3 class="text-xl font-bold text-zinc-100"><?php echo htmlspecialchars($show['title']); ?></h3>
<p class="text-zinc-400"><?php echo htmlspecialchars($show['format']); ?> • <?php echo htmlspecialchars($show['duration']); ?> min</p>
<p class="text-zinc-500 text-sm"><?php echo htmlspecialchars($show['description']); ?></p>
</div>
</div>
</div>

<div class="grid grid-cols-2 gap-4 pt-4 border-t border-outline-variant/10">
<div>
<span class="block text-sm font-bold text-zinc-400 uppercase tracking-wider">Date & Time</span>
<span class="text-zinc-100 font-semibold"><?php echo date('l, F j, Y', strtotime($show['show_date'])); ?> at <?php echo date('g:i A', strtotime($show['show_time'])); ?></span>
</div>
<div>
<span class="block text-sm font-bold text-zinc-400 uppercase tracking-wider">Cinema</span>
<span class="text-zinc-100 font-semibold"><?php echo htmlspecialchars($show['theater_name']); ?></span>
</div>
</div>
</div>

<!-- Seats & Pricing -->
<div class="glass-panel rounded-2xl p-8">
<h3 class="text-xl font-bold font-headline uppercase tracking-tight mb-6">Your Seats</h3>

<div class="space-y-4">
<?php foreach ($bookingDetails as $detail): ?>
<div class="flex justify-between items-center py-3 border-b border-outline-variant/10 last:border-b-0">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-zinc-400">chair</span>
<span class="font-semibold text-zinc-100">Seat <?php echo htmlspecialchars($detail['seat_row'] . $detail['seat_number']); ?></span>
<span class="text-zinc-400 text-sm"><?php echo htmlspecialchars($detail['seat_type']); ?> Tier</span>
</div>
<span class="font-semibold text-zinc-100"><?php echo formatCurrency($detail['price']); ?></span>
</div>
<?php endforeach; ?>

<div class="pt-4 border-t border-outline-variant/10">
<div class="flex justify-between items-center text-lg font-black">
<span class="text-zinc-100">Total Paid</span>
<span class="text-green-400 font-headline"><?php echo formatCurrency($booking['total_amount']); ?></span>
</div>
</div>
</div>
</div>
</div>

<!-- Customer Information -->
<div class="space-y-8">
<div class="glass-panel rounded-2xl p-8">
<h3 class="text-xl font-bold font-headline uppercase tracking-tight mb-6">Customer Information</h3>

<div class="space-y-4">
<div>
<span class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-1">Name</span>
<span class="text-zinc-100 font-semibold"><?php echo htmlspecialchars($user['name']); ?></span>
</div>
<div>
<span class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-1">Email</span>
<span class="text-zinc-100 font-semibold"><?php echo htmlspecialchars($user['email']); ?></span>
</div>
<div>
<span class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-1">Booking Date</span>
<span class="text-zinc-100 font-semibold"><?php echo date('F j, Y \a\t g:i A', strtotime($booking['booking_date'])); ?></span>
</div>
</div>
</div>

<!-- Important Information -->
<div class="glass-panel rounded-2xl p-8">
<h3 class="text-xl font-bold font-headline uppercase tracking-tight mb-6">Important Information</h3>

<div class="space-y-4 text-sm text-zinc-400">
<div class="flex items-start gap-3">
<span class="material-symbols-outlined text-yellow-400 mt-0.5">warning</span>
<div>
<p class="font-semibold text-zinc-300 mb-1">Arrival Time</p>
<p>Please arrive at least 15 minutes before showtime for seating assistance.</p>
</div>
</div>

<div class="flex items-start gap-3">
<span class="material-symbols-outlined text-blue-400 mt-0.5">smartphone</span>
<div>
<p class="font-semibold text-zinc-300 mb-1">Digital Tickets</p>
<p>Your booking confirmation has been sent to your email. Show this page or the email at the cinema.</p>
</div>
</div>

<div class="flex items-start gap-3">
<span class="material-symbols-outlined text-red-400 mt-0.5">cancel</span>
<div>
<p class="font-semibold text-zinc-300 mb-1">Cancellation Policy</p>
<p>This booking is non-refundable. No changes or cancellations are permitted.</p>
</div>
</div>
</div>
</div>

<!-- Action Buttons -->
<div class="flex flex-col sm:flex-row gap-4 mt-8">
<a href="movies.php" class="flex-1 px-8 py-4 bg-surface-container-high border border-outline-variant/30 rounded-xl font-bold uppercase tracking-wider text-sm text-zinc-100 hover:bg-zinc-800 transition-all text-center">
    Book Another Movie
</a>
<button onclick="window.print()" class="flex-1 px-8 py-4 bg-gradient-to-br from-primary-container to-[#930000] text-on-primary-container font-black uppercase tracking-[0.2em] text-sm rounded-xl shadow-xl shadow-red-900/40 hover:scale-[1.02] active:scale-[0.98] transition-all">
    Print Tickets
</button>
</div>
</div>
</div>
</main>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    main, main * {
        visibility: visible;
    }
    main {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .glass-panel {
        background: white !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: none !important;
    }
    .text-zinc-100 { color: #111827 !important; }
    .text-zinc-400 { color: #6b7280 !important; }
    .text-zinc-500 { color: #6b7280 !important; }
    .bg-surface-container-high { background: #f9fafb !important; }
    .border-outline-variant\/10 { border-color: #e5e7eb !important; }
    .border-outline-variant\/30 { border-color: #d1d5db !important; }
}
</style>

<?php require_once 'includes/footer.php'; ?>