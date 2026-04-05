<!-- Footer -->
<footer class="bg-[#0a0a0a] border-t border-outline-variant/20 w-full pt-16 pb-8 mt-20 relative overflow-hidden flex-shrink-0">
    <!-- Background Glow -->
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-3/4 h-32 bg-primary/5 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-12 px-6 md:px-12 max-w-7xl mx-auto relative z-10">
        <div>
            <span class="text-2xl font-black text-white font-headline tracking-tighter block mb-4 group cursor-pointer inline-flex items-center gap-2">
                CINEFLOW <div class="w-2 h-2 rounded-full bg-primary group-hover:animate-ping"></div>
            </span>
            <p class="text-zinc-500 text-sm leading-relaxed tracking-wide">
                Your premier destination for the art of cinema. Discover, book, and experience the world's most compelling stories in unparalleled fidelity.
            </p>
        </div>
        
        <div class="space-y-4">
            <h4 class="font-headline text-xs tracking-[0.2em] font-black text-primary uppercase">Company</h4>
            <ul class="space-y-3">
                <li><a class="text-zinc-400 hover:text-white transition-colors text-sm font-medium hover:translate-x-1 inline-block transform duration-300" href="<?php echo BASE_URL; ?>pages/movies.php">Movies</a></li>
                <li><a class="text-zinc-400 hover:text-white transition-colors text-sm font-medium hover:translate-x-1 inline-block transform duration-300" href="<?php echo BASE_URL; ?>index.php#showtimes">Showtimes</a></li>
                <li><a class="text-zinc-400 hover:text-white transition-colors text-sm font-medium hover:translate-x-1 inline-block transform duration-300" href="<?php echo BASE_URL; ?>pages/gift_cards.php">Gift Cards</a></li>
            </ul>
        </div>
        
        <div class="space-y-4">
            <h4 class="font-headline text-xs tracking-[0.2em] font-black text-primary uppercase">Support</h4>
            <ul class="space-y-3">
                <li><a class="text-zinc-400 hover:text-white transition-colors text-sm font-medium hover:translate-x-1 inline-block transform duration-300" href="<?php echo BASE_URL; ?>pages/help_center.php">Help Center</a></li>
                <li><a class="text-zinc-400 hover:text-white transition-colors text-sm font-medium hover:translate-x-1 inline-block transform duration-300" href="<?php echo BASE_URL; ?>pages/feedback.php">Feedback</a></li>
            </ul>
        </div>
        
        <div class="space-y-4">
            <h4 class="font-headline text-xs tracking-[0.2em] font-black text-primary uppercase">Legal</h4>
            <ul class="space-y-3">
                <li><a class="text-zinc-400 hover:text-white transition-colors text-sm font-medium hover:translate-x-1 inline-block transform duration-300" href="<?php echo BASE_URL; ?>pages/terms.php">Terms of Service</a></li>
            </ul>
            <div class="flex gap-4 pt-4">
                <a href="#" class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-zinc-400 hover:bg-primary hover:text-white transition-all transform hover:scale-110"><span class="material-symbols-outlined text-sm">social_leaderboard</span></a>
                <a href="#" class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-zinc-400 hover:bg-primary hover:text-white transition-all transform hover:scale-110"><span class="material-symbols-outlined text-sm">movie</span></a>
                <a href="#" class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-zinc-400 hover:bg-primary hover:text-white transition-all transform hover:scale-110"><span class="material-symbols-outlined text-sm">videocam</span></a>
            </div>
        </div>
    </div>
    
    <div class="mt-16 px-6 md:px-12 max-w-7xl mx-auto border-t border-outline-variant/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 relative z-10">
        <p class="text-zinc-600 text-[10px] tracking-[0.2em] uppercase font-bold">© <?php echo date('Y'); ?> CINEFLOW CINEMA. ALL RIGHTS RESERVED.</p>
        <div class="flex items-center gap-2 group cursor-pointer">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse group-hover:animate-none"></span>
            <span class="text-zinc-400 group-hover:text-zinc-200 transition-colors text-[10px] font-bold uppercase tracking-widest">Systems Operational</span>
        </div>
    </div>
</footer>
</body>
</html>