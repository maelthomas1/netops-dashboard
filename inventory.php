<?php
require_once 'includes/api_client.php';
$devices = getDevicesData();
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaire du Parc</title>
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
                <a href="index.php"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 transition">
                    <i class="fa-solid fa-chart-pie w-5 text-slate-400"></i> Tableau de bord
                </a>
                <a href="inventory.php"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="fa-solid fa-desktop w-5 text-indigo-500"></i> Inventaire
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-slate-100 text-xs text-slate-500 bg-slate-50/50">
            <span>OCS-Like v0.1</span>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col min-h-screen overflow-y-auto">
        <header
            class="h-16 bg-white/80 backdrop-blur border-b border-slate-200 px-6 flex justify-between items-center sticky top-0 z-10 shadow-xs">
            <h1 class="text-sm font-semibold text-slate-800">Inventaire des équipements</h1>
        </header>

        <main class="max-w-7xl w-full mx-auto p-6 md:p-8 space-y-6">
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="p-6 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800">Liste des machines connectées</h3>
                    <span class="text-xs text-slate-500 font-medium"><?= count($devices) ?> enregistrées</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider bg-slate-50">
                                <th class="py-3 px-6">Hostname</th>
                                <th class="py-3 px-6">OS / Plateforme</th>
                                <th class="py-3 px-6">Adresse IP</th>
                                <th class="py-3 px-6">Uptime</th>
                                <th class="py-3 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php foreach ($devices as $dev): ?>
                                <?php
                                // Récupération optionnelle du rapport pour afficher l'IP si besoin
                                $report = getLatestReportByDeviceId($dev['id']);
                                ?>
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-4 px-6 font-semibold text-slate-900 flex items-center gap-2.5">
                                        <i class="fa-solid fa-server text-indigo-500 text-xs"></i>
                                        <?= htmlspecialchars($dev['hostname']) ?>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 text-xs font-mono">
                                        <?= htmlspecialchars($dev['platform']) ?></td>
                                    <td class="py-4 px-6 text-slate-600 font-mono text-xs">
                                        <?= htmlspecialchars($report['ip_address'] ?? 'N/A') ?></td>
                                    <td class="py-4 px-6 text-slate-600 text-xs">
                                        <?= formatUptime($report['uptime_second'] ?? 0) ?></td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="device.php?id=<?= urlencode($dev['id']) ?>"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-100 transition">
                                            <i class="fa-solid fa-eye"></i> Consulter
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>