<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduLearn - Platform Belajar Modern</title>
    

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    

    <link rel="stylesheet" href="/edulearn/assets/css/style.css">
    

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</head>
<body x-data class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

<nav class="fixed w-full z-50 glass transition-all duration-300" x-data="{ scrolled: false, mobileMenu: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    <div class="container mx-auto">
        <div class="flex justify-between items-center" style="height: 4rem;">
         
            <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" onclick="window.location.href='/edulearn/index.php'">
                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">E</div>
                <span class="font-bold text-xl tracking-tight text-slate-800">EduLearn</span>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="/edulearn/dashboard.php" class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                <a href="/edulearn/materials/index.php" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/materials/') !== false ? 'active' : ''; ?>">Materi</a>
                <a href="/edulearn/forum/index.php" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/forum/') !== false ? 'active' : ''; ?>">Forum</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/edulearn/materials/create.php" class="nav-link">Upload Materi</a>
                <?php endif; ?>
            </div>

            <div class="hidden md:flex items-center gap-4">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 pl-1 pr-4 py-1 rounded-full border border-slate-200 hover:border-primary-200 bg-white hover:bg-slate-50 transition-all shadow-sm group">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold shadow-sm group-hover:shadow-md transition-shadow">
                                <?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?>
                            </div>
                            <span class="text-sm font-medium text-slate-700 group-hover:text-primary-700 transition-colors"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-2 ring-1 ring-black ring-opacity-5 focus:outline-none" 
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-slate-50 mb-1">
                                <p class="text-sm font-medium text-slate-900 truncate">Hai, <?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></p>
                                <p class="text-xs text-slate-500 truncate"><?php echo isset($_SESSION['role']) && $_SESSION['role'] == 'admin' ? 'Administrator' : 'Student'; ?></p>
                            </div>
                            <a href="/edulearn/dashboard.php" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition-colors">Dashboard</a>
                            <a href="/edulearn/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">Sign out</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/edulearn/login.php" class="text-sm font-medium text-slate-600 hover:text-primary-600">Login</a>
                    <a href="/edulearn/register.php" class="btn btn-primary btn-sm">Register</a>
                <?php endif; ?>
            </div>

            <div class="md:hidden flex items-center">
                <button @click="mobileMenu = !mobileMenu" class="text-slate-600 hover:text-primary-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenu" x-collapse class="md:hidden bg-white border-t border-slate-100">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="/edulearn/dashboard.php" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Dashboard</a>
            <a href="/edulearn/materials/index.php" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Materi</a>
            <a href="/edulearn/forum/index.php" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Forum</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/edulearn/materials/create.php" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Upload Materi</a>
                    <a href="/edulearn/profile.php" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50">Profile</a>
                    <p class="px-3 py-2 text-sm text-slate-500">Log in as <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    <a href="/edulearn/logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Logout</a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 gap-2 px-3 pt-2">
                    <a href="/edulearn/login.php" class="block text-center px-4 py-2 border border-slate-200 rounded-lg text-slate-700 font-medium hover:bg-slate-50">Login</a>
                    <a href="/edulearn/register.php" class="block text-center px-4 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="flex-grow pt-20">
