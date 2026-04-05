<?php
$pageTitle = 'Feedback - CINEFLOW';
require_once '../includes/header.php';
?>

<main class="min-h-screen pt-24 pb-40 px-6 max-w-3xl mx-auto relative z-10 animate-fly-in">
    <div class="text-center mt-12 mb-12">
        <span class="material-symbols-outlined text-5xl text-primary mb-4 block">rate_review</span>
        <h1 class="text-4xl md:text-5xl font-black font-headline tracking-tighter uppercase leading-none mb-4">
            We value your Feedback
        </h1>
        <p class="text-zinc-400 font-medium">Tell us about your latest cinematic experience. We are constantly striving to achieve perfection.</p>
    </div>

    <div class="glass-panel p-8 md:p-12 rounded-3xl relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-primary/10 rounded-full blur-[80px] pointer-events-none"></div>
        
        <form class="space-y-6 relative z-10" id="feedback-form">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-2">Your Name</label>
                    <input type="text" class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant/20 rounded-xl text-white placeholder-zinc-600 focus:border-primary focus:outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-2">Email Address</label>
                    <input type="email" class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant/20 rounded-xl text-white placeholder-zinc-600 focus:border-primary focus:outline-none transition-all" required>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-2">Topic</label>
                <select class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant/20 rounded-xl text-white focus:border-primary focus:outline-none transition-all appearance-none cursor-pointer">
                    <option class="bg-surface">General Experience</option>
                    <option class="bg-surface">Theater Issues</option>
                    <option class="bg-surface">Website / App Bug</option>
                    <option class="bg-surface">Food & Beverage</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-2">Message</label>
                <textarea rows="5" class="w-full px-5 py-4 bg-surface-container-high border border-outline-variant/20 rounded-xl text-white placeholder-zinc-600 focus:border-primary focus:outline-none transition-all resize-none" placeholder="Share your details here..." required></textarea>
            </div>

            <button type="submit" class="w-full py-5 bg-primary text-white font-black uppercase tracking-[0.2em] text-sm rounded-xl hover:brightness-110 active:scale-95 transition-all">Submit Feedback</button>
        </form>
    </div>
</main>

<script>
document.getElementById('feedback-form').addEventListener('submit', function(e) {
    e.preventDefault();
    if(typeof showToast === 'function') {
        showToast('Thank you! Your feedback has been transmitted successfully.', 'success');
        this.reset();
    } else {
        alert("Thanks! Your feedback was submitted.");
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
