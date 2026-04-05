<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireLogin(); // Ensure user is logged in

$showId = isset($_GET['show_id']) ? (int)$_GET['show_id'] : 0;
$show = getShowById($showId);

if (!$show) {
    header('Location: ' . BASE_URL . 'pages/movies.php');
    exit();
}

$seats = getSeatStatusForShow($showId);

// Group seats by row
$rows = [];
foreach ($seats as $seat) {
    $rowId = $seat['seat_row'];
    if (!isset($rows[$rowId])) {
        $rows[$rowId] = [];
    }
    $rows[$rowId][] = $seat;
}
ksort($rows); // Sort alphabetically A-Z
$theaterId = $show['theater_id'];

$pageTitle = 'Select Seats - CINEFLOW';
require_once '../includes/header.php';
?>

<main class="min-h-screen pt-20 pb-40 flex flex-col md:flex-row overflow-hidden relative">
    <!-- Grid Background -->
    <div class="fixed inset-0 z-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-10 pointer-events-none mix-blend-screen"></div>

<!-- Theater View Area -->
<section class="flex-1 md:h-[calc(100vh-80px)] overflow-y-auto no-scrollbar relative z-10 px-4 md:px-12 py-10">
    <div class="max-w-6xl mx-auto pb-20">
        <!-- Header -->
        <div class="mb-12 text-center animate-fly-in">
            <h1 class="text-3xl font-headline font-black tracking-tighter text-white uppercase"><?php echo htmlspecialchars($show['title']); ?></h1>
            <p class="text-zinc-400 font-medium tracking-wide mt-2">
                <?php echo date('D, M j, Y', strtotime($show['show_date'])); ?> • <?php echo date('H:i', strtotime($show['show_time'])); ?>
            </p>
            <div class="inline-flex items-center gap-2 bg-surface-container-high px-4 py-1.5 rounded-full mt-4 border border-outline-variant/30 text-xs font-bold uppercase tracking-widest text-zinc-300">
                <span class="material-symbols-outlined text-sm">theaters</span> <?php echo htmlspecialchars($show['theater_name']); ?>
            </div>
        </div>

        <!-- The Screen -->
        <div class="relative mx-auto mb-20 animate-fly-in <?php echo $theaterId == 1 ? 'w-full' : ($theaterId == 3 ? 'w-[70%]' : 'w-[80%]'); ?>" style="animation-delay: 0.1s;">
            <div class="h-1 bg-gradient-to-r from-transparent via-primary/80 to-transparent rounded-full mb-1 shadow-[0_0_20px_rgba(231,26,15,0.8)]"></div>
            <div class="h-16 bg-gradient-to-b from-primary/30 to-transparent rounded-t-full opacity-70 w-full" style="transform: perspective(300px) rotateX(40deg); transform-origin: top;"></div>
            <p class="text-center text-zinc-500 text-[10px] font-bold uppercase tracking-[0.4em] absolute -bottom-8 w-full">SCREEN</p>
        </div>

        <!-- Seats Container -->
        <div id="seat-map" class="animate-fly-in relative flex flex-col items-center justify-center <?php echo $theaterId == 1 ? 'space-y-2' : ($theaterId == 3 ? 'space-y-8' : 'space-y-5'); ?>" style="animation-delay: 0.2s;" data-theater-id="<?php echo $theaterId; ?>">
            <?php 
            // Unique Render Logic per Theater
            $totalRowsCount = count($rows);
            $rowIndex = 0;

            foreach ($rows as $rowName => $seatsInRow):
                $totalInRow = count($seatsInRow);
                $rowIndex++;

                // IMAX Curve Logic (Theater 1)
                $curveOffset = 0;
                if ($theaterId == 1) {
                    $middlePoint = $totalRowsCount / 2;
                    $curveOffset = abs($rowIndex - $middlePoint) * 5;
                }
            ?>
            <div class="flex items-center justify-center relative w-full translate-y-[<?php echo $curveOffset; ?>px]" style="transform: translateY(<?php echo $curveOffset; ?>px);">
                
                <!-- Row Letter Left -->
                <div class="absolute left-[-2rem] md:left-0 text-zinc-600 font-bold text-xs"><?php echo $rowName; ?></div>
                
                <div class="flex items-center justify-center <?php echo $theaterId == 1 ? 'gap-1' : ($theaterId == 3 ? 'gap-6' : 'gap-3'); ?>">
                    <?php 
                    foreach ($seatsInRow as $index => $seat):
                        // Unique Architectures handling
                        $hasAisle = false;
                        
                        // Dual Aisle for Standard Theater (Theater 2)
                        if ($theaterId == 2 && ($index == 4 || $index == 9)) {
                            $hasAisle = true;
                        }
                        // Center Aisle only for VIP and Director's Cut
                        if (($theaterId == 3 || $theaterId == 4) && $index == floor($totalInRow/2) - 1) {
                            $hasAisle = true;
                        }

                        $statusClass = '';
                        $cursorClass = 'cursor-pointer hover:-translate-y-1';
                        
                        if ($seat['status'] == 'booked') {
                            $statusClass = 'bg-zinc-800 border-zinc-700 text-zinc-700 pointer-events-none opacity-40';
                            $cursorClass = '';
                        } elseif ($seat['status'] == 'locked') {
                            $statusClass = 'bg-orange-500/20 border-orange-500 text-orange-500 pointer-events-none';
                            if ($seat['locked_by'] == $_SESSION['user_id']) {
                                $statusClass = 'bg-primary border-primary text-white scale-110 shadow-[0_0_15px_rgba(231,26,15,0.6)] ring-2 ring-primary/50';
                            }
                        } else {
                            if ($seat['seat_type'] == 'VIP' || $seat['seat_type'] == 'DIRECTOR') {
                                $statusClass = 'bg-surface border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-white shadow-[0_0_10px_rgba(234,179,8,0.2)]';
                            } else {
                                $statusClass = 'bg-surface border-outline-variant/50 text-zinc-400 hover:border-primary hover:text-primary hover:shadow-[0_0_15px_rgba(231,26,15,0.4)]';
                            }
                        }

                        // Size styling based on theater type
                        $seatSizeClass = 'w-6 h-6 md:w-8 md:h-8'; // Standard/IMAX
                        if ($theaterId == 3) {
                            $seatSizeClass = 'w-10 h-10 md:w-14 md:h-14 rounded-2xl'; // VIP Lounge
                        } else if ($theaterId == 4) {
                            $seatSizeClass = 'w-8 h-8 md:w-10 md:h-10 rounded-xl'; // Directors
                        } else {
                            $seatSizeClass .= ' rounded-t-xl rounded-b-sm'; // Standard curve
                        }
                    ?>
                    <button class="seat-btn border-2 text-[10px] md:text-xs font-black tracking-tighter transition-all duration-300 flex items-center justify-center group relative <?php echo $seatSizeClass . ' ' . $statusClass . ' ' . $cursorClass; ?>" 
                            data-seat-id="<?php echo $seat['id']; ?>"
                            data-seat-name="<?php echo $seat['seat_row'] . $seat['seat_number']; ?>"
                            data-status="<?php echo $seat['status']; ?>"
                            data-type="<?php echo $seat['seat_type']; ?>"
                            data-price="<?php echo $seat['seat_type'] == 'VIP' ? $show['price'] + 15 : ($seat['seat_type'] == 'DIRECTOR' ? $show['price'] + 20 : $show['price']); ?>">
                        <span class="opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-md"><?php echo $seat['seat_number']; ?></span>
                    </button>
                    <?php if ($hasAisle): ?>
                        <div class="<?php echo $theaterId == 2 ? 'w-8 md:w-16' : 'w-12 md:w-20'; ?>"></div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                
                <!-- Row Letter Right -->
                <div class="absolute right-[-2rem] md:right-0 text-center text-zinc-600 font-bold text-xs"><?php echo $rowName; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Sidebar Details -->
<aside class="w-full md:w-[350px] lg:w-[400px] bg-surface-container-high border-t md:border-t-0 md:border-l border-outline-variant/20 p-6 md:p-8 flex flex-col justify-between fixed bottom-0 md:relative z-20 h-auto md:h-[calc(100vh-80px)] max-h-[50vh] md:max-h-full overflow-y-auto shadow-[0_-30px_50px_rgba(0,0,0,0.8)] md:shadow-none animate-fly-in" style="animation-delay: 0.3s;">
    <div>
        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-primary mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">confirmation_number</span> Booking Registry
        </h3>
        
        <div class="flex gap-5 mb-8 bg-surface p-4 rounded-2xl border border-white/5">
            <img src="<?php echo BASE_URL . htmlspecialchars($show['poster_url']); ?>" class="w-20 rounded-lg shadow-lg object-cover">
            <div class="flex flex-col justify-center">
                <h4 class="font-headline font-black text-white text-lg leading-tight uppercase tracking-tighter"><?php echo htmlspecialchars($show['title']); ?></h4>
                <p class="text-xs text-zinc-400 mt-2 font-bold tracking-widest uppercase flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-zinc-500"></span> <?php echo htmlspecialchars($show['format']); ?></p>
            </div>
        </div>

        <div class="space-y-4 mb-8 text-sm bg-surface-container rounded-2xl p-6 border border-outline-variant/20">
            <div class="flex justify-between items-center text-white border-b border-outline-variant/10 pb-4">
                <span class="text-zinc-500 font-bold uppercase tracking-[0.1em] text-[10px]">Tickets</span>
                <span id="ticket-count" class="font-black text-lg">0</span>
            </div>
            <div class="flex justify-between items-center text-white border-b border-outline-variant/10 pb-4">
                <span class="text-zinc-500 font-bold uppercase tracking-[0.1em] text-[10px]">Seats</span>
                <span id="selected-seats-list" class="font-black tracking-widest break-words max-w-[150px] text-right truncate">-</span>
            </div>
            <div class="flex justify-between items-center text-white pt-2">
                <span class="text-zinc-500 font-bold uppercase tracking-[0.1em] text-[10px]">Amount</span>
                <span id="total-price" class="font-headline font-black text-3xl text-primary drop-shadow-[0_0_10px_rgba(231,26,15,0.4)]">$0.00</span>
            </div>
        </div>
        
        <!-- Legend -->
        <div class="grid grid-cols-2 gap-4 mb-6 px-2">
            <div class="flex items-center gap-3 text-[10px] text-zinc-400 font-bold uppercase tracking-widest"><div class="w-5 h-5 border-2 border-outline-variant/50 rounded-t-md"></div> Open</div>
            <div class="flex items-center gap-3 text-[10px] text-zinc-400 font-bold uppercase tracking-widest"><div class="w-5 h-5 bg-zinc-800 border-2 border-zinc-700 rounded-t-md"></div> Sold</div>
            <div class="flex items-center gap-3 text-[10px] text-white font-black uppercase tracking-widest"><div class="w-5 h-5 bg-primary border-2 border-primary rounded-t-md shadow-[0_0_10px_rgba(231,26,15,0.5)]"></div> Yours</div>
            <div class="flex items-center gap-3 text-[10px] text-yellow-500 font-bold uppercase tracking-widest"><div class="w-5 h-5 border-2 border-yellow-500 rounded-t-md shadow-[0_0_10px_rgba(234,179,8,0.2)]"></div> Premium</div>
        </div>
    </div>

    <button id="proceed-btn" disabled class="w-full bg-surface-container-highest text-zinc-600 py-5 rounded-2xl font-black uppercase tracking-[0.2em] text-sm transition-all duration-300 shadow-none flex items-center justify-center gap-3 border border-outline-variant/10">
        <span>Proceed to Secure</span>
        <span class="material-symbols-outlined text-xl hidden" id="proceed-spinner">sync</span>
    </button>
</aside>
</main>

<script>
let selectedSeats = [];
const showId = <?php echo $showId; ?>;
let totalPrice = 0;

document.querySelectorAll('.seat-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if(this.dataset.status !== 'available' && !this.classList.contains('bg-primary')) return;
        
        const seatId = this.dataset.seatId;
        const seatName = this.dataset.seatName;
        const isSelected = this.classList.contains('bg-primary');
        const price = parseFloat(this.dataset.price);

        if(!isSelected) {
            if(selectedSeats.length >= 10) {
                if(typeof showToast === 'function') showToast('Maximum 10 seats allowed', 'error');
                return;
            }
            this.classList.remove('bg-surface', 'border-outline-variant/50', 'text-zinc-400', 'border-yellow-500', 'text-yellow-500');
            this.classList.add('bg-primary', 'border-primary', 'text-white', 'shadow-[0_0_15px_rgba(231,26,15,0.6)]', '-translate-y-1');
            selectedSeats.push({id: seatId, name: seatName, price: price});
        } else {
            this.classList.remove('bg-primary', 'border-primary', 'text-white', 'shadow-[0_0_15px_rgba(231,26,15,0.6)]', '-translate-y-1');
            if (this.dataset.type === 'VIP' || this.dataset.type === 'DIRECTOR') {
                this.classList.add('bg-surface', 'border-yellow-500', 'text-yellow-500');
            } else {
                this.classList.add('bg-surface', 'border-outline-variant/50', 'text-zinc-400');
            }
            selectedSeats = selectedSeats.filter(s => s.id !== seatId);
        }
        
        updateSummary();
    });
});

function updateSummary() {
    const proceedBtn = document.getElementById('proceed-btn');
    document.getElementById('ticket-count').textContent = selectedSeats.length;
    
    if(selectedSeats.length > 0) {
        document.getElementById('selected-seats-list').textContent = selectedSeats.map(s => s.name).join(', ');
        totalPrice = selectedSeats.reduce((sum, s) => sum + s.price, 0);
        document.getElementById('total-price').textContent = '$' + totalPrice.toFixed(2);
        
        proceedBtn.disabled = false;
        proceedBtn.classList.remove('bg-surface-container-highest', 'text-zinc-600', 'border-outline-variant/10', 'shadow-none');
        proceedBtn.classList.add('bg-primary', 'text-white', 'border-transparent', 'hover:brightness-110', 'shadow-[0_10px_30px_rgba(231,26,15,0.4)]');
    } else {
        document.getElementById('selected-seats-list').textContent = '-';
        document.getElementById('total-price').textContent = '$0.00';
        
        proceedBtn.disabled = true;
        proceedBtn.classList.add('bg-surface-container-highest', 'text-zinc-600', 'border-outline-variant/10', 'shadow-none');
        proceedBtn.classList.remove('bg-primary', 'text-white', 'border-transparent', 'hover:brightness-110', 'shadow-[0_10px_30px_rgba(231,26,15,0.4)]');
    }
}

// Proceed Action
document.getElementById('proceed-btn').addEventListener('click', function() {
    if(selectedSeats.length === 0) return;
    
    this.disabled = true;
    const spinner = document.getElementById('proceed-spinner');
    spinner.classList.remove('hidden');
    spinner.classList.add('animate-spin');
    
    fetch('<?php echo BASE_URL; ?>api/lock_seats.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            show_id: showId,
            seat_ids: selectedSeats.map(s => s.id),
            action: 'lock'
        })
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            window.location.href = '<?php echo BASE_URL; ?>pages/checkout.php';
        } else {
            if(typeof showToast === 'function') showToast(data.message, 'error');
            this.disabled = false;
            spinner.classList.add('hidden');
            spinner.classList.remove('animate-spin');
            if (data.redirect) window.location.href = '<?php echo BASE_URL; ?>pages/' + data.redirect;
            else pollSeats();
        }
    })
    .catch(err => {
        if(typeof showToast === 'function') showToast('Network Error', 'error');
        this.disabled = false;
        spinner.classList.add('hidden');
        spinner.classList.remove('animate-spin');
    });
});

function isSelected(id) {
    return selectedSeats.some(s => s.id == id);
}

function pollSeats() {
    fetch('<?php echo BASE_URL; ?>api/lock_seats.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ show_id: showId, action: 'poll' })
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            data.seats.forEach(seat => {
                const btn = document.querySelector(`.seat-btn[data-seat-id="${seat.id}"]`);
                if(btn) {
                    btn.dataset.status = seat.status;
                    const isMyLock = seat.status === 'locked' && seat.locked_by == <?php echo $_SESSION['user_id'] ?? 0; ?>;
                    
                    if (seat.status === 'booked') {
                        btn.className = `seat-btn ${getSeatSizeClasses()} border-2 text-[10px] md:text-xs font-black tracking-tighter transition-all duration-300 flex items-center justify-center group relative bg-zinc-800 border-zinc-700 text-zinc-700 pointer-events-none opacity-40`;
                    } else if (seat.status === 'locked' && !isMyLock) {
                        btn.className = `seat-btn ${getSeatSizeClasses()} border-2 text-[10px] md:text-xs font-black tracking-tighter transition-all duration-300 flex items-center justify-center group relative bg-orange-500/20 border-orange-500 text-orange-500 pointer-events-none`;
                    } else if (!isSelected(seat.id) && seat.status === 'available') {
                        const typeClass = (seat.seat_type === 'VIP' || seat.seat_type === 'DIRECTOR') ? 'bg-surface border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-white shadow-[0_0_10px_rgba(234,179,8,0.2)]' : 'bg-surface border-outline-variant/50 text-zinc-400 hover:border-primary hover:text-primary hover:shadow-[0_0_15px_rgba(231,26,15,0.4)]';
                        btn.className = `seat-btn ${getSeatSizeClasses()} border-2 text-[10px] md:text-xs font-black tracking-tighter transition-all duration-300 flex items-center justify-center group relative cursor-pointer hover:-translate-y-1 ${typeClass}`;
                    }
                }
            });
        }
    }).catch(e => console.log('Polling failed', e));
}

function getSeatSizeClasses() {
    const theaterId = <?php echo $theaterId; ?>;
    if (theaterId == 3) return 'w-10 h-10 md:w-14 md:h-14 rounded-2xl';
    if (theaterId == 4) return 'w-8 h-8 md:w-10 md:h-10 rounded-xl';
    return 'w-6 h-6 md:w-8 md:h-8 rounded-t-xl rounded-b-sm';
}

setInterval(pollSeats, 5000);
</script>

<style>
.no-scrollbar::-webkit-scrollbar { width: 4px; }
.no-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
.no-scrollbar::-webkit-scrollbar-track { background: transparent; }
</style>

<?php require_once '../includes/footer.php'; ?>