<?php
$pageTitle = 'Terms of Service - AUTEUR Cinema';
require_once '../includes/header.php';
?>

<main class="min-h-screen pt-24 pb-40 px-6 max-w-4xl mx-auto relative z-10 animate-fly-in">
    <div class="mb-12">
        <h1 class="text-4xl md:text-5xl font-black font-headline tracking-tighter uppercase leading-none mb-4 text-white">
            Terms of Service
        </h1>
        <p class="text-zinc-500 font-medium text-sm tracking-wide">Last Updated: October 2026</p>
    </div>

    <div class="space-y-12 text-zinc-300 leading-relaxed font-medium">
        <section>
            <h2 class="text-xl font-bold text-white mb-4 uppercase tracking-widest">1. Acceptance of Terms</h2>
            <p class="text-zinc-400">By accessing and using the AUTEUR Cinema website, application, or purchasing tickets, you agree to be bound by these Terms of Service. If you do not agree, please refrain from utilizing our booking engine.</p>
        </section>

        <section>
            <h2 class="text-xl font-bold text-white mb-4 uppercase tracking-widest">2. Ticket Purchases & Refunds</h2>
            <p class="text-zinc-400 mb-4">All ticket sales are final unless otherwise stated by our specific premium-tier guarantees. Seat allocations are guaranteed for 5 minutes during the checkout polling phase.</p>
            <ul class="list-disc pl-5 text-zinc-400 space-y-2">
                <li>Refunds are only eligible 2 hours prior to the showtime.</li>
                <li>Processing fees are strictly non-refundable.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-bold text-white mb-4 uppercase tracking-widest">3. Theater Conduct</h2>
            <p class="text-zinc-400">AUTEUR Cinema reserves the right to deny admission or eject any person whose conduct is deemed disorderly, who uses vulgar or abusive language, or fails to comply with theater regulations. Recording video or audio inside the auditoriums is strictly prohibited by law.</p>
        </section>

        <section>
            <h2 class="text-xl font-bold text-white mb-4 uppercase tracking-widest">4. Privacy Data</h2>
            <p class="text-zinc-400">We store securely your digital footprint corresponding exclusively to transactional ticketing IDs and authentication state. We do not store plain-text passwords or native credit card numbers directly on our SQL shards.</p>
        </section>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
