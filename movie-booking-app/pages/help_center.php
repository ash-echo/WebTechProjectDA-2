<?php
$pageTitle = 'Help Center - AUTEUR Cinema';
require_once '../includes/header.php';
?>

<main class="min-h-screen pt-24 pb-40 px-6 max-w-4xl mx-auto relative z-10 animate-fly-in">
    <div class="text-center mt-12 mb-16">
        <span class="material-symbols-outlined text-5xl text-primary mb-4 block">help_center</span>
        <h1 class="text-4xl md:text-6xl font-black font-headline tracking-tighter uppercase leading-none mb-4">
            How can we help?
        </h1>
        <div class="relative max-w-xl mx-auto mt-8">
            <input type="text" placeholder="Search for answers..." class="w-full px-6 py-4 bg-surface-container-high border border-outline-variant/30 rounded-2xl text-white placeholder-zinc-500 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all text-lg pl-14">
            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-zinc-400">search</span>
        </div>
    </div>

    <div class="space-y-6">
        <h2 class="text-xl font-black text-white uppercase tracking-widest mb-6">Frequently Asked Questions</h2>
        
        <div class="glass-panel p-6 rounded-2xl cursor-pointer hover:border-primary transition-colors group">
            <h3 class="text-lg font-bold text-white flex justify-between items-center group-hover:text-primary transition-colors">
                How do I cancel or refund a ticket?
                <span class="material-symbols-outlined">expand_more</span>
            </h3>
        </div>

        <div class="glass-panel p-6 rounded-2xl cursor-pointer hover:border-primary transition-colors group">
            <h3 class="text-lg font-bold text-white flex justify-between items-center group-hover:text-primary transition-colors">
                Do I need to print my tickets?
                <span class="material-symbols-outlined">expand_more</span>
            </h3>
        </div>

        <div class="glass-panel p-6 rounded-2xl cursor-pointer hover:border-primary transition-colors group">
            <h3 class="text-lg font-bold text-white flex justify-between items-center group-hover:text-primary transition-colors">
                What are the age restrictions for R-rated movies?
                <span class="material-symbols-outlined">expand_more</span>
            </h3>
        </div>
    </div>

    <div class="mt-20 text-center bg-surface-container-low p-10 rounded-3xl border border-outline-variant/20">
        <h2 class="text-2xl font-black uppercase tracking-widest text-white mb-2">Still need help?</h2>
        <p class="text-zinc-500 mb-6">Our support team is available 24/7 to assist you.</p>
        <a href="feedback.php" class="inline-block px-8 py-3 bg-white text-black font-black uppercase tracking-widest text-sm rounded-xl hover:bg-zinc-200 transition-all">Contact Support</a>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
