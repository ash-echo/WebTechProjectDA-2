<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . 'index.php');
    exit();
}

$pageTitle = 'Forgot Password - CINEFLOW';
require_once '../includes/header.php';
?>

<main class="min-h-screen flex items-center justify-center px-6 py-24 relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1489599735734-79b4e62b8c2f?w=1920')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/90 to-surface/80 z-0"></div>

    <div class="w-full max-w-md space-y-8 relative z-10 animate-fly-in">
        <div class="text-center">
            <h1 class="text-4xl font-black font-headline tracking-tighter uppercase leading-none mb-4 text-white">Reset Password</h1>
            <p id="stepDescription" class="text-zinc-400 font-medium">Enter your email to receive a 6-digit OTP.</p>
        </div>

        <div class="glass-panel rounded-3xl p-8 border border-white/5">
            <!-- Step 1: Email -->
            <form id="emailForm" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-zinc-400 uppercase tracking-widest mb-2">Email Address</label>
                    <input type="email" id="email" required class="w-full px-5 py-4 bg-surface-container border border-outline-variant/30 rounded-2xl text-white outline-none focus:border-primary transition-all">
                </div>
                <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-black uppercase tracking-widest hover:brightness-110 transition-all">Send OTP</button>
            </form>

            <!-- Step 2: OTP (Hidden) -->
            <form id="otpForm" class="space-y-6 hidden">
                <div>
                    <label class="block text-xs font-bold text-zinc-400 uppercase tracking-widest mb-2">Verification Code</label>
                    <input type="text" id="otp" required maxlength="6" class="w-full px-5 py-4 bg-surface-container border border-outline-variant/30 rounded-2xl text-white outline-none focus:border-primary transition-all text-center text-2xl tracking-[0.5em] font-black" placeholder="000000">
                </div>
                <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-black uppercase tracking-widest hover:brightness-110 transition-all">Verify OTP</button>
            </form>

            <!-- Step 3: Password (Hidden) -->
            <form id="passwordForm" class="space-y-6 hidden">
                <div>
                    <label class="block text-xs font-bold text-zinc-400 uppercase tracking-widest mb-2">New Password</label>
                    <input type="password" id="newPassword" required minlength="8" class="w-full px-5 py-4 bg-surface-container border border-outline-variant/30 rounded-2xl text-white outline-none focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-zinc-400 uppercase tracking-widest mb-2">Confirm New Password</label>
                    <input type="password" id="confirmNewPassword" required minlength="8" class="w-full px-5 py-4 bg-surface-container border border-outline-variant/30 rounded-2xl text-white outline-none focus:border-primary transition-all">
                </div>
                <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-black uppercase tracking-widest hover:brightness-110 transition-all">Reset Password</button>
            </form>

            <div class="mt-6 text-center">
                <a href="login.php" class="text-zinc-500 hover:text-white text-sm font-bold uppercase tracking-widest transition-colors">Back to Login</a>
            </div>
        </div>
    </div>
</main>

<script>
const emailForm = document.getElementById('emailForm');
const otpForm = document.getElementById('otpForm');
const passwordForm = document.getElementById('passwordForm');
const stepDesc = document.getElementById('stepDescription');

let userEmail = '';

emailForm.addEventListener('submit', function(e) {
    e.preventDefault();
    userEmail = document.getElementById('email').value;
    
    const fd = new FormData();
    fd.append('email', userEmail);
    
    fetch('<?php echo BASE_URL; ?>api/forgot_password.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            emailForm.classList.add('hidden');
            otpForm.classList.remove('hidden');
            stepDesc.textContent = 'Check your email for the 6-digit code.';
        } else {
            showToast(data.message, 'error');
        }
    });
});

otpForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const otp = document.getElementById('otp').value;
    
    const fd = new FormData();
    fd.append('email', userEmail);
    fd.append('otp', otp);
    
    fetch('<?php echo BASE_URL; ?>api/verify_otp.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            otpForm.classList.add('hidden');
            passwordForm.classList.remove('hidden');
            stepDesc.textContent = 'Set your new secure password.';
        } else {
            showToast(data.message, 'error');
        }
    });
});

passwordForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const pass = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmNewPassword').value;
    
    if (pass !== confirm) {
        showToast('Passwords do not match', 'error');
        return;
    }
    
    const fd = new FormData();
    fd.append('email', userEmail);
    fd.append('password', pass);
    fd.append('otp', document.getElementById('otp').value); // Security verification
    
    fetch('<?php echo BASE_URL; ?>api/reset_password.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => window.location.href = 'login.php', 2000);
        } else {
            showToast(data.message, 'error');
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
