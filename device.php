<?php
require_once 'includes/api_client.php';

$deviceId = $_GET['id'] ?? '';
$device = getDeviceById($deviceId);

if (!$device) {
    die("Erreur : Machine introuvable ou ID invalide. <a href='inventory.php'>Retour à l'inventaire</a>");
}

// Récupération des dernières métriques de la machine via les rapports de l'API
$report = getLatestReportByDeviceId($deviceId);
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail - <?= htmlspecialchars($device['hostname'] ?? 'Inconnu') ?></title>
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
                <span class="font-bold tracking-wide text-lg text-slate-900">NetOps</span>
            </div>
            <nav class="p-4 space-y-1">
                <a href="index.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 transition">
                    <i class="fa-solid fa-chart-pie w-5 text-slate-400"></i> Tableau de bord
                </a>
                <a href="inventory.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="fa-solid fa-desktop w-5 text-indigo-500"></i> Inventaire
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-slate-100 text-xs text-slate-500 bg-slate-50/50">
            <span>Agent v<?= htmlspecialchars($report['agent_version'] ?? '1.0.0') ?></span>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col min-h-screen overflow-y-auto">
        <header class="h-16 bg-white/80 backdrop-blur border-b border-slate-200 px-6 flex justify-between items-center sticky top-0 z-10 shadow-xs">
            <div class="flex items-center gap-3">
                <a href="inventory.php" class="text-slate-400 hover:text-slate-700 text-sm"><i class="fa-solid fa-arrow-left"></i> Retour</a>
                <span class="text-slate-300">/</span>
                <h1 class="text-sm font-semibold text-slate-800"><?= htmlspecialchars($device['hostname'] ?? '') ?></h1>
            </div>  
        </header>

        <main class="max-w-7xl w-full mx-auto p-6 md:p-8 space-y-6">
            <!-- Fiche d'identité Machine -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex items-start gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($device['hostname'] ?? '') ?></h2>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Actif
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 font-mono"><?= htmlspecialchars($device['platform'] ?? 'N/A') ?> — Arch: <?= htmlspecialchars($device['arch'] ?? 'N/A') ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 gap-6 w-full lg:w-auto border-t lg:border-t-0 lg:border-l border-slate-100 pt-4 lg:pt-0 lg:pl-6">
                    <div>
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Adresse IP</span>
                        <span class="text-sm font-mono font-medium text-slate-700"><?= htmlspecialchars($report['ip_address'] ?? 'N/A') ?></span>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Uptime</span>
                        <span class="text-sm font-medium text-slate-700"><?= formatUptime($report['uptime_second'] ?? 0) ?></span>
                    </div>
                </div>
            </div>

            <!-- Grille des Ressources (CPU & RAM) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- CPU Card -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600"><i class="fa-solid fa-microchip"></i></div>
                            <h3 class="text-sm font-semibold text-slate-800">Processeur (CPU)</h3>
                        </div>
                        <span class="text-xs font-mono bg-slate-100 px-2.5 py-1 rounded-md text-slate-600 border border-slate-200"><?= $report['cpu_cores'] ?? 1 ?> Cœurs</span>
                    </div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-2xl font-bold text-slate-900"><?= $report['cpu_usage_percent'] ?? 0 ?>%</span>
                        <span class="text-xs text-slate-500">Charge instantanée</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-indigo-600 h-full rounded-full transition-all duration-500" style="width: <?= $report['cpu_usage_percent'] ?? 0 ?>%"></div>
                    </div>
                </div>

                <!-- RAM Card -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-cyan-600"><i class="fa-solid fa-memory"></i></div>
                            <h3 class="text-sm font-semibold text-slate-800">Mémoire Vive (RAM)</h3>
                        </div>
                        <span class="text-xs font-mono bg-slate-100 px-2.5 py-1 rounded-md text-slate-600 border border-slate-200"><?= formatBytes($report['mem_total_bytes'] ?? 0) ?></span>
                    </div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-2xl font-bold text-slate-900"><?= $report['mem_usage_percent'] ?? 0 ?>%</span>
                        <span class="text-xs text-slate-500"><?= formatBytes($report['mem_used_bytes'] ?? 0) ?> utilisés</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-cyan-500 h-full rounded-full transition-all duration-500" style="width: <?= $report['mem_usage_percent'] ?? 0 ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Tableau Stockage / Disques (Si géré dans les rapports) -->
            <?php if (!empty($report['disk']) && is_array($report['disk'])): ?>
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600"><i class="fa-solid fa-hard-drive"></i></div>
                        <h3 class="text-sm font-semibold text-slate-800">Disques & Stockage</h3>
                    </div>
                    <span class="text-xs text-slate-500 font-medium"><?= count($report['disk']) ?> volumes détectés</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider bg-slate-50">
                                <th class="py-3 px-6">Point de montage</th>
                                <th class="py-3 px-6">Utilisation</th>
                                <th class="py-3 px-6">Espace utilisé</th>
                                <th class="py-3 px-6">Capacité totale</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php foreach ($report['disk'] as $disk): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6 font-mono font-medium text-slate-700 flex items-center gap-2.5">
                                    <i class="fa-solid fa-folder-open text-slate-400 text-xs"></i>
                                    <?= htmlspecialchars($disk['mount_point']) ?>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-28 bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div class="bg-amber-500 h-full rounded-full" style="width: <?= $disk['usage_percent'] ?>%"></div>
                                        </div>
                                        <span class="text-xs font-mono font-semibold text-slate-700"><?= $disk['usage_percent'] ?>%</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-slate-600 font-mono text-xs"><?= formatBytes($disk['used_bytes']) ?></td>
                                <td class="py-4 px-6 text-slate-500 font-mono text-xs"><?= formatBytes($disk['total_bytes']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>