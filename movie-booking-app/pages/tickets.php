<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireLogin();

$pageTitle = 'My Tickets - CINEFLOW';
require_once '../includes/header.php';

// Fetch booking history
try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        SELECT b.*, s.show_date, s.show_time, m.title, t.name as theater_name, m.poster_url as movie_poster,
               COUNT(bd.id) as ticket_count
        FROM bookings b
        JOIN shows s ON b.show_id = s.id
        JOIN movies m ON s.movie_id = m.id
        JOIN theaters t ON s.theater_id = t.id
        LEFT JOIN booking_details bd ON b.id = bd.booking_id
        WHERE b.user_id = ? AND b.status = 'confirmed'
        GROUP BY b.id
        ORDER BY b.booking_date DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $bookings = [];
}
?>

<main class="pt-24 pb-40 relative z-10 min-h-screen bg-surface">
    <!-- Background Grid -->
    <div class="fixed inset-0 z-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-10 pointer-events-none mix-blend-screen"></div>

<div class="max-w-screen-2xl mx-auto px-6 md:px-12 relative z-10 animate-fly-in">
<header class="mb-12 border-b border-outline-variant/20 pb-8 flex flex-col md:flex-row items-end justify-between gap-6">
    <div class="space-y-4">
        <h1 class="text-5xl md:text-7xl font-black font-headline tracking-tighter uppercase text-white leading-none">
            My <span class="text-primary">Tickets</span>
        </h1>
        <p class="text-zinc-400 font-bold tracking-widest uppercase text-sm">
            Your Digital Admissions & Purchase History
        </p>
    </div>
</header>

<?php if (empty($bookings)): ?>
<div class="text-center py-32 bg-surface-container border border-outline-variant/10 rounded-[3rem] animate-fly-in">
    <span class="material-symbols-outlined text-6xl text-primary/40 mb-4 inline-block">qr_code</span>
    <h2 class="text-3xl font-headline font-black text-white tracking-tight uppercase">No Tickets Found</h2>
    <p class="text-zinc-500 mt-2 font-medium tracking-wide">You haven't booked any movies yet.</p>
    <a href="<?php echo BASE_URL; ?>pages/movies.php" class="mt-8 inline-block px-10 py-4 bg-primary text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:brightness-110 transition-all">Browse Movies</a>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
    <?php foreach ($bookings as $index => $booking): 
        // Simulated QR Code API
        $qrData = "CINEFLOW-" . $booking['id'] . "-" . $_SESSION['user_id'];
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData) . "&color=0-0-0&bgcolor=255-255-255";
        $isPast = strtotime($booking['show_date'] . ' ' . $booking['show_time']) < time();
    ?>
    <div class="bg-surface-container-low rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/10 animate-fly-in group" style="animation-delay: <?php echo min(0.1 + ($index * 0.1), 0.5); ?>s;">
        <!-- Top Half -->
        <div class="relative min-h-[140px] px-8 pt-8 pb-10 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="<?php echo htmlspecialchars($booking['movie_poster']); ?>" class="w-full h-full object-cover blur-md opacity-30 transform group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-surface-container-low"></div>
            </div>
            <div class="relative z-10 flex gap-6 items-center">
                <img src="<?php echo htmlspecialchars($booking['movie_poster']); ?>" class="w-28 md:w-32 aspect-[2/3] rounded-xl shadow-2xl shadow-black border border-white/20 shrink-0 object-cover transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                <div class="flex-1 min-w-0 flex flex-col justify-center">
                    <?php if ($isPast): ?>
                        <span class="bg-zinc-800 text-zinc-400 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded inline-block w-max mb-2">Past Event</span>
                    <?php else: ?>
                        <span class="bg-emerald-500 text-white text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded inline-block w-max mb-2 shadow-lg shadow-emerald-500/20">Upcoming</span>
                    <?php endif; ?>
                    <h3 class="text-2xl font-headline font-black text-white leading-tight uppercase truncate"><?php echo htmlspecialchars($booking['title']); ?></h3>
                    <p class="text-zinc-400 text-xs font-bold uppercase tracking-widest mt-1 truncate"><?php echo htmlspecialchars($booking['theater_name']); ?></p>
                </div>
            </div>
        </div>

        <!-- Perforated Div -->
        <div class="relative h-px bg-dashed border-t-2 border-dashed border-outline-variant/30 flex justify-between items-center z-20">
            <div class="w-6 h-6 bg-surface rounded-full absolute -left-3 translate-y-[-50%] border-r-2 border-outline-variant/30 hidden md:block"></div>
            <div class="w-6 h-6 bg-surface rounded-full absolute -right-3 translate-y-[-50%] border-l-2 border-outline-variant/30 hidden md:block"></div>
        </div>

        <!-- Bottom Half: QR and Details -->
        <div class="bg-surface-container-highest p-8 flex flex-col relative z-10 <?php echo $isPast ? 'opacity-50 grayscale' : ''; ?>">
            <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-8">
                <div>
                    <span class="block text-[9px] text-zinc-500 font-bold uppercase tracking-[0.2em] mb-1">Date</span>
                    <span class="text-white font-bold text-sm tracking-wide"><?php echo date('M. d, Y', strtotime($booking['show_date'])); ?></span>
                </div>
                <div>
                    <span class="block text-[9px] text-zinc-500 font-bold uppercase tracking-[0.2em] mb-1">Time</span>
                    <span class="text-white font-bold text-sm tracking-wide"><?php echo date('H:i', strtotime($booking['show_time'])); ?></span>
                </div>
                <div>
                    <span class="block text-[9px] text-zinc-500 font-bold uppercase tracking-[0.2em] mb-1">Confirmation</span>
                    <span class="text-white font-mono text-sm tracking-widest text-primary"><?php echo htmlspecialchars($booking['id']); ?></span>
                </div>
                <div>
                    <span class="block text-[9px] text-zinc-500 font-bold uppercase tracking-[0.2em] mb-1">Tickets</span>
                    <span class="text-white font-bold text-sm tracking-wide"><?php echo $booking['ticket_count']; ?> Seat(s)</span>
                </div>
            </div>

            <!-- QR Code Box -->
            <div class="flex items-center justify-between border-t border-outline-variant/20 pt-8 mt-auto">
                <div class="flex items-center gap-3 w-full">
                    <div class="p-2 bg-white rounded-xl shadow-lg shrink-0 group-hover:scale-105 transition-transform">
                        <img src="<?php echo $qrUrl; ?>" alt="QR Code" class="w-20 h-20 mix-blend-multiply">
                    </div>
                    <div class="ml-2 w-full">
                        <span class="block text-[9px] text-zinc-400 font-bold uppercase tracking-[0.2em] mb-1">Scan at entrance</span>
                        <div class="h-2 w-full max-w-[100px] bg-gradient-to-r from-primary to-transparent rounded-full mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</main>

<?php require_once '../includes/footer.php'; ?>
