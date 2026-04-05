<?php
$pageTitle = 'Gift Cards - CINEFLOW';
require_once '../includes/header.php';
?>

<main class="min-h-screen pt-24 pb-40 px-6 max-w-7xl mx-auto relative z-10 animate-fly-in">
    <div class="fixed inset-0 z-0 bg-gradient-to-br from-primary/10 via-surface to-surface pointer-events-none"></div>
    
    <div class="relative z-10 max-w-4xl mx-auto text-center mt-12 mb-20">
        <span class="material-symbols-outlined text-6xl text-primary mb-6 animate-pulse">redeem</span>
        <h1 class="text-5xl md:text-7xl font-black font-headline tracking-tighter uppercase leading-none mb-6">
            The Gift of Cinema
        </h1>
        <p class="text-xl text-zinc-400 font-medium max-w-2xl mx-auto">
            Give the perfect gift to the movie lovers in your life. CINEFLOW Gift Cards never expire and can be used for tickets, VIP upgrades, and premier concessions.
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto relative z-10">
        <div class="glass-panel rounded-3xl p-10 flex flex-col items-center text-center group cursor-pointer hover:border-primary transition-all duration-500 hover:-translate-y-2">
            <div class="w-full h-48 bg-gradient-to-r from-zinc-800 to-zinc-900 rounded-2xl mb-8 flex items-center justify-center border border-white/5 relative overflow-hidden group-hover:shadow-[0_0_40px_rgba(231,26,15,0.3)] transition-all">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-20"></div>
                <h3 class="font-headline font-black text-3xl tracking-[0.2em] text-white">DIGITAL</h3>
            </div>
            <h2 class="text-2xl font-black uppercase tracking-widest text-white mb-3">E-Gift Cards</h2>
            <p class="text-zinc-500 mb-8">Delivered instantly via email. Choose from dozens of cinematic designs.</p>
            <button class="w-full px-8 py-4 bg-primary text-white font-black uppercase tracking-[0.2em] text-sm rounded-xl hover:brightness-110 active:scale-95 transition-all mt-auto">Buy E-Gift Card</button>
        </div>

        <div class="glass-panel rounded-3xl p-10 flex flex-col items-center text-center group cursor-pointer hover:border-white transition-all duration-500 hover:-translate-y-2">
            <div class="w-full h-48 bg-gradient-to-r from-zinc-200 to-white rounded-2xl mb-8 flex items-center justify-center shadow-[inset_0_-10px_20px_rgba(0,0,0,0.2)]">
                <h3 class="font-headline font-black text-3xl tracking-[0.2em] text-black">PHYSICAL</h3>
            </div>
            <h2 class="text-2xl font-black uppercase tracking-widest text-white mb-3">Physical Gift Cards</h2>
            <p class="text-zinc-500 mb-8">Premium textured cards delivered by mail securely in an exclusive CINEFLOW box.</p>
            <button class="w-full px-8 py-4 bg-white text-black font-black uppercase tracking-[0.2em] text-sm rounded-xl hover:bg-zinc-200 active:scale-95 transition-all mt-auto">Order Physical Card</button>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
