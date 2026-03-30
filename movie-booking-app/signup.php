<?php
$pageTitle = 'Sign Up - AUTEUR Cinema';
require_once 'includes/header.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: movies.php');
    exit();
}

$signupError = isset($_GET['error']) ? $_GET['error'] : '';
$signupSuccess = isset($_GET['success']) ? $_GET['success'] : '';
?>

<main class="min-h-screen flex items-center justify-center px-6 py-24">
<div class="w-full max-w-md space-y-8">
<!-- Header -->
<div class="text-center">
<div class="inline-flex items-center justify-center w-16 h-16 bg-primary/20 rounded-full mb-6">
<span class="material-symbols-outlined text-3xl text-primary">person_add</span>
</div>
<h1 class="text-4xl md:text-5xl font-black font-headline tracking-tighter uppercase leading-none mb-4">
    Join AUTEUR Cinema
</h1>
<p class="text-zinc-400 font-medium">
    Create your account to start booking movies
</p>
</div>

<!-- Signup Form -->
<div class="glass-panel rounded-2xl p-8">
<?php if ($signupError): ?>
<div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-red-400">error</span>
<span class="text-red-400 font-semibold">
    <?php
    switch ($signupError) {
        case 'email_exists':
            echo 'An account with this email already exists';
            break;
        case 'password_mismatch':
            echo 'Passwords do not match';
            break;
        case 'weak_password':
            echo 'Password must be at least 8 characters long';
            break;
        case 'invalid_email':
            echo 'Please enter a valid email address';
            break;
        default:
            echo 'Registration failed. Please try again.';
    }
    ?>
</span>
</div>
</div>
<?php endif; ?>

<?php if ($signupSuccess): ?>
<div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-green-400">check_circle</span>
<span class="text-green-400 font-semibold">Account created successfully! Please log in.</span>
</div>
</div>
<?php endif; ?>

<form method="POST" action="api/signup.php" class="space-y-6">
<div>
<label for="name" class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Full Name</label>
<div class="relative">
<input type="text" id="name" name="name" required
       class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors"
       placeholder="John Doe">
<div class="absolute right-3 top-1/2 -translate-y-1/2">
<span class="material-symbols-outlined text-zinc-600">person</span>
</div>
</div>
</div>

<div>
<label for="email" class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Email Address</label>
<div class="relative">
<input type="email" id="email" name="email" required
       class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors"
       placeholder="your@email.com">
<div class="absolute right-3 top-1/2 -translate-y-1/2">
<span class="material-symbols-outlined text-zinc-600">email</span>
</div>
</div>
</div>

<div>
<label for="phone" class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Phone Number</label>
<div class="relative">
<input type="tel" id="phone" name="phone"
       class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors"
       placeholder="+1 (555) 123-4567">
<div class="absolute right-3 top-1/2 -translate-y-1/2">
<span class="material-symbols-outlined text-zinc-600">phone</span>
</div>
</div>
</div>

<div>
<label for="password" class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Password</label>
<div class="relative">
<input type="password" id="password" name="password" required
       class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors"
       placeholder="Create a strong password" minlength="8">
<div class="absolute right-3 top-1/2 -translate-y-1/2">
<span class="material-symbols-outlined text-zinc-600">lock</span>
</div>
</div>
<p class="text-xs text-zinc-500 mt-1">Must be at least 8 characters long</p>
</div>

<div>
<label for="confirm_password" class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Confirm Password</label>
<div class="relative">
<input type="password" id="confirm_password" name="confirm_password" required
       class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors"
       placeholder="Confirm your password">
<div class="absolute right-3 top-1/2 -translate-y-1/2">
<span class="material-symbols-outlined text-zinc-600">lock</span>
</div>
</div>
</div>

<div class="flex items-center">
<input type="checkbox" id="terms" name="terms" required class="w-4 h-4 text-primary bg-surface-container-high border-outline-variant/30 rounded focus:ring-primary focus:ring-2">
<label for="terms" class="ml-2 text-sm text-zinc-400">
    I agree to the <a href="#" class="text-primary hover:text-primary/80">Terms of Service</a> and <a href="#" class="text-primary hover:text-primary/80">Privacy Policy</a>
</label>
</div>

<button type="submit" class="w-full py-4 bg-gradient-to-br from-primary-container to-[#930000] text-on-primary-container font-black uppercase tracking-[0.2em] text-lg rounded-xl shadow-xl shadow-red-900/40 hover:scale-[1.02] active:scale-[0.98] transition-all">
    Create Account
</button>
</form>

<div class="mt-8 text-center">
<p class="text-zinc-400">
    Already have an account?
    <a href="login.php" class="text-primary font-semibold hover:text-primary/80">Sign in here</a>
</p>
</div>
</div>
</div>
</main>

<script>
// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword.value && this.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity('Passwords do not match');
    } else {
        confirmPassword.setCustomValidity('');
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>