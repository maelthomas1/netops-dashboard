<?php

define('API_BASE_URL', 'http://10.29.160.96/api');

function callApi(string $endpoint) {
    $ch = curl_init(API_BASE_URL . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || $response === false) {
        return null;
    }

    return json_decode($response, true);
}

// Récupère toutes les machines
function getDevicesData(): array {
    $devices = callApi('/devices');
    return is_array($devices) ? $devices : [];
}

function getOnlineDevicesData(): array {
    $devices = callApi('/devices/online');
    return is_array($devices) ? $devices : [];
}

// Récupère une machine par son ID
function getDeviceById(string $deviceId): ?array {
    return callApi('/devices/' . urlencode($deviceId));
}

// Récupère le dernier rapport de métriques pour une machine donnée
function getLatestReportByDeviceId(string $deviceId): ?array {
    $reports = callApi('/reports');
    if (!is_array($reports)) {
        return null;
    }
    
    $latest = null;
    foreach ($reports as $report) {
        if (isset($report['device_id']) && $report['device_id'] === $deviceId) {
            if (!$latest || strtotime($report['send_at']) > strtotime($latest['send_at'])) {
                $latest = $report;
            }
        }
    }
    return $latest;
}

// Fonctions de formatage
function formatBytes(int $bytes, int $precision = 2): string {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function formatUptime(int $seconds): string {
    $days = floor($seconds / 86400);
    $hours = floor(($seconds % 86400) / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $secs = $seconds % 60;

    $parts = [];
    if ($days > 0) {
        $parts[] = "{$days}j";
    }
    if ($hours > 0 || $days > 0) {
        $parts[] = "{$hours}h";
    }
    if ($minutes > 0 || $hours > 0 || $days > 0) {
        $parts[] = "{$minutes}m";
    }
    $parts[] = "{$secs}s";

    return implode(' ', $parts);
}