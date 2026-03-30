<?php
$pageTitle = 'Login - AUTEUR Cinema';
require_once 'includes/header.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: movies.php');
    exit();
}

$loginError = isset($_GET['error']) ? $_GET['error'] : '';
?>

<main class="min-h-screen flex items-center justify-center px-6 py-24">
<div class="w-full max-w-md space-y-8">
<!-- Header -->
<div class="text-center">
<div class="inline-flex items-center justify-center w-16 h-16 bg-primary/20 rounded-full mb-6">
<span class="material-symbols-outlined text-3xl text-primary">login</span>
</div>
<h1 class="text-4xl md:text-5xl font-black font-headline tracking-tighter uppercase leading-none mb-4">
    Welcome Back
</h1>
<p class="text-zinc-400 font-medium">
    Sign in to your AUTEUR Cinema account
</p>
</div>

<!-- Login Form -->
<div class="glass-panel rounded-2xl p-8">
<?php if ($loginError): ?>
<div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-red-400">error</span>
<span class="text-red-400 font-semibold">
    <?php
    switch ($loginError) {
        case 'invalid_credentials':
            echo 'Invalid email or password';
            break;
        case 'account_disabled':
            echo 'Your account has been disabled';
            break;
        default:
            echo 'Login failed. Please try again.';
    }
    ?>
</span>
</div>
</div>
<?php endif; ?>

<form method="POST" action="api/login.php" class="space-y-6">
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
<label for="password" class="block text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Password</label>
<div class="relative">
<input type="password" id="password" name="password" required
       class="w-full px-4 py-3 bg-surface-container-high border border-outline-variant/30 rounded-xl text-zinc-100 placeholder-zinc-500 focus:border-primary focus:outline-none transition-colors"
       placeholder="Enter your password">
<div class="absolute right-3 top-1/2 -translate-y-1/2">
<span class="material-symbols-outlined text-zinc-600">lock</span>
</div>
</div>
</div>

<div class="flex items-center justify-between">
<div class="flex items-center">
<input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-primary bg-surface-container-high border-outline-variant/30 rounded focus:ring-primary focus:ring-2">
<label for="remember" class="ml-2 text-sm text-zinc-400">Remember me</label>
</div>
<a href="#" class="text-sm text-primary hover:text-primary/80 font-semibold">Forgot password?</a>
</div>

<button type="submit" class="w-full py-4 bg-gradient-to-br from-primary-container to-[#930000] text-on-primary-container font-black uppercase tracking-[0.2em] text-lg rounded-xl shadow-xl shadow-red-900/40 hover:scale-[1.02] active:scale-[0.98] transition-all">
    Sign In
</button>
</form>

<div class="mt-8 text-center">
<p class="text-zinc-400">
    Don't have an account?
    <a href="signup.php" class="text-primary font-semibold hover:text-primary/80">Sign up here</a>
</p>
</div>
</div>

<!-- Demo Credentials -->
<div class="glass-panel rounded-2xl p-6 mt-8">
<h3 class="text-lg font-bold font-headline uppercase tracking-tight mb-4 text-center">Demo Credentials</h3>
<div class="space-y-3 text-sm">
<div class="flex justify-between items-center py-2 px-3 bg-surface-container-high rounded-lg">
<span class="text-zinc-400">Email:</span>
<span class="text-zinc-100 font-mono">demo@auteur.com</span>
</div>
<div class="flex justify-between items-center py-2 px-3 bg-surface-container-high rounded-lg">
<span class="text-zinc-400">Password:</span>
<span class="text-zinc-100 font-mono">demo123</span>
</div>
</div>
</div>
</div>
</main>

<?php require_once 'includes/footer.php'; ?>