<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School Inventory System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #050b14; /* Dark Navy Base */
            color: #e2e8f0;
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Ambient Glow Effect */
        .ambient-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            top: -150px;
            left: -150px;
            z-index: -1;
            pointer-events: none;
        }
        
        .ambient-glow-right {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
            z-index: -1;
            pointer-events: none;
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="antialiased min-h-screen relative overflow-x-hidden selection:bg-blue-500/30">
    <div class="ambient-glow"></div>
    <div class="ambient-glow-right"></div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-panel border-b border-white/10 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-3 cursor-default">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-500/20">
                        <!-- Lucide Icon for Box/Inventory -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-box"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">Inventory</span>
                </div>
                <!-- Links -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 shadow-lg shadow-blue-600/30 px-5 py-2.5 rounded-lg transition-all duration-300">
                            Masuk Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-white bg-white/10 hover:bg-white/20 border border-white/10 px-5 py-2.5 rounded-lg transition-all duration-300 backdrop-blur-md hidden sm:block">
                            Masuk
                        </a>
                        <a href="{{ route('login') }}" class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 shadow-lg shadow-blue-600/30 px-5 py-2.5 rounded-lg transition-all duration-300">
                            Mulai Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="pt-32 pb-16 sm:pt-40 sm:pb-24 lg:pb-32 px-4 mx-auto max-w-7xl flex flex-col items-center justify-center min-h-[90vh]">
        <div class="text-center max-w-4xl mx-auto px-4 z-10">
            <div class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold text-blue-400 ring-1 ring-inset ring-blue-500/20 bg-blue-500/10 mb-8 animate-fade-in-up">
                <span class="flex h-2 w-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                Sistem Inventaris Sekolah v2.0
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 animate-fade-in-up delay-100 text-white leading-tight">
                Manajemen Aset yang <span class="gradient-text">Lebih Cerdas.</span>
            </h1>
            
            <p class="mt-6 text-lg md:text-xl leading-8 text-gray-400 max-w-2xl mx-auto animate-fade-in-up delay-200">
                Lacak barang, kelola peminjaman, dan pantau ketersediaan stok secara real-time dengan antarmuka yang dirancang sangat teliti untuk kemudahan Anda.
            </p>
            
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up delay-300">
                <a href="{{ route('login') }}" class="w-full sm:w-auto rounded-xl bg-blue-600 px-8 py-4 flex items-center justify-center gap-2 text-base font-semibold text-white shadow-sm hover:bg-blue-500 hover:shadow-lg hover:shadow-blue-500/30 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-300 transform hover:-translate-y-1">
                    Masuk ke Sistem
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                <a href="#features" class="w-full sm:w-auto text-base font-semibold leading-6 text-gray-300 hover:text-white transition-colors py-4 px-8 rounded-xl hover:bg-white/5 border border-transparent hover:border-white/10 flex items-center justify-center">
                    Pelajari Fitur <span aria-hidden="true" class="ml-2">↓</span>
                </a>
            </div>
        </div>

        <!-- Dashboard mockup to show off -->
        <div class="mt-16 sm:mt-24 w-full max-w-5xl mx-auto animate-fade-in-up delay-[400ms] z-10 relative">
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl blur opacity-20"></div>
            <div class="glass-panel rounded-2xl p-2 md:p-3 relative">
                <div class="rounded-xl bg-[#0a0f1c] border border-white/5 overflow-hidden flex flex-col w-full aspect-video shadow-2xl">
                    <!-- Browser Header Mockup -->
                    <div class="h-10 border-b border-white/10 bg-[#0f1523] flex items-center px-4 gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                        <div class="ml-4 flex-1 flex justify-center">
                             <div class="w-1/2 md:w-1/3 h-6 bg-white/5 rounded-md border border-white/5 flex items-center px-3">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 mr-2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                 <div class="w-1/2 h-2 bg-gray-600/50 rounded"></div>
                             </div>
                        </div>
                    </div>
                    <!-- Body Mockup -->
                    <div class="flex-1 p-6 relative bg-gradient-to-br from-[#0a0f1c] to-[#0f1523]">
                         <!-- A simple representation of the admin view -->
                         <div class="w-full h-full flex gap-6">
                            <!-- Sidebar Mockup -->
                            <div class="hidden md:flex w-64 h-full bg-white/5 rounded-xl border border-white/5 flex-col gap-3 p-5 shadow-inner">
                                <div class="flex items-center gap-3 mb-6">
                                     <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center"><div class="w-4 h-4 bg-blue-500 rounded-sm"></div></div>
                                     <div class="h-4 w-24 bg-white/20 rounded"></div>
                                </div>
                                <div class="h-8 w-full bg-blue-600/20 border border-blue-500/30 rounded flex items-center px-3 gap-3">
                                     <div class="w-4 h-4 rounded bg-blue-400"></div>
                                     <div class="h-3 w-1/2 bg-blue-400/80 rounded"></div>
                                </div>
                                <div class="h-8 w-full bg-transparent border border-transparent rounded flex items-center px-3 gap-3">
                                     <div class="w-4 h-4 rounded bg-gray-500"></div>
                                     <div class="h-3 w-2/3 bg-gray-500/80 rounded"></div>
                                </div>
                                <div class="h-8 w-full bg-transparent border border-transparent rounded flex items-center px-3 gap-3">
                                     <div class="w-4 h-4 rounded bg-gray-500"></div>
                                     <div class="h-3 w-1/2 bg-gray-500/80 rounded"></div>
                                </div>
                                <div class="mt-auto h-12 w-full bg-white/5 rounded-lg border border-white/5 flex items-center px-3 gap-3">
                                     <div class="w-8 h-8 rounded-full bg-gray-600"></div>
                                     <div class="flex-1">
                                         <div class="h-2 w-2/3 bg-white/20 rounded mb-1"></div>
                                         <div class="h-1.5 w-1/2 bg-gray-500/50 rounded"></div>
                                     </div>
                                </div>
                            </div>
                            <!-- Main Content Mockup -->
                            <div class="flex-1 h-full flex flex-col gap-6">
                                <!-- Header area -->
                                <div class="flex justify-between items-center">
                                    <div>
                                         <div class="h-6 w-32 bg-white/20 rounded mb-2"></div>
                                         <div class="h-3 w-48 bg-gray-500/50 rounded"></div>
                                    </div>
                                    <div class="h-10 w-28 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20">
                                         <div class="h-3 w-16 bg-white/80 rounded"></div>
                                    </div>
                                </div>
                                <!-- Stats row -->
                                <div class="flex gap-4">
                                     <div class="flex-1 h-24 bg-white/5 border border-white/5 rounded-xl p-4 flex flex-col justify-between relative overflow-hidden">
                                         <div class="absolute right-0 top-0 w-16 h-16 bg-blue-500/10 rounded-bl-full"></div>
                                         <div class="h-3 w-20 bg-gray-500/50 rounded"></div>
                                         <div class="h-8 w-12 bg-white/20 rounded"></div>
                                     </div>
                                     <div class="flex-1 h-24 bg-white/5 border border-white/5 rounded-xl p-4 flex flex-col justify-between relative overflow-hidden">
                                         <div class="absolute right-0 top-0 w-16 h-16 bg-green-500/10 rounded-bl-full"></div>
                                         <div class="h-3 w-24 bg-gray-500/50 rounded"></div>
                                         <div class="h-8 w-16 bg-white/20 rounded"></div>
                                     </div>
                                     <div class="flex-1 h-24 bg-white/5 border border-white/5 rounded-xl p-4 flex flex-col justify-between relative overflow-hidden">
                                         <div class="absolute right-0 top-0 w-16 h-16 bg-purple-500/10 rounded-bl-full"></div>
                                         <div class="h-3 w-16 bg-gray-500/50 rounded"></div>
                                         <div class="h-8 w-10 bg-white/20 rounded"></div>
                                     </div>
                                </div>
                                <!-- Table area -->
                                <div class="flex-1 bg-white/5 rounded-xl border border-white/5 overflow-hidden flex flex-col">
                                    <div class="h-12 border-b border-white/10 w-full flex items-center px-5 gap-4">
                                         <div class="h-3 w-1/4 bg-gray-400/50 rounded"></div>
                                         <div class="h-3 w-1/4 bg-gray-400/50 rounded"></div>
                                         <div class="h-3 w-1/4 bg-gray-400/50 rounded text-right"></div>
                                    </div>
                                    <!-- Rows -->
                                    <div class="h-14 border-b border-white/5 w-full flex items-center px-5 gap-4 bg-white/[0.02] hover:bg-white/[0.05] transition-colors cursor-default">
                                         <div class="h-3 w-1/4 bg-white/20 rounded"></div>
                                         <div class="h-5 w-20 bg-green-500/20 border border-green-500/30 rounded-full"></div>
                                         <div class="flex-1 flex justify-end gap-2">
                                              <div class="w-6 h-6 rounded bg-white/10"></div>
                                              <div class="w-6 h-6 rounded bg-white/10"></div>
                                         </div>
                                    </div>
                                    <div class="h-14 border-b border-white/5 w-full flex items-center px-5 gap-4 hover:bg-white/[0.05] transition-colors cursor-default">
                                         <div class="h-3 w-1/5 bg-white/20 rounded"></div>
                                         <div class="h-5 w-24 bg-yellow-500/20 border border-yellow-500/30 rounded-full"></div>
                                         <div class="flex-1 flex justify-end gap-2">
                                              <div class="w-6 h-6 rounded bg-white/10"></div>
                                              <div class="w-6 h-6 rounded bg-white/10"></div>
                                         </div>
                                    </div>
                                    <div class="h-14 border-b border-white/5 w-full flex items-center px-5 gap-4 bg-white/[0.02] hover:bg-white/[0.05] transition-colors cursor-default">
                                         <div class="h-3 w-1/4 bg-white/20 rounded"></div>
                                         <div class="h-5 w-16 bg-blue-500/20 border border-blue-500/30 rounded-full"></div>
                                         <div class="flex-1 flex justify-end gap-2">
                                              <div class="w-6 h-6 rounded bg-white/10"></div>
                                              <div class="w-6 h-6 rounded bg-white/10"></div>
                                         </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Features Section -->
    <section id="features" class="py-24 relative z-10 border-t border-white/5 bg-[#050b14]/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center md:text-left mb-16 max-w-2xl">
                <h2 class="text-3xl font-bold tracking-tight text-white md:text-5xl mb-4">Semua yang Anda butuhkan.</h2>
                <p class="text-lg text-gray-400">Dirancang khusus untuk memenuhi standar pencatatan inventaris tertinggi dengan performa cepat dan navigasi yang sangat mudah.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-panel p-8 rounded-2xl hover:bg-white/[0.04] transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-12 h-12 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center justify-center text-blue-400 mb-6 group-hover:scale-110 group-hover:bg-blue-500/20 transition-all shadow-[0_0_15px_rgba(59,130,246,0.1)] group-hover:shadow-[0_0_20px_rgba(59,130,246,0.2)]">
                        <!-- Radar icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-radar"><path d="M19.07 4.93A10 10 0 0 0 6.99 3.34"/><path d="M4 6h.01"/><path d="M2.29 9.62A10 10 0 1 0 21.31 8.35"/><path d="M16.24 7.76A6 6 0 1 0 8.23 16.67"/><path d="M12 18h.01"/><path d="M17.99 11.66A6 6 0 0 1 15.77 16.67"/><circle cx="12" cy="12" r="2"/><path d="m13.41 10.59 5.66-5.66"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3 tracking-tight">Pelacakan Real-time</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Pantau secara langsung setiap pergerakan barang, ketersediaan stok aktual, dan status barang. Sistem akan memvalidasi persediaan otomatis.</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-panel p-8 rounded-2xl hover:bg-white/[0.04] transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-12 h-12 bg-purple-500/10 border border-purple-500/20 rounded-xl flex items-center justify-center text-purple-400 mb-6 group-hover:scale-110 group-hover:bg-purple-500/20 transition-all shadow-[0_0_15px_rgba(168,85,247,0.1)] group-hover:shadow-[0_0_20px_rgba(168,85,247,0.2)]">
                        <!-- Repeat/Exchange icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-repeat"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3 tracking-tight">Alur Peminjaman Mudah</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Proses peminjaman dan pengembalian barang dirancang dengan sangat ringkas dan transparan sehingga meminimalkan kesalahan identifikasi.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-panel p-8 rounded-2xl hover:bg-white/[0.04] transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-12 h-12 bg-indigo-500/10 border border-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400 mb-6 group-hover:scale-110 group-hover:bg-indigo-500/20 transition-all shadow-[0_0_15px_rgba(99,102,241,0.1)] group-hover:shadow-[0_0_20px_rgba(99,102,241,0.2)]">
                        <!-- Bar chart icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart-3"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3 tracking-tight">Riwayat & Laporan Terperinci</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Setiap aktivitas dan pergerakan aset tercatat secara rapi di database. Cetak dan ekspor laporan penggunaan barang ke format Excel (XLSX).</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/5 py-10 w-full text-center z-10 relative bg-[#03060a]">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                <span class="font-semibold text-white tracking-tight">Inventory</span>
            </div>
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} Sistem Inventaris Sekolah. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>
