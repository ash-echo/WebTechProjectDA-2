<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . 'pages/movies.php');
    exit();
}

$pageTitle = 'Login - CINEFLOW';
require_once '../includes/header.php';
?>

<main class="min-h-screen flex items-center justify-center px-6 py-24 relative overflow-hidden">
    <!-- Backsplash -->
    <div class="absolute inset-0 z-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1489599735734-79b4e62b8c2f?w=1920&h=1080&fit=crop')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/90 to-surface/80 z-0"></div>

    <div class="w-full max-w-md space-y-8 relative z-10 animate-fly-in">
        <!-- Header -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/20 rounded-full mb-6 ring-4 ring-primary/10 animate-pulse-glow">
                <span class="material-symbols-outlined text-3xl text-primary">login</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-black font-headline tracking-tighter uppercase leading-none mb-4">
                Welcome Back
            </h1>
            <p class="text-zinc-400 font-medium">
                Sign in to your CINEFLOW account
            </p>
        </div>

        <!-- Login Form -->
        <div class="glass-panel rounded-3xl p-8 hover:shadow-2xl hover:shadow-red-900/20 transition-all duration-500 border border-white/5">
            <form id="loginForm" class="space-y-6">
                <div>
                    <label for="email" class="block text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2">Email Address</label>
                    <div class="relative group">
                        <input type="email" id="email" name="email" required
                               class="w-full px-5 py-4 bg-surface-container border border-outline-variant/30 rounded-2xl text-zinc-100 placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all group-hover:border-outline-variant"
                               placeholder="your@email.com">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            <span class="material-symbols-outlined text-zinc-500 group-focus-within:text-primary transition-colors">alternate_email</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2">Password</label>
                    <div class="relative group">
                        <input type="password" id="password" name="password" required
                               class="w-full px-5 py-4 bg-surface-container border border-outline-variant/30 rounded-2xl text-zinc-100 placeholder-zinc-600 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all group-hover:border-outline-variant"
                               placeholder="Enter your password">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            <span class="material-symbols-outlined text-zinc-500 group-focus-within:text-primary transition-colors">lock</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" id="remember" name="remember" class="peer sr-only">
                            <div class="w-5 h-5 border-2 border-outline-variant rounded bg-transparent peer-checked:bg-primary peer-checked:border-primary transition-all flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-[14px] opacity-0 peer-checked:opacity-100 transition-opacity" style="font-variation-settings: 'wght' 700;">check</span>
                            </div>
                        </div>
                        <label for="remember" class="text-sm font-medium text-zinc-400 group-hover:text-zinc-200 transition-colors cursor-pointer">Remember me</label>
                    </div>
                    <a href="forgot_password.php" class="text-sm text-primary hover:text-white transition-colors font-bold tracking-wide">Forgot password?</a>
                </div>

                <button type="submit" id="submitBtn" class="relative w-full py-4 overflow-hidden rounded-2xl group active:scale-95 transition-all">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary to-orange-600 transition-transform group-hover:scale-105"></div>
                    <div class="relative flex items-center justify-center gap-2">
                        <span id="btnText" class="font-black text-white tracking-[0.2em] uppercase text-sm">Sign In</span>
                        <span id="btnLoader" class="hidden material-symbols-outlined text-white animate-spin">progress_activity</span>
                        <span id="btnIcon" class="material-symbols-outlined text-white text-sm transform group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>
                </button>
            </form>

            <div class="mt-8 text-center border-t border-outline-variant/20 pt-6">
                <p class="text-zinc-500 text-sm">
                    New to CINEFLOW? 
                    <a href="signup.php" class="text-primary font-bold hover:text-white transition-colors ml-1 uppercase tracking-widest text-xs">Create Account</a>
                </p>
            </div>
        </div>
    </div>
</main>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');
    const btnIcon = document.getElementById('btnIcon');
    const submitBtn = document.getElementById('submitBtn');
    
    // UI Loading State
    submitBtn.disabled = true;
    btnText.textContent = 'AUTHENTICATING';
    btnLoader.classList.remove('hidden');
    btnIcon.classList.add('hidden');
    
    const formData = new FormData(form);
    
    fetch('<?php echo BASE_URL; ?>api/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => {
                window.location.href = '<?php echo BASE_URL; ?>pages/' + data.redirect;
            }, 1000);
        } else {
            showToast(data.message, 'error');
            // Reset UI
            submitBtn.disabled = false;
            btnText.textContent = 'SIGN IN';
            btnLoader.classList.add('hidden');
            btnIcon.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error occurred. Please try again.', 'error');
        // Reset UI
        submitBtn.disabled = false;
        btnText.textContent = 'SIGN IN';
        btnLoader.classList.add('hidden');
        btnIcon.classList.remove('hidden');
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>