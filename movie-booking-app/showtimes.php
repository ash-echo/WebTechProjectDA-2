<?php
$pageTitle = 'Showtimes & Theaters - AUTEUR Cinema';
require_once 'includes/header.php';
require_once 'includes/functions.php';

$movieId = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : 1;
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$movie = getMovieById($movieId);
if (!$movie) {
    header('Location: movies.php');
    exit();
}

$shows = getShowsForMovie($movieId, $date);
?>

<main class="pt-24 pb-20">
<!-- Hero Movie Context (Subtle) -->
<section class="relative h-[409px] w-full overflow-hidden mb-[-8rem]">
<img alt="Cinematic background" class="w-full h-full object-cover" data-alt="Cinematic close-up of a vintage film projector lens with warm golden flare and soft bokeh in a dark theater" src="<?php echo htmlspecialchars($movie['backdrop_url'] ?? $movie['poster_url'] ?? 'https://via.placeholder.com/1920x1080?text=' . urlencode($movie['title'])); ?>"/>
<div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/60 to-transparent"></div>
<div class="absolute bottom-40 left-8 md:left-12 lg:left-24">
<p class="text-primary-container font-bold tracking-[0.2em] uppercase text-xs mb-4">Now Showing</p>
<h1 class="editorial-title text-5xl md:text-7xl font-black tracking-tighter text-white"><?php echo htmlspecialchars($movie['title']); ?></h1>
<div class="flex items-center gap-4 mt-6">
<span class="bg-zinc-800 text-zinc-300 px-3 py-1 rounded text-xs font-bold uppercase tracking-widest border border-zinc-700/50">UA | 16+</span>
<span class="flex items-center gap-1 text-zinc-100 font-bold">
<span class="material-symbols-outlined text-yellow-500" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span> <?php echo number_format($movie['rating'], 1); ?>
</span>
<span class="text-zinc-400 text-sm">• <?php echo htmlspecialchars($movie['genre']); ?> • <?php echo $movie['duration']; ?>m</span>
</div>
</div>
</section>
<!-- Date Picker & Filters -->
<div class="sticky top-[72px] z-40 bg-surface/80 backdrop-blur-md pb-6 pt-2">
<div class="max-w-7xl mx-auto px-8 lg:px-12">
<!-- Date Selector -->
<div class="flex items-center gap-4 overflow-x-auto no-scrollbar py-4">
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
<div class="flex flex-col items-center justify-center min-w-[70px] h-[90px] rounded-2xl <?php echo $selected ? 'bg-primary-container text-white' : 'bg-surface-container-high text-zinc-400 hover:bg-zinc-800'; ?> cursor-pointer shadow-lg shadow-red-900/20 transition-all">
<a href="?movie_id=<?php echo $movieId; ?>&date=<?php echo $d; ?>" class="text-center">
<span class="text-xs font-bold uppercase tracking-widest opacity-80"><?php echo $month; ?></span>
<span class="editorial-title text-2xl font-black"><?php echo $dayNum; ?></span>
<span class="text-[10px] font-bold uppercase tracking-widest"><?php echo $dayName; ?></span>
</a>
</div>
<?php endforeach; ?>
</div>
<!-- Filters -->
<div class="flex flex-wrap items-center gap-3 mt-4">
<button class="bg-surface-container-highest text-zinc-100 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-zinc-700 transition-colors">
    Price Range <span class="material-symbols-outlined text-[18px]">expand_more</span>
</button>
<button class="bg-surface-container-highest text-zinc-100 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-zinc-700 transition-colors">
    Showtimes <span class="material-symbols-outlined text-[18px]">expand_more</span>
</button>
<button class="bg-primary text-on-primary px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2">
    Special Formats (IMAX) <span class="material-symbols-outlined text-[18px]">close</span>
</button>
<button class="bg-surface-container-highest text-zinc-100 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-zinc-700 transition-colors">
    Subtitles <span class="material-symbols-outlined text-[18px]">expand_more</span>
</button>
</div>
</div>
</div>
<!-- Theater Listings -->
<div class="max-w-7xl mx-auto px-8 lg:px-12 mt-8 space-y-6">
<?php if (empty($shows)): ?>
<div class="text-center py-20">
<h2 class="text-2xl font-bold text-zinc-400">No shows available for this date</h2>
<p class="text-zinc-500 mt-2">Try selecting a different date</p>
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

foreach ($theaters as $theater):
?>
<!-- Theater Card -->
<div class="bg-surface-container-low rounded-3xl overflow-hidden group hover:bg-surface-container transition-all">
<div class="flex flex-col md:flex-row p-6 lg:p-10 gap-8">
<!-- Theater Info -->
<div class="md:w-1/3 border-b md:border-b-0 md:border-r border-outline-variant/20 pb-6 md:pb-0 pr-0 md:pr-8">
<div class="flex items-start justify-between">
<h3 class="editorial-title text-2xl font-black text-white leading-tight"><?php echo htmlspecialchars($theater['name']); ?></h3>
<span class="material-symbols-outlined text-zinc-500 hover:text-primary-container cursor-pointer transition-colors">favorite</span>
</div>
<p class="text-zinc-400 text-sm mt-2 flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]">location_on</span>
<?php echo htmlspecialchars($theater['location']); ?>
</p>
<div class="flex items-center gap-4 mt-6">
<div class="flex items-center gap-1.5 text-zinc-300">
<span class="material-symbols-outlined text-[20px]">local_parking</span>
<span class="text-[10px] font-bold uppercase tracking-widest">Parking</span>
</div>
<div class="flex items-center gap-1.5 text-zinc-300">
<span class="material-symbols-outlined text-[20px]">restaurant</span>
<span class="text-[10px] font-bold uppercase tracking-widest">Food</span>
</div>
<div class="flex items-center gap-1.5 text-zinc-300">
<span class="material-symbols-outlined text-[20px]">accessible</span>
<span class="text-[10px] font-bold uppercase tracking-widest">Assisted</span>
</div>
</div>
</div>
<!-- Showtimes Grid -->
<div class="md:w-2/3">
<div class="flex items-center justify-between mb-6">
<span class="text-xs font-bold uppercase tracking-widest text-zinc-500">Available Showtimes</span>
<div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest">
<span class="flex items-center gap-1.5 text-emerald-500"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Available</span>
<span class="flex items-center gap-1.5 text-orange-400"><span class="w-2 h-2 rounded-full bg-orange-400"></span> Filling Fast</span>
</div>
</div>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
<?php foreach ($theater['shows'] as $show): ?>
<!-- Showtime Item -->
<a href="seat_selection.php?show_id=<?php echo $show['id']; ?>" class="group/time relative overflow-hidden bg-surface-container-high hover:bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-4 cursor-pointer transition-all">
<p class="text-center text-emerald-400 font-bold text-lg group-hover/time:scale-110 transition-transform"><?php echo date('H:i', strtotime($show['show_time'])); ?></p>
<p class="text-center text-[9px] uppercase tracking-tighter text-emerald-500/60 mt-1 font-bold"><?php echo htmlspecialchars($show['format']); ?> • <?php echo formatCurrency($show['price']); ?></p>
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
</style>

<?php require_once 'includes/footer.php'; ?>