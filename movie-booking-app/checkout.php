<?php
$pageTitle = 'Checkout - AUTEUR Cinema';
require_once 'includes/header.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireLogin();

$showId = isset($_GET['show_id']) ? (int)$_GET['show_id'] : 0;
$show = getShowById($showId);

if (!$show) {
    header('Location: movies.php');
    exit();
}

$selectedSeats = isset($_SESSION['selected_seats']) ? $_SESSION['selected_seats'] : [];
if (empty($selectedSeats)) {
    header('Location: seat_selection.php?show_id=' . $showId);
    exit();
}

// Verify seats are still locked
$seats = getSeatStatusForShow($showId);
$lockedSeats = array_filter($seats, function($seat) use ($selectedSeats) {
    return in_array($seat['id'], $selectedSeats) && $seat['status'] === 'locked' && $seat['locked_by'] == $_SESSION['user_id'];
});

if (count($lockedSeats) !== count($selectedSeats)) {
    // Seats not properly locked, redirect back
    unset($_SESSION['selected_seats']);
    header('Location: seat_selection.php?show_id=' . $showId . '&error=seats_unavailable');
    exit();
}

// Calculate total
$totalAmount = count($selectedSeats) * $show['price'];
$serviceFee = 2.50;
$grandTotal = $totalAmount + $serviceFee;

// Get user info
$user = getUserById($_SESSION['user_id']);
?>

<main class="pt-24 pb-40 px-6 max-w-4xl mx-auto">
<!-- Header Section -->
<header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
<div class="space-y-2">
<div class="flex items-center gap-3 text-primary uppercase tracking-widest text-xs font-bold font-headline">
<span class="flex h-2 w-2 rounded-full bg-primary animate-pulse"></span>
    Checkout
</div>
<h1 class="text-5xl md:text-6xl font-black font-headline tracking-tighter uppercase leading-none">
    Complete Your Booking
</h1>
<p class="text-zinc-400 font-medium flex items-center gap-4">
<span><?php echo htmlspecialchars($show['title']); ?></span>
<span class="w-1 h-1 bg-zinc-700 rounded-full"></span>
<span><?php echo date('D, M j', strtotime($show['show_date'])); ?> at <?php echo date('g:i A', strtotime($show['show_time'])); ?></span>
</p>
</div>
</header>

<div class="grid lg:grid-cols-2 gap-12">
<!-- Order Summary -->
<div class="space-y-8">
<div class="glass-panel rounded-2xl p-8">
<h2 class="text-2xl font-black font-headline uppercase tracking-tight mb-6">Order Summary</h2>

<div class="space-y-4">
<div class="flex justify-between items-center py-3 border-b border-outline-variant/10">
<span class="text-zinc-400"><?php echo count($selectedSeats); ?> × Standard Seat</span>
<span class="font-semibold text-zinc-100"><?php echo formatCurrency($totalAmount); ?></span>
</div>
<div class="flex justify-between items-center py-3 border-b border-outline-variant/10">
<span class="text-zinc-400">Service Fee</span>
<span class="font-semibold text-zinc-100"><?php echo formatCurrency($serviceFee); ?></span>
</div>
<div class="flex justify-between items-center py-4 text-lg font-black">
<span class="text-zinc-100">Total</span>
<span class="text-primary font-headline"><?php echo formatCurrency($grandTotal); ?></span>
</div>
</div>
</div>

<!-- Selected Seats -->
<div class="glass-panel rounded-2xl p-8">
<h3 class="text-xl font-bold font-headline uppercase tracking-tight mb-6">Selected Seats</h3>
<div class="flex flex-wrap gap-3">
<?php foreach ($lockedSeats as $seat): ?>
<div class="px-4 py-2 bg-surface-container-high rounded-xl border border-outline-variant/15">
<span class="font-semibold text-zinc-100"><?php echo $seat['seat_row'] . $seat['seat_number']; ?></span>
<span class="text-zinc-400 text-sm ml-2"><?php echo $seat['seat_type']; ?> Tier</span>
</div>
<?php endforeach; ?>
</div>
</div>
</div>

<!-- Payment Form -->
<div class="space-y-8">
<div class="glass-panel rounded-2xl p-8">
<h2 class="text-2xl font-black font-headline uppercase tracking-tight mb-6">Payment Details</h2>

<form id="payment-form" method="POST" action="api/process_payment.php">
<input type="hidden" name="show_id" value="<?php echo $showId; ?>">
<input type="hidden" name="selected_seats" value="<?php echo htmlspecialchars(json_encode($selectedSeats)); ?>">
<input type="hidden" name="total_amount" value="<?php echo $grandTotal; ?>">

<div class="space-y-6">
<!-- Card Number -->
<div>
<label class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Card Number</label>
<div class="relative">
<input type="text" name="card_number" placeholder="1234 5678 9012 3456" class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors" required>
<div class="absolute right-3 top-1/2 -translate-y-1/2 flex gap-1">
<span class="material-symbols-outlined text-zinc-600">credit_card</span>
</div>
</div>
</div>

<!-- Expiry and CVV -->
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Expiry Date</label>
<input type="text" name="expiry" placeholder="MM/YY" class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors" required>
</div>
<div>
<label class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">CVV</label>
<input type="text" name="cvv" placeholder="123" class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors" required>
</div>
</div>

<!-- Cardholder Name -->
<div>
<label class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Cardholder Name</label>
<input type="text" name="cardholder_name" placeholder="John Doe" class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors" required>
</div>

<!-- Billing Address -->
<div>
<label class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Billing Address</label>
<input type="text" name="billing_address" placeholder="123 Main St, City, State 12345" class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors" required>
</div>

<!-- Email for receipt -->
<div>
<label class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Email for Receipt</label>
<input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors" required>
</div>
</div>

<div class="mt-8 pt-6 border-t border-outline-variant/10">
<button type="submit" class="w-full py-4 bg-gradient-to-br from-primary-container to-[#930000] text-on-primary-container font-black uppercase tracking-[0.2em] text-lg rounded-xl shadow-xl shadow-red-900/40 hover:scale-[1.02] active:scale-[0.98] transition-all">
    Complete Payment
</button>
</div>
</form>
</div>

<!-- Terms and Conditions -->
<div class="glass-panel rounded-2xl p-8">
<h3 class="text-lg font-bold font-headline uppercase tracking-tight mb-4">Terms & Conditions</h3>
<div class="space-y-3 text-sm text-zinc-400">
<p>By completing this purchase, you agree to our terms of service and cancellation policy.</p>
<p>Tickets are non-refundable and non-transferable. Late arrivals may result in denied entry.</p>
<p>All sales are final. No refunds or exchanges.</p>
</div>
</div>
</div>
</div>
</main>

<script>
// Form validation and formatting
document.getElementById('payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation
    const cardNumber = this.card_number.value.replace(/\s/g, '');
    if (cardNumber.length < 13 || cardNumber.length > 19) {
        alert('Please enter a valid card number');
        return;
    }
    
    const expiry = this.expiry.value;
    if (!/^\d{2}\/\d{2}$/.test(expiry)) {
        alert('Please enter expiry date in MM/YY format');
        return;
    }
    
    const cvv = this.cvv.value;
    if (cvv.length < 3 || cvv.length > 4) {
        alert('Please enter a valid CVV');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    // Submit form
    this.submit();
});

// Format card number
document.querySelector('input[name="card_number"]').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
    value = value.replace(/(\d{4})/g, '$1 ').trim();
    e.target.value = value;
});

// Format expiry date
document.querySelector('input[name="expiry"]').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    e.target.value = value;
});
</script>

<?php require_once 'includes/footer.php'; ?>