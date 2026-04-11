<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Lab Automation System - Enterprise Lab Management</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6366f1", // Indigo 500 (Matches Manual)
                        secondary: "#4f46e5", // Indigo 600
                        "background-light": "#F8FAFC", // Slate 50
                        "background-dark": "#0f172a", // Slate 900
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#1e293b", // Slate 800
                        "subtle-light": "#E2E8F0", // Slate 200
                        "subtle-dark": "#334155", // Slate 700
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        'xl': "0.75rem",
                        '2xl': "1rem",
                        '3xl': "1.5rem",
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                        'glow': '0 0 20px -5px rgba(99, 102, 241, 0.4)',
                    }
                },
            },
        };
    </script>
    <style>
        .glass-effect {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            background-image: linear-gradient(to right, #4f46e5, #0ea5e9);
        }

        .dark .gradient-text {
            background-image: linear-gradient(to right, #818cf8, #38bdf8);
        }
    </style>
</head>

<body
    class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-sans antialiased transition-colors duration-300">
    <nav
        class="anim-nav fixed w-full z-50 top-0 transition-all duration-300 bg-surface-light/90 dark:bg-background-dark/90 glass-effect border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="anim-logo flex-shrink-0 flex items-center gap-3 cursor-pointer">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white shadow-lg">
                        <span class="material-icons-outlined text-2xl">science</span>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white">LAB <span class="text-indigo-500">Enterprise</span></span>
                </div>
                <div class="anim-nav-links hidden md:flex space-x-8 items-center">
                    <a class="anim-nav-link text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-white transition"
                        href="#">Home</a>
                    <a class="anim-nav-link text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-white transition"
                        href="#features">Features</a>
                    <a class="anim-nav-link text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-white transition"
                        href="#workflow">Workflow</a>
                    <a class="anim-nav-link text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-white transition"
                        href="#comments">Reviews</a>
                    <a class="anim-nav-link text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-white transition"
                        href="#faq">FAQ</a>
                </div>
                <div class="anim-nav-btn hidden md:flex items-center gap-4">
                    <a class="px-5 py-2.5 rounded-lg bg-primary hover:bg-secondary text-sm font-medium text-white shadow-lg shadow-indigo-500/30 transition transform hover:-translate-y-0.5"
                        href="./users/login.php">
                        Log In
                    </a>
                </div>
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn"
                        class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 focus:outline-none">
                        <span class="material-icons-outlined">menu</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 absolute w-full left-0 top-20 shadow-lg overflow-hidden">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="#" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800 transition">Home</a>
                <a href="#features" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800 transition">Features</a>
                <a href="#workflow" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800 transition">Workflow</a>
                <a href="#pricing" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800 transition">Licensing</a>
                <a href="docs/User_Manual.php" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800 transition">Documentation</a>
                <div class="pt-4 mt-2 border-t border-slate-100 dark:border-slate-800">
                    <a href="./users/login.php" class="block w-full text-center px-5 py-3 rounded-xl bg-primary hover:bg-secondary text-base font-medium text-white shadow-lg shadow-indigo-500/30 transition">
                        Log In
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="relative pt-10 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-background-light dark:bg-background-dark">
        <!-- <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div
                class="absolute top-[-20%] left-[-10%] w-[60%] h-[60%] rounded-full bg-indigo-200/30 dark:bg-indigo-900/10 blur-[120px]">
            </div>
            <div
                class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-blue-200/30 dark:bg-cyan-900/10 blur-[120px]">
            </div>
        </div>  -->
        <!-- hero -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8 text-center lg:text-left">
                    <div
                        class="anim-hero-badge inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white dark:bg-surface-dark border border-slate-200 dark:border-slate-700 shadow-sm">
                        <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-300">v2.5.0 Now Available</span>
                    </div>
                    <h1
                        class="anim-hero-heading text-4xl lg:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-[1.1]">
                        Digitize Your <br>
                        <span class="gradient-text">Lab Operations</span>
                    </h1>
                    <p class="anim-hero-text text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        The central nervous system for modern research facilities. Streamline workflows, enforce security, and eliminate data silos with one unified platform.
                    </p>
                    <div class="anim-cta-wrapper flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a class="anim-cta-btn px-8 py-4 rounded-xl bg-primary hover:bg-secondary text-white font-semibold shadow-xl shadow-indigo-500/20 transition flex items-center justify-center gap-2"
                            href="#">
                            Start Free Trial <span class="material-icons-outlined text-sm">arrow_forward</span>
                        </a>
                        <a class="anim-cta-btn px-8 py-4 rounded-xl bg-white dark:bg-surface-dark border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition flex items-center justify-center gap-2"
                            href="docs/User_Manual.php">
                            <span class="material-icons-outlined text-sm">menu_book</span> User Manual
                        </a>
                    </div>
                    <div class="anim-hero-text pt-4 flex items-center justify-center lg:justify-start gap-6 text-sm text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-1"><span class="material-icons-outlined text-base text-green-500">check_circle</span> GDPR Compliant</span>
                        <span class="flex items-center gap-1"><span class="material-icons-outlined text-base text-green-500">check_circle</span> ISO 27001</span>
                    </div>
                </div>
                <div class="relative lg:h-[600px] flex items-center justify-center lg:justify-end">
                    <div
                        class="anim-phone-main relative w-full max-w-lg z-10 transform lg:translate-x-0 shadow-2xl rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-surface-dark overflow-hidden">
                        <!-- Dashboard UI Mockup -->
                        <div class="bg-slate-50 dark:bg-slate-900 p-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="flex-1 bg-white dark:bg-slate-800 h-8 rounded text-xs flex items-center px-3 text-slate-400">lab.dashboard.com/overview</div>
                        </div>
                        <div class="p-6 grid gap-6">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-indigo-50 dark:bg-slate-800 p-4 rounded-lg">
                                    <div class="text-indigo-500 mb-1 font-medium text-xs uppercase">Active Exp.</div>
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">24</div>
                                </div>
                                <div class="bg-green-50 dark:bg-slate-800 p-4 rounded-lg">
                                    <div class="text-green-600 mb-1 font-medium text-xs uppercase">Completed</div>
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">1,893</div>
                                </div>
                                <div class="bg-blue-50 dark:bg-slate-800 p-4 rounded-lg">
                                    <div class="text-blue-500 mb-1 font-medium text-xs uppercase">Team</div>
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">12</div>
                                </div>
                            </div>
                            <div class="h-48 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-end justify-between p-4 px-6 gap-2">
                                <div class="w-full bg-indigo-200 dark:bg-indigo-900/50 rounded-t" style="height: 40%"></div>
                                <div class="w-full bg-indigo-300 dark:bg-indigo-800/50 rounded-t" style="height: 60%"></div>
                                <div class="w-full bg-indigo-400 dark:bg-indigo-700/50 rounded-t" style="height: 35%"></div>
                                <div class="w-full bg-indigo-500 dark:bg-indigo-600 rounded-t" style="height: 80%"></div>
                                <div class="w-full bg-indigo-400 dark:bg-indigo-700/50 rounded-t" style="height: 55%"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
                                    <div class="w-8 h-8 rounded bg-orange-100 text-orange-600 flex items-center justify-center"><span class="material-icons-outlined text-sm">warning</span></div>
                                    <div class="flex-1 text-sm font-medium text-slate-700 dark:text-slate-300">Calibration Required: Centrifuge A</div>
                                    <div class="text-xs text-slate-400">2m ago</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div> 
   
     <!-- workflow  -->
    <section id="features" class="py-24 bg-white dark:bg-surface-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="anim-section-header text-center max-w-3xl mx-auto mb-16">
                <h2 class="anim-section-title text-3xl font-bold text-slate-900 dark:text-white mb-4">Workflow Automation</h2>
                <p class="anim-section-subtitle text-slate-600 dark:text-slate-400">Designed for high-throughput environments where data integrity matches speed.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-12 text-center relative">
                <div class="anim-step-card flex flex-col items-center group">
                    <div
                        class="anim-step-icon w-20 h-20 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center mb-6 transition-transform group-hover:scale-110 duration-300 shadow-glow text-primary">
                        <span class="material-icons-outlined text-4xl">cloud_queue</span>
                    </div>
                    <h3 class="anim-step-title text-xl font-bold text-slate-900 dark:text-white mb-2">Centralized Data</h3>
                    <p class="anim-step-text text-slate-500 dark:text-slate-400 text-sm max-w-xs">Eliminate silos. All experimental data, user logs, and inventories in one secure, relational database.</p>
                </div>
                <div class="anim-step-card flex flex-col items-center group">
                    <div
                        class="w-20 h-20 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center mb-6 transition-transform group-hover:scale-110 duration-300 shadow-glow text-primary">
                        <span class="material-icons-outlined text-4xl">admin_panel_settings</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Role-Based Security</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-xs">Granular RBAC ensures sensitive data is only accessible to authorized personnel like Lead Analysts.</p>
                </div>
                <div class="anim-step-card flex flex-col items-center group">
                    <div
                        class="w-20 h-20 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center mb-6 transition-transform group-hover:scale-110 duration-300 shadow-glow text-primary">
                        <span class="material-icons-outlined text-4xl">insights</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Real-time Reporting</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-xs">Instant dashboards and reporting tools allow teams to share insights instantly across locations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- stat  -->
    <section id="workflow" class="py-24 bg-background-light dark:bg-background-dark border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="anim-section-header text-center max-w-3xl mx-auto mb-20">
                <h2 class="anim-section-title text-3xl font-bold text-slate-900 dark:text-white mb-4">Unified Lab Management</h2>
                <p class="anim-section-subtitle text-slate-600 dark:text-slate-400">From sample intake to final report generation, we handle the complexity.</p>
            </div>
            <div class="space-y-12">
                <!-- Feature 1 -->
                <div class="anim-feature-card bg-white dark:bg-surface-dark rounded-2xl p-8 md:p-12 shadow-soft border border-slate-100 dark:border-slate-800">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div class="flex justify-center md:justify-start order-2 md:order-1">
                            <div class="w-full max-w-md bg-slate-50 dark:bg-slate-900 rounded-lg p-6 border border-slate-200 dark:border-slate-700">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="font-mono text-xs text-slate-400">EXP-2025-893</div>
                                    <div class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded font-medium">Valid</div>
                                </div>
                                <div class="space-y-3">
                                    <div class="h-2 bg-slate-200 dark:bg-slate-800 rounded w-3/4"></div>
                                    <div class="h-2 bg-slate-200 dark:bg-slate-800 rounded w-full"></div>
                                    <div class="h-2 bg-slate-200 dark:bg-slate-800 rounded w-5/6"></div>
                                </div>
                                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-between items-center">
                                    <div class="flex -space-x-2">
                                        <div class="w-6 h-6 rounded-full bg-indigo-500 border-2 border-white"></div>
                                        <div class="w-6 h-6 rounded-full bg-blue-500 border-2 border-white"></div>
                                    </div>
                                    <span class="material-icons-outlined text-slate-400">more_horiz</span>
                                </div>
                            </div>
                        </div>
                        <div class="anim-feature-content order-1 md:order-2">
                            <h3 class="anim-feature-title text-2xl font-bold text-slate-900 dark:text-white mb-4">Secure Data Tracking</h3>
                            <p class="anim-feature-text text-slate-600 dark:text-slate-400 mb-8 leading-relaxed">
                                Every interaction is logged. 'Experiment History' tracks every edit with a timestamp and user ID, providing a complete audit trail for compliance.
                            </p>
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-icons-outlined text-green-500 text-lg">check_circle</span> 3NF Normalized Database
                                </li>
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-icons-outlined text-green-500 text-lg">check_circle</span> AES-256 Encryption
                                </li>
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-icons-outlined text-green-500 text-lg">check_circle</span> Automated Backups
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="anim-feature-card bg-white dark:bg-surface-dark rounded-2xl p-8 md:p-12 shadow-soft border border-slate-100 dark:border-slate-800">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div class="order-1">
                            <h3 class="anim-feature-title text-2xl font-bold text-slate-900 dark:text-white mb-4">Modern User Experience</h3>
                            <p class="anim-feature-text text-slate-600 dark:text-slate-400 mb-8 leading-relaxed">
                                Built with a 'Premium Dark' sidebar and responsive layouts. The interface adapts to specific user roles, decluttering the view for Testers while providing power tools for Admins.
                            </p>
                            <button class="text-primary font-medium flex items-center gap-2 hover:gap-3 transition-all">
                                Explore Interface <span class="material-icons-outlined text-sm">arrow_forward</span>
                            </button>
                        </div>
                        <div class="flex justify-center md:justify-end order-2">
                            <div class="w-full max-w-md bg-slate-900 rounded-lg p-6 shadow-xl">
                                <div class="flex gap-4 mb-6">
                                    <div class="w-16 bg-indigo-600/20 rounded p-2 text-center text-xs text-indigo-400 font-bold border border-indigo-600/30">
                                        NAV
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <div class="h-2 bg-slate-700 rounded w-full"></div>
                                        <div class="h-2 bg-slate-800 rounded w-2/3"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="h-20 bg-slate-800 rounded border border-slate-700"></div>
                                    <div class="h-20 bg-slate-800 rounded border border-slate-700"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="anim-feature-card bg-white dark:bg-surface-dark rounded-2xl p-8 md:p-12 shadow-soft border border-slate-100 dark:border-slate-800">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div class="flex justify-center md:justify-start order-2 md:order-1">
                            <div class="w-full max-w-md bg-slate-50 dark:bg-slate-900 rounded-lg p-6 border border-slate-200 dark:border-slate-700">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex -space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-500 border-2 border-white dark:border-slate-800 flex items-center justify-center text-white text-xs font-bold shadow-sm">JD</div>
                                        <div class="w-10 h-10 rounded-full bg-blue-500 border-2 border-white dark:border-slate-800 flex items-center justify-center text-white text-xs font-bold shadow-sm">AS</div>
                                        <div class="w-10 h-10 rounded-full bg-green-500 border-2 border-white dark:border-slate-800 flex items-center justify-center text-white text-xs font-bold shadow-sm">MK</div>
                                    </div>
                                    <div class="h-2 bg-slate-200 dark:bg-slate-800 rounded flex-1"></div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-slate-200 dark:bg-slate-800 flex-shrink-0 flex items-center justify-center">
                                            <span class="material-icons-outlined text-slate-400 text-xs text-[10px]">person</span>
                                        </div>
                                        <div class="space-y-2 flex-1">
                                            <div class="h-1.5 bg-slate-200 dark:bg-slate-800 rounded w-1/3"></div>
                                            <div class="h-2 bg-slate-200 dark:bg-slate-800 rounded w-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 pl-11">
                                        <div class="w-8 h-8 rounded bg-indigo-100 dark:bg-indigo-900/40 flex-shrink-0 flex items-center justify-center">
                                            <span class="material-icons-outlined text-indigo-500 text-xs text-[10px]">reply</span>
                                        </div>
                                        <div class="space-y-2 flex-1">
                                            <div class="h-1.5 bg-indigo-200 dark:bg-indigo-900/60 rounded w-1/4"></div>
                                            <div class="h-2 bg-indigo-200 dark:bg-indigo-900/60 rounded w-5/6"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="anim-feature-content order-1 md:order-2">
                            <h3 class="anim-feature-title text-2xl font-bold text-slate-900 dark:text-white mb-4">Collaborative Research</h3>
                            <p class="anim-feature-text text-slate-600 dark:text-slate-400 mb-8 leading-relaxed">
                                Break down the walls between departments. Share experimental results, protocols, and inventory status in real-time across your entire organization.
                            </p>
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-icons-outlined text-green-500 text-lg">check_circle</span> Shared Project Spaces
                                </li>
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-icons-outlined text-green-500 text-lg">check_circle</span> In-line Commenting
                                </li>
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-icons-outlined text-green-500 text-lg">check_circle</span> Team Activity Feeds
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="anim-feature-card bg-white dark:bg-surface-dark rounded-2xl p-8 md:p-12 shadow-soft border border-slate-100 dark:border-slate-800">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div class="order-1">
                            <h3 class="anim-feature-title text-2xl font-bold text-slate-900 dark:text-white mb-4">Advanced Reporting & Export</h3>
                            <p class="anim-feature-text text-slate-600 dark:text-slate-400 mb-8 leading-relaxed">
                                Transform raw data into actionable insights. Generate ISO-compliant PDF reports or export entire datasets in 3NF normalized formats with a single click.
                            </p>
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-icons-outlined text-green-500 text-lg">check_circle</span> One-Click PDF Generation
                                </li>
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-icons-outlined text-green-500 text-lg">check_circle</span> CSV, JSON & XML Exports
                                </li>
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-icons-outlined text-green-500 text-lg">check_circle</span> Automated Compliance Filing
                                </li>
                            </ul>
                            <button class="text-primary font-medium flex items-center gap-2 hover:gap-3 transition-all">
                                View Reporting Suite <span class="material-icons-outlined text-sm">arrow_forward</span>
                            </button>
                        </div>
                        <div class="flex justify-center md:justify-end order-2">
                            <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-lg p-6 shadow-xl border border-slate-100 dark:border-slate-800">
                                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded bg-red-100 text-red-600 flex items-center justify-center">
                                            <span class="material-icons-outlined text-sm">picture_as_pdf</span>
                                        </div>
                                        <div class="text-xs font-bold text-slate-900 dark:text-white">Monthly_Audit_v4.pdf</div>
                                    </div>
                                    <div class="text-[10px] text-slate-400">1.2 MB</div>
                                </div>
                                <div class="space-y-3">
                                    <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded w-full"></div>
                                    <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded w-5/6"></div>
                                    <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded w-4/6"></div>
                                </div>
                                <div class="mt-6 pt-4 grid grid-cols-2 gap-3">
                                    <div class="py-2 rounded border border-slate-200 dark:border-slate-700 flex items-center justify-center gap-2 text-[10px] font-bold text-slate-600 dark:text-slate-400">
                                        <span class="material-icons-outlined text-sm">download</span> CSV
                                    </div>
                                    <div class="py-2 rounded border border-slate-200 dark:border-slate-700 flex items-center justify-center gap-2 text-[10px] font-bold text-slate-600 dark:text-slate-400">
                                        <span class="material-icons-outlined text-sm">download</span> JSON
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- comments -->
    <section id="comments" class="py-20 bg-white dark:bg-surface-dark overflow-hidden border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="anim-section-header text-center mb-16">
                <h2 class="anim-section-title text-3xl font-bold text-slate-900 dark:text-white mb-4">Trusted by Research Leaders</h2>
                <p class="anim-section-subtitle text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Powering labs that demand precision, security, and uptime.
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="anim-testimonial-card bg-slate-50 dark:bg-background-dark p-8 rounded-xl border border-slate-100 dark:border-slate-800">
                    <div class="flex text-yellow-400 mb-4 items-center gap-1">
                        <span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span>
                    </div>
                    <p class="anim-testimonial-text text-slate-600 dark:text-slate-300 italic mb-6">
                        "LAS has transformed our documentation process. The goal buckets and standardized reporting mean I don't even have to think about compliance."
                    </p>
                    <div class="anim-testimonial-author flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600">DR</div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm">Dr. Sarah Mery</h4>
                            <p class="text-xs text-slate-500">Lead Analyst</p>
                        </div>
                    </div>
                </div>
                <div class="anim-testimonial-card bg-slate-50 dark:bg-background-dark p-8 rounded-xl border border-slate-100 dark:border-slate-800">
                    <div class="flex text-yellow-400 mb-4 items-center gap-1">
                        <span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 italic mb-6">
                        "The RBAC system is incredibly intuitive. Onboarding new testers takes minutes instead of days."
                    </p>
                    <div class="flex items-center gap-3">
                         <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600">DH</div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm">David Hein</h4>
                            <p class="text-xs text-slate-500">Lab Director</p>
                        </div>
                    </div>
                </div>
                <div class="anim-testimonial-card bg-slate-50 dark:bg-background-dark p-8 rounded-xl border border-slate-100 dark:border-slate-800">
                    <div class="flex text-yellow-400 mb-4 items-center gap-1">
                        <span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star</span><span class="material-icons-outlined text-sm">star_half</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 italic mb-6">
                        "Finally, a system that handles our high data volume without slowing down. The real-time search is a game changer."
                    </p>
                    <div class="flex items-center gap-3">
                         <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600">JT</div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm">James Tuff</h4>
                            <p class="text-xs text-slate-500">IT Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- faq -->
    <section id="faq" class="py-20 bg-background-light dark:bg-background-dark border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="anim-section-header text-center mb-12">
                <h2 class="anim-section-title text-3xl font-bold text-slate-900 dark:text-white mb-2">Knowledge Base</h2>
                <p class="anim-section-subtitle text-slate-500 dark:text-slate-400">Common questions from lab administrators.</p>
            </div>
            <div class="space-y-4">
                <div class="anim-faq-item bg-white dark:bg-surface-dark rounded-xl p-6 shadow-soft cursor-pointer group hover:shadow-md transition border border-slate-100 dark:border-slate-800">
                    <div class="flex justify-between items-center">
                        <h3 class="anim-faq-question font-medium text-slate-900 dark:text-white">Can I export my data?</h3>
                        <span class="anim-faq-icon material-icons-outlined text-slate-400 group-hover:text-primary transition duration-300">add</span>
                    </div>
                    <div class="anim-faq-answer hidden overflow-hidden">
                        <p class="pt-4 text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                            Yes! Use the "GDPR Takeout" tool in your settings to download a full CSV or JSON archive of your experiments, logs, and profile data instantly.
                        </p>
                    </div>
                </div>
                <div class="anim-faq-item bg-white dark:bg-surface-dark rounded-xl p-6 shadow-soft cursor-pointer group hover:shadow-md transition border border-slate-100 dark:border-slate-800">
                    <div class="flex justify-between items-center">
                        <h3 class="anim-faq-question font-medium text-slate-900 dark:text-white">How does licensing work?</h3>
                        <span class="anim-faq-icon material-icons-outlined text-slate-400 group-hover:text-primary transition duration-300">add</span>
                    </div>
                    <div class="anim-faq-answer hidden overflow-hidden">
                        <p class="pt-4 text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                            We offer flexible per-seat licensing. Choose between Professional (up to 10 users) and Enterprise (unlimited) tiers based on your lab's specific needs.
                        </p>
                    </div>
                </div>
                <div class="anim-faq-item bg-white dark:bg-surface-dark rounded-xl p-6 shadow-soft cursor-pointer group hover:shadow-md transition border border-slate-100 dark:border-slate-800">
                    <div class="flex justify-between items-center">
                        <h3 class="anim-faq-question font-medium text-slate-900 dark:text-white">Is it compatible with LIMS?</h3>
                        <span class="anim-faq-icon material-icons-outlined text-slate-400 group-hover:text-primary transition duration-300">add</span>
                    </div>
                    <div class="anim-faq-answer hidden overflow-hidden">
                        <p class="pt-4 text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                            Absolutely. LAS provides a robust REST API that allows for seamless integration with most modern Laboratory Information Management Systems (LIMS).
                        </p>
                    </div>
                </div>
                <div class="anim-faq-item bg-white dark:bg-surface-dark rounded-xl p-6 shadow-soft cursor-pointer group hover:shadow-md transition border border-slate-100 dark:border-slate-800">
                    <div class="flex justify-between items-center">
                        <h3 class="anim-faq-question font-medium text-slate-900 dark:text-white">Can I host this on-premise?</h3>
                        <span class="anim-faq-icon material-icons-outlined text-slate-400 group-hover:text-primary transition duration-300">add</span>
                    </div>
                    <div class="anim-faq-answer hidden overflow-hidden">
                        <p class="pt-4 text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                            Yes. While we recommend our secure cloud hosting, Enterprise clients have the option for on-premise deployment with full Docker support.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="anim-footer bg-slate-900 text-white relative overflow-hidden mt-0 py-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="border-t border-slate-800 pt-12">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 mb-12">
                    <div class="col-span-2 lg:col-span-2">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="font-bold text-xl">Lab Automation</span>
                        </div>
                        <p class="text-slate-500 text-sm max-w-xs">Lab Automation Inc. © <br />Scientific & Research Data Systems.<br />All rights reserved.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-300 mb-4">Product</h4>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li><a class="hover:text-white transition" href="index/features.php">Features</a></li>
                            <li><a class="hover:text-white transition" href="index/security.php">Security</a></li>
                            <li><a class="hover:text-white transition" href="index/integrations.php">Integrations</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-300 mb-4">Resources</h4>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li><a class="hover:text-white transition" href="index/documentation.php">Documentation</a></li>
                            <li><a class="hover:text-white transition" href="index/api_reference.php">API Reference</a></li>
                            <li><a class="hover:text-white transition" href="index/system_status.php">System Status</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-300 mb-4">Company</h4>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li><a class="hover:text-white transition" href="index/about_us.php">About Us</a></li>
                            <li><a class="hover:text-white transition" href="index/contact.php">Contact</a></li>
                            <li><a class="hover:text-white transition" href="index/privacy_policy.php">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Background Glow -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-900/20 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
    </footer>


    <!-- GSAP Core & ScrollTrigger (Local Libraries) -->
    <script src="libraries/gsap/gsap.min.js"></script>
    <script src="libraries/gsap/ScrollTrigger.min.js"></script>
    
    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Mobile Menu Logic
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.contains('hidden');
                
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    gsap.fromTo(mobileMenu, 
                        { height: 0, opacity: 0 }, 
                        { height: 'auto', opacity: 1, duration: 0.3, ease: 'power2.out' }
                    );
                } else {
                    gsap.to(mobileMenu, { 
                        height: 0, 
                        opacity: 0, 
                        duration: 0.2, 
                        ease: 'power2.in',
                        onComplete: () => mobileMenu.classList.add('hidden')
                    });
                }
            });
        }

        // Keep original animation logic, just ensuring classes match new content
        gsap.from('.anim-logo', { x: -30, opacity: 0, duration: 0.6, delay: 0.2, ease: 'power2.out'});
        // gsap.from('.anim-nav-link', { y: -10, opacity: 0, duration: 0.5, stagger: 0.08, ease: 'power2.out' });
        gsap.from('.anim-nav-btn', { scale: 0.8, opacity: 0, duration: 0.5, delay: 0.6, ease: 'power2.out' });

        gsap.from('.anim-hero-badge', { scale: 0, opacity: 0, duration: 0.6, delay: 0.3, ease: 'back.out(1.7)' });
        gsap.from('.anim-hero-heading', { y: 50, opacity: 0, duration: 0.8, delay: 0.5, ease: 'power2.out' });
        gsap.from('.anim-hero-text', { y: 30, opacity: 0, duration: 0.6, delay: 0.7, ease: 'power2.out' });
        gsap.from('.anim-cta-btn', { scale: 0.8, opacity: 0, duration: 0.6, stagger: 0.2, ease: 'back.out(1.7)' });
        gsap.from('.anim-phone-main', { x: 50, opacity: 0, duration: 0.8, delay: 0.4, ease: 'power2.out' });

        gsap.utils.toArray('.anim-section-header').forEach(header => {
            gsap.from(header, { scrollTrigger: { trigger: header, start: 'top 80%', end: 'top 30%' }, y: 60, opacity: 0 });
        });

        gsap.from('.anim-step-card', { scrollTrigger: { trigger: '.anim-step-card', start: 'top 80%', end: 'top 30%' }, y: 80, opacity: 0, stagger: 0.2 });

         gsap.from('.anim-feature-card', { scrollTrigger: { trigger: '.anim-feature-card', start: 'top 80%', end: 'top 30%' }, y: 60, opacity: 0 });

        gsap.from('.anim-testimonial-card', { scrollTrigger: { trigger: '.anim-testimonial-card', start: 'top 80%', end: 'top 30%' }, y: 60, opacity: 0, stagger: 0.15 });

        gsap.from('.anim-footer-heading', { scrollTrigger: { trigger: '.anim-footer', start: 'top 80%', end: 'top 30%' }, y: 50, opacity: 0 });
        gsap.from('.anim-footer-text', { scrollTrigger: { trigger: '.anim-footer', start: 'top 80%', end: 'top 30%' }, y: 30, opacity: 0 });

        // FAQ Interactive Logic
        document.querySelectorAll('.anim-faq-item').forEach(item => {
            item.addEventListener('click', () => {
                const answer = item.querySelector('.anim-faq-answer');
                const icon = item.querySelector('.anim-faq-icon');
                const isOpen = !answer.classList.contains('hidden');

                // Close other items
                document.querySelectorAll('.anim-faq-answer').forEach(otherAnswer => {
                    if (otherAnswer !== answer && !otherAnswer.classList.contains('hidden')) {
                        const otherIcon = otherAnswer.parentElement.querySelector('.anim-faq-icon');
                        gsap.to(otherAnswer, { height: 0, duration: 0.3, onComplete: () => otherAnswer.classList.add('hidden') });
                        otherIcon.innerText = 'add';
                        gsap.to(otherIcon, { rotation: 0, duration: 0.3 });
                    }
                });

                if (isOpen) {
                    gsap.to(answer, { 
                        height: 0, 
                        duration: 0.3, 
                        onComplete: () => answer.classList.add('hidden') 
                    });
                    icon.innerText = 'add';
                    gsap.to(icon, { rotation: 0, duration: 0.3 });
                } else {
                    answer.classList.remove('hidden');
                    gsap.fromTo(answer, 
                        { height: 0 }, 
                        { height: 'auto', duration: 0.4, ease: 'power2.out' }
                    );
                    icon.innerText = 'remove';
                    gsap.to(icon, { rotation: 180, duration: 0.3 });
                }
            });
        });
    </script>
</body>
</html>
