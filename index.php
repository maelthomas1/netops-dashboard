<?php
require_once 'includes/api_client.php';
$devices = getDevicesData();
$onlineDevices = getOnlineDevicesData();

$totalDevices = count($devices);
$activeDevices = count($onlineDevices);
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetOps</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased h-full flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between hidden md:flex shadow-sm">
        <div>
            <div class="h-16 flex items-center px-6 border-b border-slate-100 gap-3">
                <div class="bg-indigo-600 text-white p-2 rounded-lg text-sm shadow-sm">
                    <i class="fa-solid fa-server"></i>
                </div>
                    <img src="./images/logo_texte_NetOps.jpg" alt="Logo NetOps" class="h-12">
            </div>
            <nav class="p-4 space-y-1">
                <a href="index.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="fa-solid fa-chart-pie w-5 text-indigo-500"></i> Tableau de bord
                </a>
                <a href="inventory.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                    <i class="fa-solid fa-desktop w-5 text-slate-400"></i> Inventaire
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col min-h-screen overflow-y-auto">
        <header class="h-16 bg-white/80 backdrop-blur border-b border-slate-200 px-6 flex justify-between items-center sticky top-0 z-10 shadow-xs">
            <h1 class="text-sm font-semibold text-slate-800">Tableau de bord</h1>
        </header>

        <main class="max-w-7xl w-full mx-auto p-6 md:p-8 space-y-6">
            <!-- KPIs / Cartes de résumé -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-desktop"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Machines enregistrées</span>
                        <h2 class="text-2xl font-bold text-slate-900"><?= $totalDevices ?></h2>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Agents en ligne</span>
                        <h2 class="text-2xl font-bold text-slate-900"><?= $activeDevices ?></h2>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>