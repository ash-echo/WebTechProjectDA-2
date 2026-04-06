<?php
$pageTitle = 'Showtimes & Theaters - CINEFLOW';
require_once '../includes/header.php';
require_once '../includes/functions.php';

$movieId = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : 1;
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$movie = getMovieById($movieId);
if (!$movie) {
    header('Location: ' . BASE_URL . 'pages/movies.php');
    exit();
}

$selectedFormat = $_GET['format'] ?? 'All';
$selectedTime = $_GET['time'] ?? 'All';

$shows = getShowsForMovie($movieId, $date, $selectedFormat, $selectedTime);
?>

<main class="pt-16 pb-20">
<!-- Hero Movie Context (Subtle) -->
<section class="relative h-[450px] w-full overflow-hidden mb-[-8rem]">
<img alt="Cinematic background" class="w-full h-full object-cover animate-pulse-glow" src="<?php echo BASE_URL . htmlspecialchars($movie['backdrop_url'] ?? $movie['poster_url']); ?>"/>
<div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/60 to-transparent"></div>
<div class="absolute bottom-40 left-6 md:left-12 lg:left-24 animate-fly-in">
<p class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 flex items-center gap-2"><div class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse-glow"></div> Now Showing</p>
<h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white drop-shadow-2xl uppercase"><?php echo htmlspecialchars($movie['title']); ?></h1>
<div class="flex flex-wrap items-center gap-4 mt-6">
<span class="bg-surface-container text-zinc-300 px-3 py-1.5 rounded-md text-[10px] font-black uppercase tracking-widest border border-outline-variant/30">UA | 16+</span>
<span class="flex items-center gap-1.5 text-yellow-500 bg-surface-container px-3 py-1.5 rounded-full border border-outline-variant/30">
<span class="material-symbols-outlined text-[14px] icon-filled">star</span>
<span class="text-white font-headline font-bold text-sm"><?php echo number_format($movie['rating'], 1); ?></span>
</span>
<span class="text-zinc-400 text-sm font-medium tracking-wide bg-surface-container px-4 py-1.5 rounded-full border border-outline-variant/30">• <?php echo htmlspecialchars($movie['genre']); ?> • <?php echo $movie['duration']; ?>m</span>
</div>
</div>
</section>
<!-- Date Picker & Filters -->
<div class="sticky top-[68px] md:top-[72px] z-40 bg-surface/90 backdrop-blur-xl border-b border-outline-variant/10 shadow-lg shadow-black/20 pb-4 pt-4 transition-all animate-fly-in" style="animation-delay: 0.1s;">
<div class="max-w-7xl mx-auto px-6 lg:px-12">
<!-- Date Selector -->
<div class="flex items-center gap-3 overflow-x-auto no-scrollbar py-2 mask-linear">
<?php
$dates = [];
for ($i = 0; $i < 7; $i++) {
    $dates[] = date('Y-m-d', strtotime("+$i days"));
}

foreach ($dates as $index => $d):
$selected = ($d == $date);
$dayName = date('D', strtotime($d));
$dayNum = date('j', strtotime($d));
$month = date('M', strtotime($d));
?>
<a href="?movie_id=<?php echo $movieId; ?>&date=<?php echo $d; ?>&format=<?php echo $selectedFormat; ?>&time=<?php echo $selectedTime; ?>" class="flex flex-col items-center justify-center min-w-[70px] md:min-w-[80px] h-[95px] rounded-2xl group relative cursor-pointer <?php echo $selected ? 'bg-primary text-white shadow-lg shadow-red-900/40 border-none' : 'bg-surface-container border border-outline-variant/30 text-zinc-400 hover:border-primary hover:text-white'; ?> transition-all duration-300 flex-shrink-0 btn-hover-fx">
<?php if ($selected): ?><div class="absolute -top-1 left-1/2 -translate-x-1/2 w-8 h-1 bg-white/50 rounded-full blur-[2px]"></div><?php endif; ?>
<span class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-1 group-hover:text-primary transition-colors <?php echo $selected ? 'text-white/80 group-hover:text-white/80' : ''; ?>"><?php echo $month; ?></span>
<span class="text-3xl font-headline font-black leading-none drop-shadow-sm group-hover:drop-shadow-lg <?php echo $selected ? 'text-white' : 'text-zinc-200'; ?>"><?php echo $dayNum; ?></span>
<span class="text-[9px] font-black uppercase tracking-widest mt-1 opacity-60"><?php echo $dayName; ?></span>
</a>
<?php endforeach; ?>
</div>

<div class="flex flex-wrap items-center gap-3 mt-4">
    <!-- Format Filter -->
    <div class="relative group">
        <select onchange="location.href='?movie_id=<?php echo $movieId; ?>&date=<?php echo $date; ?>&time=<?php echo $selectedTime; ?>&format=' + this.value" class="appearance-none bg-surface-container-high text-zinc-400 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-white hover:text-black transition-colors border border-outline-variant/30 cursor-pointer outline-none">
            <option value="All" <?php echo $selectedFormat == 'All' ? 'selected' : ''; ?>>All Formats</option>
            <option value="IMAX" <?php echo $selectedFormat == 'IMAX' ? 'selected' : ''; ?>>IMAX</option>
            <option value="4K" <?php echo $selectedFormat == '4K' ? 'selected' : ''; ?>>4K</option>
            <option value="2D" <?php echo $selectedFormat == '2D' ? 'selected' : ''; ?>>2D</option>
            <option value="3D" <?php echo $selectedFormat == '3D' ? 'selected' : ''; ?>>3D</option>
        </select>
    </div>

    <!-- Time Filter -->
    <div class="relative group">
        <select onchange="location.href='?movie_id=<?php echo $movieId; ?>&date=<?php echo $date; ?>&format=<?php echo $selectedFormat; ?>&time=' + this.value" class="appearance-none bg-surface-container-high text-zinc-400 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-white hover:text-black transition-colors border border-outline-variant/30 cursor-pointer outline-none">
            <option value="All" <?php echo $selectedTime == 'All' ? 'selected' : ''; ?>>All Times</option>
            <option value="Morning" <?php echo $selectedTime == 'Morning' ? 'selected' : ''; ?>>Morning</option>
            <option value="Afternoon" <?php echo $selectedTime == 'Afternoon' ? 'selected' : ''; ?>>Afternoon</option>
            <option value="Evening" <?php echo $selectedTime == 'Evening' ? 'selected' : ''; ?>>Evening</option>
            <option value="Night" <?php echo $selectedTime == 'Night' ? 'selected' : ''; ?>>Night</option>
        </select>
    </div>

    <!-- Price (Static Info for now as user said flat 267) -->
    <button class="bg-surface-container-high text-zinc-400 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 border border-outline-variant/30 opacity-50 cursor-default">
        Price: <?php echo formatCurrency(267); ?>
    </button>

    <?php if ($selectedFormat !== 'All' || $selectedTime !== 'All'): ?>
        <a href="?movie_id=<?php echo $movieId; ?>&date=<?php echo $date; ?>" class="bg-primary/20 text-primary border border-primary/50 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-primary hover:text-white transition-all">
            Clear Filters <span class="material-symbols-outlined text-[18px]">close</span>
        </a>
    <?php endif; ?>
</div>
</div>
</div>

<!-- Theater Listings -->
<div class="max-w-7xl mx-auto px-6 lg:px-12 mt-12 space-y-8 min-h-[400px]">
<?php if (empty($shows)): ?>
<div class="text-center py-32 bg-surface-container border border-outline-variant/10 rounded-[3rem] animate-fly-in">
<span class="material-symbols-outlined text-6xl text-primary/40 mb-4 inline-block">event_busy</span>
<h2 class="text-3xl font-headline font-black text-white tracking-tight uppercase">No Shows Available</h2>
<p class="text-zinc-500 mt-2 font-medium tracking-wide">Change the date or check out other movies.</p>
</div>
<?php else: ?>
<?php
// Group shows by theater
$theaters = [];
foreach ($shows as $show) {
    $theaterId = $show['theater_id'];
    if (!isset($theaters[$theaterId])) {
        $theaters[$theaterId] = [
            'name' => $show['theater_name'],
            'location' => $show['location'],
            'shows' => []
        ];
    }
    $theaters[$theaterId]['shows'][] = $show;
}
$theaterIndex = 0;
foreach ($theaters as $theater):
$theaterIndex++;
?>
<!-- Theater Card -->
<div class="bg-surface-container-low rounded-[2rem] overflow-hidden border border-outline-variant/10 hover:border-outline-variant/30 transition-all duration-300 animate-fly-in group" style="animation-delay: <?php echo min(0.1 + ($theaterIndex*0.1), 0.5); ?>s;">
<div class="flex flex-col md:flex-row p-6 lg:p-10 gap-8">
<!-- Theater Info -->
<div class="md:w-1/3 border-b md:border-b-0 md:border-r border-outline-variant/20 pb-6 md:pb-0 pr-0 md:pr-8">
<div class="flex items-start justify-between">
<h3 class="text-2xl font-headline font-black text-white leading-tight break-words pr-4 group-hover:text-primary transition-colors"><?php echo htmlspecialchars($theater['name']); ?></h3>
<span class="material-symbols-outlined text-zinc-500 hover:text-red-500 cursor-pointer transition-colors bg-surface-container-high w-10 h-10 rounded-full flex items-center justify-center shrink-0">favorite</span>
</div>
<p class="text-zinc-500 text-sm mt-3 flex items-start gap-2 font-medium">
<span class="material-symbols-outlined text-[18px] text-zinc-600 shrink-0 mt-0.5">location_on</span>
<?php echo htmlspecialchars($theater['location']); ?>
</p>
<div class="flex flex-wrap items-center gap-4 mt-6">
<div class="flex items-center gap-1.5 text-zinc-400 bg-surface-container px-2.5 py-1.5 rounded-lg border border-outline-variant/10">
<span class="material-symbols-outlined text-[16px]">local_parking</span>
<span class="text-[9px] font-black uppercase tracking-widest">Parking</span>
</div>
<div class="flex items-center gap-1.5 text-zinc-400 bg-surface-container px-2.5 py-1.5 rounded-lg border border-outline-variant/10">
<span class="material-symbols-outlined text-[16px]">restaurant</span>
<span class="text-[9px] font-black uppercase tracking-widest">Food</span>
</div>
<div class="flex items-center gap-1.5 text-zinc-400 bg-surface-container px-2.5 py-1.5 rounded-lg border border-outline-variant/10">
<span class="material-symbols-outlined text-[16px]">accessible</span>
<span class="text-[9px] font-black uppercase tracking-widest">Access</span>
</div>
</div>
</div>

<!-- Showtimes Grid -->
<div class="md:w-2/3 flex flex-col justify-center">
<div class="flex flex-wrap items-center justify-between mb-6 gap-4">
<span class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 shrink-0">Available Showtimes</span>
<div class="flex flex-wrap items-center gap-4 text-[10px] font-black uppercase tracking-[0.2em]">
<span class="flex items-center gap-2 text-primary/80"><div class="w-1.5 h-1.5 rounded-full bg-primary/80"></div> Available</span>
<span class="flex items-center gap-2 text-orange-500"><div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div> Fast Filling</span>
</div>
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
<?php foreach ($theater['shows'] as $show): 
// Use simulated occupancy to determine "Fast Filling" status
$occupancy = getShowOccupancy($show['id']);
$isFastFilling = $occupancy >= 50; 
$borderColor = $isFastFilling ? 'border-orange-500/30 hover:border-orange-500/80' : 'border-outline-variant/30 hover:border-primary/80';
$bgColor = $isFastFilling ? 'hover:bg-orange-500/5' : 'hover:bg-primary/5';
$timeColor = $isFastFilling ? 'text-orange-500' : 'text-white';
?>
<!-- Showtime Item -->
<a href="<?php echo BASE_URL; ?>pages/seat_selection.php?show_id=<?php echo $show['id']; ?>" class="relative overflow-hidden bg-surface-container <?php echo $bgColor; ?> border <?php echo $borderColor; ?> rounded-[1.25rem] p-5 cursor-pointer transition-all duration-300 hover:-translate-y-1 group/time block">
<p class="text-center <?php echo $timeColor; ?> font-black font-headline text-2xl group-hover/time:scale-110 transition-transform"><?php echo date('H:i', strtotime($show['show_time'])); ?></p>
<div class="text-center mt-2 flex flex-col items-center gap-1">
<span class="text-[9px] font-black uppercase tracking-widest text-zinc-500"><?php echo htmlspecialchars($show['format']); ?></span>
<span class="text-[10px] font-bold text-zinc-300 bg-surface-container-highest px-2 py-0.5 rounded-md mt-1 border border-outline-variant/10"><?php echo formatCurrency($show['price']); ?></span>
</div>
</a>
<?php endforeach; ?>
</div>

</div>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</main>

<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.mask-linear {
    -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
    mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
}
</style>

<?php require_once '../includes/footer.php'; ?>