<?php
$pageTitle = 'Seat Selection - AUTEUR Cinema';
require_once 'includes/header.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$showId = isset($_GET['show_id']) ? (int)$_GET['show_id'] : 0;
$show = getShowById($showId);

if (!$show) {
    header('Location: movies.php');
    exit();
}

// Clean expired locks
cleanExpiredLocks();

// Get seat status
$seats = getSeatStatusForShow($showId);

// Group seats by row
$seatRows = [];
foreach ($seats as $seat) {
    $row = $seat['seat_row'];
    if (!isset($seatRows[$row])) {
        $seatRows[$row] = [];
    }
    $seatRows[$row][] = $seat;
}
ksort($seatRows);

// Get selected seats from session
$selectedSeats = isset($_SESSION['selected_seats']) ? $_SESSION['selected_seats'] : [];
?>

<main class="pt-24 pb-40 px-6 max-w-5xl mx-auto">
<!-- Movie Header Section -->
<header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
<div class="space-y-2">
<div class="flex items-center gap-3 text-primary uppercase tracking-widest text-xs font-bold font-headline">
<span class="flex h-2 w-2 rounded-full bg-primary animate-pulse"></span>
    Select Seats
</div>
<h1 class="text-5xl md:text-6xl font-black font-headline tracking-tighter uppercase leading-none">
    <?php echo htmlspecialchars($show['title']); ?>
</h1>
<p class="text-zinc-400 font-medium flex items-center gap-4">
<span><?php echo htmlspecialchars($show['format']); ?></span>
<span class="w-1 h-1 bg-zinc-700 rounded-full"></span>
<span><?php echo date('D, M j', strtotime($show['show_date'])); ?></span>
<span class="w-1 h-1 bg-zinc-700 rounded-full"></span>
<span><?php echo date('g:i A', strtotime($show['show_time'])); ?></span>
</p>
</div>
<div class="flex gap-3">
<div class="bg-surface-container-high px-4 py-3 rounded-xl border border-outline-variant/15">
<span class="block text-[10px] uppercase tracking-wider text-zinc-500 font-bold mb-1">Cinema</span>
<span class="text-zinc-100 font-semibold font-headline"><?php echo htmlspecialchars($show['theater_name']); ?></span>
</div>
<div class="bg-surface-container-high px-4 py-3 rounded-xl border border-outline-variant/15">
<span class="block text-[10px] uppercase tracking-wider text-zinc-500 font-bold mb-1">Seats</span>
<span class="text-zinc-100 font-semibold font-headline" id="selected-count"><?php echo count($selectedSeats); ?> Selected</span>
</div>
</div>
</header>
<!-- Legend Section -->
<div class="flex flex-wrap justify-center gap-8 mb-16 py-4 border-y border-outline-variant/10">
<div class="flex items-center gap-2">
<div class="w-5 h-5 rounded-lg bg-surface-container-highest"></div>
<span class="text-xs font-semibold uppercase tracking-wider text-zinc-400 font-label">Available</span>
</div>
<div class="flex items-center gap-2">
<div class="w-5 h-5 rounded-lg bg-primary-container"></div>
<span class="text-xs font-semibold uppercase tracking-wider text-zinc-100 font-label">Selected</span>
</div>
<div class="flex items-center gap-2">
<div class="w-5 h-5 rounded-lg bg-zinc-800 flex items-center justify-center">
<span class="material-symbols-outlined text-[14px] text-zinc-600">close</span>
</div>
<span class="text-xs font-semibold uppercase tracking-wider text-zinc-400 font-label">Sold</span>
</div>
<div class="flex items-center gap-2">
<div class="w-5 h-5 rounded-lg bg-tertiary-container flex items-center justify-center">
<span class="material-symbols-outlined text-[14px] text-white" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<span class="text-xs font-semibold uppercase tracking-wider text-zinc-400 font-label">Bestseller</span>
</div>
</div>
<!-- Seat Map Container -->
<div class="relative mb-20 select-none">
<!-- Screen Indicator -->
<div class="w-full mb-24 flex flex-col items-center">
<div class="w-3/4 h-2 bg-zinc-800 rounded-full shadow-[0_-10px_40px_rgba(231,26,15,0.4)] relative">
<div class="absolute -top-12 left-1/2 -translate-x-1/2 text-primary font-headline font-black text-sm uppercase tracking-[0.4em] whitespace-nowrap">
    All eyes this way please
</div>
</div>
<div class="w-3/4 h-32 screen-gradient opacity-40 blur-2xl"></div>
</div>
<!-- Seating Grid -->
<div class="flex flex-col gap-8 items-center">
<?php
$tierPrices = ['PRIME' => 24.00, 'CLASSIC' => 18.00];
$currentTier = '';
foreach ($seatRows as $row => $rowSeats):
    $firstSeat = $rowSeats[0];
    $tier = $firstSeat['seat_type'];
    if ($tier != $currentTier):
        if ($currentTier != '') echo '</div></div>'; // Close previous tier
        $currentTier = $tier;
?>
<!-- <?php echo $tier; ?> Section -->
<div class="w-full">
<div class="flex items-center justify-center gap-3 mb-6">
<span class="h-[1px] w-12 bg-outline-variant/30"></span>
<span class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"><?php echo $tier; ?> TIER — <?php echo formatCurrency($tierPrices[$tier]); ?></span>
<span class="h-[1px] w-12 bg-outline-variant/30"></span>
</div>
<div class="flex flex-col gap-3 items-center">
<?php endif; ?>
<!-- Row <?php echo $row; ?> -->
<div class="flex gap-2">
<span class="w-6 text-[10px] text-zinc-600 font-bold flex items-center justify-center"><?php echo $row; ?></span>
<div class="flex gap-2 mx-4">
<?php
// Split seats into clusters for better layout
$leftCluster = array_slice($rowSeats, 0, 2);
$centerCluster = array_slice($rowSeats, 2, 4);
$rightCluster = array_slice($rowSeats, 6, 2);
?>
<!-- Cluster Left -->
<div class="flex gap-2">
<?php foreach ($leftCluster as $seat): ?>
<div class="w-8 h-8 md:w-10 md:h-10 rounded-xl 
    <?php 
    if ($seat['status'] == 'booked') echo 'bg-zinc-800 flex items-center justify-center cursor-not-allowed';
    elseif ($seat['status'] == 'locked') echo 'bg-orange-800 flex items-center justify-center cursor-not-allowed';
    elseif (in_array($seat['id'], $selectedSeats)) echo 'bg-primary-container flex items-center justify-center shadow-lg shadow-red-900/40 seat-selected';
    else echo 'bg-surface-container-highest hover:bg-zinc-600 cursor-pointer seat-available';
    ?> transition-colors"
    data-seat-id="<?php echo $seat['id']; ?>"
    data-seat-label="<?php echo $row . $seat['seat_number']; ?>">
<?php if ($seat['status'] == 'booked'): ?>
<span class="material-symbols-outlined text-sm text-zinc-600">close</span>
<?php elseif ($seat['status'] == 'locked'): ?>
<span class="material-symbols-outlined text-sm text-orange-400">schedule</span>
<?php elseif (in_array($seat['id'], $selectedSeats)): ?>
<span class="material-symbols-outlined text-white text-lg seat-selected">chair</span>
<?php elseif ($seat['seat_type'] == 'PRIME' && $seat['seat_number'] % 3 == 0): ?>
<span class="material-symbols-outlined text-white text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<!-- Cluster Center -->
<div class="flex gap-2 mx-6">
<?php foreach ($centerCluster as $seat): ?>
<div class="w-8 h-8 md:w-10 md:h-10 rounded-xl 
    <?php 
    if ($seat['status'] == 'booked') echo 'bg-zinc-800 flex items-center justify-center cursor-not-allowed';
    elseif ($seat['status'] == 'locked') echo 'bg-orange-800 flex items-center justify-center cursor-not-allowed';
    elseif (in_array($seat['id'], $selectedSeats)) echo 'bg-primary-container flex items-center justify-center shadow-lg shadow-red-900/40 seat-selected';
    else echo 'bg-surface-container-highest hover:bg-zinc-600 cursor-pointer seat-available';
    ?> transition-colors"
    data-seat-id="<?php echo $seat['id']; ?>"
    data-seat-label="<?php echo $row . $seat['seat_number']; ?>">
<?php if ($seat['status'] == 'booked'): ?>
<span class="material-symbols-outlined text-sm text-zinc-600">close</span>
<?php elseif ($seat['status'] == 'locked'): ?>
<span class="material-symbols-outlined text-sm text-orange-400">schedule</span>
<?php elseif (in_array($seat['id'], $selectedSeats)): ?>
<span class="material-symbols-outlined text-white text-lg seat-selected">chair</span>
<?php elseif ($seat['seat_type'] == 'PRIME' && $seat['seat_number'] % 3 == 0): ?>
<span class="material-symbols-outlined text-white text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<!-- Cluster Right -->
<div class="flex gap-2">
<?php foreach ($rightCluster as $seat): ?>
<div class="w-8 h-8 md:w-10 md:h-10 rounded-xl 
    <?php 
    if ($seat['status'] == 'booked') echo 'bg-zinc-800 flex items-center justify-center cursor-not-allowed';
    elseif ($seat['status'] == 'locked') echo 'bg-orange-800 flex items-center justify-center cursor-not-allowed';
    elseif (in_array($seat['id'], $selectedSeats)) echo 'bg-primary-container flex items-center justify-center shadow-lg shadow-red-900/40 seat-selected';
    else echo 'bg-surface-container-highest hover:bg-zinc-600 cursor-pointer seat-available';
    ?> transition-colors"
    data-seat-id="<?php echo $seat['id']; ?>"
    data-seat-label="<?php echo $row . $seat['seat_number']; ?>">
<?php if ($seat['status'] == 'booked'): ?>
<span class="material-symbols-outlined text-sm text-zinc-600">close</span>
<?php elseif ($seat['status'] == 'locked'): ?>
<span class="material-symbols-outlined text-sm text-orange-400">schedule</span>
<?php elseif (in_array($seat['id'], $selectedSeats)): ?>
<span class="material-symbols-outlined text-white text-lg seat-selected">chair</span>
<?php elseif ($seat['seat_type'] == 'PRIME' && $seat['seat_number'] % 3 == 0): ?>
<span class="material-symbols-outlined text-white text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
</div>
<span class="w-6 text-[10px] text-zinc-600 font-bold flex items-center justify-center"><?php echo $row; ?></span>
</div>
<?php endforeach; ?>
</div>
</div>
</div>
</div>
</div>
</main>
<!-- Floating Footer -->
<footer class="fixed bottom-0 left-0 w-full z-50 p-6 md:p-8">
<div class="max-w-screen-xl mx-auto glass-panel rounded-2xl md:rounded-[2rem] p-6 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl border border-outline-variant/10">
<div class="flex items-center gap-8 w-full md:w-auto">
<div class="space-y-1">
<span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Total Price</span>
<div class="text-3xl font-black font-headline text-zinc-100" id="total-price">$0.00</div>
</div>
<div class="h-10 w-[1px] bg-zinc-800 hidden md:block"></div>
<div class="space-y-1">
<span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Seats</span>
<div class="flex gap-2" id="selected-seats-display">
<!-- Selected seats will be shown here -->
</div>
</div>
</div>
<div class="flex gap-4 w-full md:w-auto">
<button class="flex-1 md:flex-none px-8 py-4 rounded-xl font-bold uppercase tracking-wider text-sm border border-outline-variant/30 text-zinc-100 hover:bg-zinc-800 transition-all" onclick="clearSelection()">
    Cancel
</button>
<button class="flex-1 md:flex-none px-12 py-4 rounded-xl bg-gradient-to-br from-primary-container to-[#930000] text-on-primary-container font-black uppercase tracking-[0.2em] text-sm shadow-xl shadow-red-900/40 hover:scale-[1.02] active:scale-[0.98] transition-all" onclick="proceedToPayment()">
    Pay Now
</button>
</div>
</div>
</footer>

<script>
let selectedSeats = <?php echo json_encode($selectedSeats); ?>;
const showId = <?php echo $showId; ?>;
const tierPrices = <?php echo json_encode($tierPrices); ?>;

document.addEventListener('DOMContentLoaded', function() {
    updateDisplay();
    
    // Seat click handlers
    document.querySelectorAll('.seat-available, .seat-selected').forEach(seat => {
        seat.addEventListener('click', function() {
            const seatId = parseInt(this.dataset.seatId);
            toggleSeatSelection(seatId);
        });
    });
});

function toggleSeatSelection(seatId) {
    const index = selectedSeats.indexOf(seatId);
    if (index > -1) {
        selectedSeats.splice(index, 1);
    } else {
        selectedSeats.push(seatId);
    }
    
    // Update visual
    const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
    if (selectedSeats.includes(seatId)) {
        seatElement.className = seatElement.className.replace('seat-available', 'seat-selected').replace('bg-surface-container-highest', 'bg-primary-container');
        seatElement.innerHTML = '<span class="material-symbols-outlined text-white text-lg seat-selected">chair</span>';
    } else {
        seatElement.className = seatElement.className.replace('seat-selected', 'seat-available').replace('bg-primary-container', 'bg-surface-container-highest');
        seatElement.innerHTML = '';
    }
    
    updateDisplay();
}

function updateDisplay() {
    // Update selected count
    document.getElementById('selected-count').textContent = selectedSeats.length + ' Selected';
    
    // Calculate total price (simplified - all seats same price for now)
    const totalPrice = selectedSeats.length * <?php echo $show['price']; ?>;
    document.getElementById('total-price').textContent = '$' + totalPrice.toFixed(2);
    
    // Update selected seats display
    const display = document.getElementById('selected-seats-display');
    display.innerHTML = '';
    selectedSeats.forEach(seatId => {
        const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
        if (seatElement) {
            const label = seatElement.dataset.seatLabel;
            const span = document.createElement('span');
            span.className = 'px-2 py-1 bg-surface-container-highest rounded-lg text-xs font-bold text-zinc-300';
            span.textContent = label;
            display.appendChild(span);
        }
    });
}

function clearSelection() {
    selectedSeats.forEach(seatId => {
        const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
        seatElement.className = seatElement.className.replace('seat-selected', 'seat-available').replace('bg-primary-container', 'bg-surface-container-highest');
        seatElement.innerHTML = '';
    });
    selectedSeats = [];
    updateDisplay();
}

function proceedToPayment() {
    if (selectedSeats.length === 0) {
        alert('Please select at least one seat');
        return;
    }
    
    // Store selection in session via AJAX
    fetch('api/lock_seats.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            show_id: showId,
            seat_ids: selectedSeats
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'checkout.php?show_id=' + showId;
        } else {
            alert('Failed to lock seats: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>

<style>
.seat-selected {
    font-variation-settings: 'FILL' 1;
}
.screen-gradient {
    background: linear-gradient(180deg, rgba(231, 26, 15, 0.3) 0%, rgba(231, 26, 15, 0) 100%);
}
</style>

<?php require_once 'includes/footer.php'; ?>