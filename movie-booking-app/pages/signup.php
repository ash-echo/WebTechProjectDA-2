<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . 'pages/movies.php');
    exit();
}

$pageTitle = 'Sign Up - AUTEUR Cinema';
require_once '../includes/header.php';
?>

<main class="min-h-screen flex items-center justify-center px-6 py-24 relative overflow-hidden">
    <!-- Backsplash -->
    <div class="absolute inset-0 z-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=1920&h=1080&fit=crop')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/90 to-surface/80 z-0"></div>

    <div class="w-full max-w-md space-y-8 relative z-10 animate-fly-in" style="animation-delay: 0.1s;">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-black font-headline tracking-tighter uppercase leading-none mb-4">
                Join the Club
            </h1>
            <p class="text-zinc-400 font-medium">
                Create your AUTEUR account
            </p>
        </div>

        <!-- Signup Form -->
        <div class="glass-panel rounded-3xl p-8 hover:shadow-2xl hover:shadow-red-900/20 transition-all duration-500 border border-white/5">
            <form id="signupForm" class="space-y-5">
                <div>
                    <label for="name" class="block text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2">Full Name</label>
                    <div class="relative group">
                        <input type="text" id="name" name="name" required
                               class="w-full px-5 py-3 bg-surface-container border border-outline-variant/30 rounded-2xl text-zinc-100 placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all group-hover:border-outline-variant"
                               placeholder="John Doe">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            <span class="material-symbols-outlined text-zinc-500 group-focus-within:text-primary transition-colors text-sm">person</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2">Email Address</label>
                    <div class="relative group">
                        <input type="email" id="email" name="email" required
                               class="w-full px-5 py-3 bg-surface-container border border-outline-variant/30 rounded-2xl text-zinc-100 placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all group-hover:border-outline-variant"
                               placeholder="your@email.com">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            <span class="material-symbols-outlined text-zinc-500 group-focus-within:text-primary transition-colors text-sm">alternate_email</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2">Phone (Optional)</label>
                    <div class="relative group">
                        <input type="tel" id="phone" name="phone"
                               class="w-full px-5 py-3 bg-surface-container border border-outline-variant/30 rounded-2xl text-zinc-100 placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all group-hover:border-outline-variant"
                               placeholder="+1 234 567 8900">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            <span class="material-symbols-outlined text-zinc-500 group-focus-within:text-primary transition-colors text-sm">call</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2">Password</label>
                    <div class="relative group">
                        <input type="password" id="password" name="password" required minlength="8"
                               class="w-full px-5 py-3 bg-surface-container border border-outline-variant/30 rounded-2xl text-zinc-100 placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all group-hover:border-outline-variant"
                               placeholder="Min. 8 characters">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            <span class="material-symbols-outlined text-zinc-500 group-focus-within:text-primary transition-colors text-sm">lock</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="confirm_password" class="block text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2">Confirm Password</label>
                    <div class="relative group">
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
                               class="w-full px-5 py-3 bg-surface-container border border-outline-variant/30 rounded-2xl text-zinc-100 placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all group-hover:border-outline-variant"
                               placeholder="Repeat password">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            <span class="material-symbols-outlined text-zinc-500 group-focus-within:text-primary transition-colors text-sm">password</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-start gap-3 cursor-pointer group pt-2 pb-4">
                    <div class="relative flex items-start justify-center pt-0.5">
                        <input type="checkbox" id="terms" name="terms" required class="peer sr-only">
                        <div class="w-5 h-5 border-2 border-outline-variant rounded bg-transparent peer-checked:bg-primary peer-checked:border-primary transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-[14px] opacity-0 peer-checked:opacity-100 transition-opacity" style="font-variation-settings: 'wght' 700;">check</span>
                        </div>
                    </div>
                    <label for="terms" class="text-xs font-medium text-zinc-400 group-hover:text-zinc-200 transition-colors cursor-pointer leading-relaxed">
                        I agree to the <a href="#" class="text-primary hover:text-white transition-colors">Terms of Service</a> and <a href="#" class="text-primary hover:text-white transition-colors">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" id="submitBtn" class="relative w-full py-4 overflow-hidden rounded-2xl group active:scale-95 transition-all">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary to-orange-600 transition-transform group-hover:scale-105"></div>
                    <div class="relative flex items-center justify-center gap-2">
                        <span id="btnText" class="font-black text-white tracking-[0.2em] uppercase text-sm">Create Account</span>
                        <span id="btnLoader" class="hidden material-symbols-outlined text-white animate-spin">progress_activity</span>
                        <span id="btnIcon" class="material-symbols-outlined text-white text-sm transform group-hover:translate-x-1 transition-transform">arrow_right_alt</span>
                    </div>
                </button>
            </form>

            <div class="mt-8 text-center border-t border-outline-variant/20 pt-6">
                <p class="text-zinc-500 text-sm">
                    Already have an account? 
                    <a href="login.php" class="text-primary font-bold hover:text-white transition-colors ml-1 uppercase tracking-widest text-xs">Sign In</a>
                </p>
            </div>
        </div>
    </div>
</main>

<script>
document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        showToast('Passwords do not match.', 'error');
        return;
    }
    
    const form = e.target;
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');
    const btnIcon = document.getElementById('btnIcon');
    
    // UI Loading State
    submitBtn.disabled = true;
    btnText.textContent = 'CREATING...';
    btnLoader.classList.remove('hidden');
    btnIcon.classList.add('hidden');
    
    const formData = new FormData(form);
    
    fetch('<?php echo BASE_URL; ?>api/signup.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message + '. Redirecting to login...', 'success');
            setTimeout(() => {
                window.location.href = '<?php echo BASE_URL; ?>pages/' + data.redirect;
            }, 1500);
        } else {
            showToast(data.message, 'error');
            // Reset UI
            submitBtn.disabled = false;
            btnText.textContent = 'CREATE ACCOUNT';
            btnLoader.classList.add('hidden');
            btnIcon.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error occurred. Please try again.', 'error');
        // Reset UI
        submitBtn.disabled = false;
        btnText.textContent = 'CREATE ACCOUNT';
        btnLoader.classList.add('hidden');
        btnIcon.classList.remove('hidden');
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>