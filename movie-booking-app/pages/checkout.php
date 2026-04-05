<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireLogin();

$showId = isset($_SESSION['locked_show_id']) ? (int)$_SESSION['locked_show_id'] : (isset($_GET['show_id']) ? (int)$_GET['show_id'] : 0);
$show = getShowById($showId);

if (!$show) {
    header('Location: ' . BASE_URL . 'pages/movies.php');
    exit();
}

$selectedSeats = isset($_SESSION['selected_seats']) ? $_SESSION['selected_seats'] : [];
if (empty($selectedSeats)) {
    header('Location: ' . BASE_URL . 'pages/seat_selection.php?show_id=' . $showId);
    exit();
}

// Map if it's an array of ids or objects
$selectedSeatIds = [];
foreach ($selectedSeats as $s) {
    if (is_array($s) && isset($s['id'])) $selectedSeatIds[] = $s['id'];
    else if (is_numeric($s)) $selectedSeatIds[] = $s;
}

// Verify seats are still locked
$seats = getSeatStatusForShow($showId);
$lockedSeats = array_filter($seats, function($seat) use ($selectedSeatIds) {
    return in_array($seat['id'], $selectedSeatIds) && $seat['status'] === 'locked' && $seat['locked_by'] == $_SESSION['user_id'];
});

if (count($lockedSeats) !== count($selectedSeatIds)) {
    // Seats not properly locked, redirect back
    unset($_SESSION['selected_seats']);
    unset($_SESSION['locked_show_id']);
    header('Location: ' . BASE_URL . 'pages/seat_selection.php?show_id=' . $showId . '&error=seats_unavailable');
    exit();
}

// Calculate total
$totalAmount = 0;
foreach ($lockedSeats as $seat) {
    $price = ($seat['seat_type'] == 'VIP') ? $show['price'] + 5 : $show['price'];
    $totalAmount += $price;
}

$serviceFee = 2.50;
$grandTotal = $totalAmount + $serviceFee;

// Get user info
$user = getUserById($_SESSION['user_id']);

$pageTitle = 'Checkout - AUTEUR Cinema';
require_once '../includes/header.php';
?>

<main class="pt-24 pb-40 px-6 max-w-5xl mx-auto relative z-10 animate-fly-in">
<!-- Header Section -->
<header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-outline-variant/20">
<div class="space-y-4">
<div class="flex items-center gap-3 text-primary uppercase tracking-widest text-[10px] font-black font-headline bg-primary/10 px-4 py-2 rounded-full inline-flex border border-primary/20">
    <span class="flex h-2 w-2 rounded-full bg-primary animate-pulse shadow-lg shadow-primary"></span>
    Checkout
</div>
<h1 class="text-4xl md:text-5xl lg:text-6xl font-black font-headline tracking-tighter uppercase text-white leading-none">
    Complete Payment
</h1>
<p class="text-zinc-400 font-bold flex flex-wrap items-center gap-4 uppercase tracking-widest text-xs">
    <span class="text-white bg-surface-container-high px-3 py-1.5 rounded-lg"><?php echo htmlspecialchars($show['title']); ?></span>
    <span class="flex text-zinc-500 gap-2 items-center"><span class="material-symbols-outlined text-[14px]">calendar_today</span> <?php echo date('D, M j', strtotime($show['show_date'])); ?></span>
    <span class="flex text-zinc-500 gap-2 items-center"><span class="material-symbols-outlined text-[14px]">schedule</span> <?php echo date('H:i', strtotime($show['show_time'])); ?></span>
</p>
</div>
<div class="text-right">
    <p class="text-[10px] font-black uppercase tracking-widest text-zinc-500 mb-1">Total to Pay</p>
    <p class="text-4xl font-black text-primary font-headline"><?php echo formatCurrency($grandTotal); ?></p>
</div>
</header>

<div class="grid lg:grid-cols-12 gap-10">
<!-- Left Column: Details -->
<div class="lg:col-span-5 space-y-8">
    <div class="bg-surface-container-low rounded-3xl p-8 border border-outline-variant/10 shadow-2xl">
        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-zinc-500 mb-6 flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">receipt_long</span> Order Summary</h3>
        
        <div class="space-y-4 mb-8">
            <div class="flex justify-between items-center text-sm py-3 border-b border-outline-variant/10">
                <span class="text-zinc-400 font-medium"><?php echo count($lockedSeats); ?> × Tickets</span>
                <span class="font-bold text-white"><?php echo formatCurrency($totalAmount); ?></span>
            </div>
            <div class="flex justify-between items-center text-sm py-3 border-b border-outline-variant/10">
                <span class="text-zinc-400 font-medium">Service Fee</span>
                <span class="font-bold text-white"><?php echo formatCurrency($serviceFee); ?></span>
            </div>
            <div class="flex justify-between items-center py-4 text-xl font-black font-headline">
                <span class="text-zinc-300">Total</span>
                <span class="text-primary"><?php echo formatCurrency($grandTotal); ?></span>
            </div>
        </div>

        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-zinc-500 mb-6 mt-8 flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">event_seat</span> Selected Seats</h3>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($lockedSeats as $seat): ?>
            <div class="px-4 py-2.5 bg-surface-container-high rounded-xl border border-outline-variant/20 flex flex-col justify-center items-center gap-1 min-w-[70px]">
                <span class="font-black text-white text-lg leading-none"><?php echo $seat['seat_row'] . $seat['seat_number']; ?></span>
                <span class="text-zinc-500 text-[9px] uppercase tracking-widest font-bold"><?php echo $seat['seat_type']; ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Right Column: Payment Form -->
<div class="lg:col-span-7">
<div class="bg-surface-container-low rounded-3xl p-8 border border-outline-variant/10 shadow-2xl relative overflow-hidden">
<!-- Glow effect -->
<div class="absolute -right-20 -top-20 w-64 h-64 bg-primary/20 rounded-full blur-[80px] pointer-events-none"></div>

<h2 class="text-xs font-black uppercase tracking-[0.2em] text-zinc-500 mb-8 flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">credit_card</span> Payment Details</h2>

<form id="payment-form" class="relative z-10">
<input type="hidden" name="show_id" value="<?php echo $showId; ?>">
<input type="hidden" name="selected_seats" value="<?php echo htmlspecialchars(json_encode(array_values($lockedSeats))); ?>">
<input type="hidden" name="total_amount" value="<?php echo $grandTotal; ?>">

<div class="space-y-6">
<!-- Card Number -->
<div>
<label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-3">Card Number</label>
<div class="relative group">
<input type="text" name="card_number" placeholder="0000 0000 0000 0000" class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant/20 rounded-2xl text-white placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all font-mono text-lg tracking-wider group-hover:border-outline-variant/50" required>
<div class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500 group-focus-within:text-primary transition-colors flex gap-2">
    <svg class="w-8 h-8 opacity-80" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
</div>
</div>
</div>

<!-- Expiry and CVV -->
<div class="grid grid-cols-2 gap-6">
<div>
<label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-3">Valid Thru</label>
<input type="text" name="expiry" placeholder="MM/YY" class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant/20 rounded-2xl text-white placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all font-mono text-lg tracking-wider text-center" required>
</div>
<div>
<label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-3">CVC</label>
<div class="relative">
    <input type="password" name="cvv" placeholder="•••" class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant/20 rounded-2xl text-white placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all font-mono text-lg tracking-wider text-center" required>
</div>
</div>
</div>

<!-- Cardholder Name -->
<div>
<label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-3">Name on Card</label>
<input type="text" name="cardholder_name" placeholder="John Doe" class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant/20 rounded-2xl text-white placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all font-medium uppercase tracking-widest" required>
</div>

<!-- Email for receipt -->
<div>
<label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-3">Email for Receipt</label>
<input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant/20 rounded-2xl text-white placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all font-medium" required>
</div>
</div>

<div class="mt-10 pt-8 border-t border-outline-variant/10">
<button type="submit" id="submitBtn" class="relative overflow-hidden w-full py-5 bg-primary text-white font-black uppercase tracking-[0.2em] text-sm rounded-2xl shadow-[0_0_40px_rgba(231,26,15,0.4)] hover:brightness-110 active:scale-95 transition-all group flex items-center justify-center gap-3">
    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
    <span class="material-symbols-outlined relative z-10" id="btnIcon">lock</span>
    <span class="relative z-10" id="btnText">Pay <?php echo formatCurrency($grandTotal); ?></span>
    <span class="material-symbols-outlined relative z-10 hidden animate-spin" id="btnSpinner">progress_activity</span>
</button>
<p class="text-center text-zinc-600 text-[10px] font-bold uppercase tracking-widest mt-4 flex items-center justify-center gap-2">
    <span class="material-symbols-outlined text-[14px]">shield</span> Payments are secure and encrypted
</p>
</div>
</form>
</div>
</div>
</div>
</main>

<script>
// Format card number
document.querySelector('input[name="card_number"]').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
    let formatted = [];
    for(let i=0; i<value.length; i+=4) {
        formatted.push(value.substring(i, i+4));
    }
    e.target.value = formatted.join(' ').substring(0, 19);
});

// Format expiry date
document.querySelector('input[name="expiry"]').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    e.target.value = value.substring(0, 5);
});

// Format CVV
document.querySelector('input[name="cvv"]').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
});

// AJAX Form Submission
document.getElementById('payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const cardNumber = this.card_number.value.replace(/\s/g, '');
    if (cardNumber.length < 13 || cardNumber.length > 19) {
        showToast('Please enter a valid card number', 'error');
        return;
    }
    
    const expiry = this.expiry.value;
    if (!/^\d{2}\/\d{2}$/.test(expiry)) {
        showToast('Please enter valid expiry date', 'error');
        return;
    }
    
    const cvv = this.cvv.value;
    if (cvv.length < 3) {
        showToast('Please enter a valid CVV', 'error');
        return;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnIcon = document.getElementById('btnIcon');
    const btnSpinner = document.getElementById('btnSpinner');
    
    // UI Update
    submitBtn.disabled = true;
    btnText.textContent = 'PROCESSING...';
    btnIcon.classList.add('hidden');
    btnSpinner.classList.remove('hidden');
    
    const formData = new FormData(this);
    
    fetch('<?php echo BASE_URL; ?>api/process_payment.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            btnText.textContent = 'PAYMENT SUCCESSFUL';
            btnSpinner.classList.add('hidden');
            btnIcon.textContent = 'check_circle';
            btnIcon.classList.remove('hidden');
            showToast('Payment successful! Taking you to your tickets...', 'success');
            
            setTimeout(() => {
                window.location.href = '<?php echo BASE_URL; ?>pages/' + data.redirect;
            }, 1000);
        } else {
            showToast(data.message, 'error');
            submitBtn.disabled = false;
            btnText.textContent = 'Pay <?php echo formatCurrency($grandTotal); ?>';
            btnSpinner.classList.add('hidden');
            btnIcon.classList.remove('hidden');
        }
    })
    .catch(err => {
        showToast('Network error, please try again.', 'error');
        submitBtn.disabled = false;
        btnText.textContent = 'Pay <?php echo formatCurrency($grandTotal); ?>';
        btnSpinner.classList.add('hidden');
        btnIcon.classList.remove('hidden');
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>