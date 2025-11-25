<?php
include_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if (isset($_GET['activity'])) {
    try {
        $cards = [
            'aktif' => (int)$conn->query("SELECT COUNT(*) AS c FROM arsip_aktif")->fetch_assoc()['c'],
            'inaktif' => (int)$conn->query("SELECT COUNT(*) AS c FROM arsip_inaktif")->fetch_assoc()['c'],
            'statis' => (int)$conn->query("SELECT COUNT(*) AS c FROM arsip_statis")->fetch_assoc()['c'],
            'vital' => (int)$conn->query("SELECT COUNT(*) AS c FROM arsip_vital")->fetch_assoc()['c'],
        ];

        $vitalHasCreated = false;
        $vitalColCheck = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'created_at'");
        if ($vitalColCheck && $vitalColCheck->num_rows > 0) { $vitalHasCreated = true; }

        $labelsWeekly = [];
        for ($i = 6; $i >= 0; $i--) { $labelsWeekly[] = date('d-m-Y', strtotime("-$i days")); }
        $weeklyAktif = array_fill(0, count($labelsWeekly), 0);
        $weeklyInaktif = array_fill(0, count($labelsWeekly), 0);
        $weeklyStatis = array_fill(0, count($labelsWeekly), 0);
        $weeklyVital = array_fill(0, count($labelsWeekly), 0);
        $startWeekly = date('Y-m-d', strtotime('-6 days'));
        $endWeekly = date('Y-m-d');
        $startWeeklyLabel = date('d-m-Y', strtotime('-6 days'));
        $endWeeklyLabel = date('d-m-Y');
        $res = $conn->query("SELECT DATE(created_at) d, COUNT(*) c FROM arsip_aktif WHERE DATE(created_at) BETWEEN '$startWeekly' AND '$endWeekly' GROUP BY d");
        if ($res) { while ($r = $res->fetch_assoc()) { $idx = array_search($r['d'], $labelsWeekly); if ($idx !== false) { $weeklyAktif[$idx] = (int)$r['c']; } } }
        $res = $conn->query("SELECT DATE(created_at) d, COUNT(*) c FROM arsip_inaktif WHERE DATE(created_at) BETWEEN '$startWeekly' AND '$endWeekly' GROUP BY d");
        if ($res) { while ($r = $res->fetch_assoc()) { $idx = array_search($r['d'], $labelsWeekly); if ($idx !== false) { $weeklyInaktif[$idx] = (int)$r['c']; } } }
        $res = $conn->query("SELECT DATE(created_at) d, COUNT(*) c FROM arsip_statis WHERE DATE(created_at) BETWEEN '$startWeekly' AND '$endWeekly' GROUP BY d");
        if ($res) { while ($r = $res->fetch_assoc()) { $idx = array_search($r['d'], $labelsWeekly); if ($idx !== false) { $weeklyStatis[$idx] = (int)$r['c']; } } }
        if ($vitalHasCreated) {
            $res = $conn->query("SELECT DATE(created_at) d, COUNT(*) c FROM arsip_vital WHERE DATE(created_at) BETWEEN '$startWeekly' AND '$endWeekly' GROUP BY d");
            if ($res) { while ($r = $res->fetch_assoc()) { $idx = array_search($r['d'], $labelsWeekly); if ($idx !== false) { $weeklyVital[$idx] = (int)$r['c']; } } }
        }

        $y = (int)date('Y');
        $labelsMonthly = [];
        for ($mm = 1; $mm <= 12; $mm++) { $labelsMonthly[] = sprintf('%04d-%02d', $y, $mm); }
        $monthlyAktif = array_fill(0, 12, 0);
        $monthlyInaktif = array_fill(0, 12, 0);
        $monthlyStatis = array_fill(0, 12, 0);
        $monthlyVital = array_fill(0, 12, 0);
        $res = $conn->query("SELECT MONTH(created_at) mm, COUNT(*) c FROM arsip_aktif WHERE YEAR(created_at)=$y GROUP BY MONTH(created_at)");
        if ($res) { while ($r = $res->fetch_assoc()) { $idx = ((int)$r['mm']) - 1; if ($idx >= 0 && $idx < 12) { $monthlyAktif[$idx] = (int)$r['c']; } } }
        $res = $conn->query("SELECT MONTH(created_at) mm, COUNT(*) c FROM arsip_inaktif WHERE YEAR(created_at)=$y GROUP BY MONTH(created_at)");
        if ($res) { while ($r = $res->fetch_assoc()) { $idx = ((int)$r['mm']) - 1; if ($idx >= 0 && $idx < 12) { $monthlyInaktif[$idx] = (int)$r['c']; } } }
        $res = $conn->query("SELECT MONTH(created_at) mm, COUNT(*) c FROM arsip_statis WHERE YEAR(created_at)=$y GROUP BY MONTH(created_at)");
        if ($res) { while ($r = $res->fetch_assoc()) { $idx = ((int)$r['mm']) - 1; if ($idx >= 0 && $idx < 12) { $monthlyStatis[$idx] = (int)$r['c']; } } }
        if ($vitalHasCreated) {
            $res = $conn->query("SELECT MONTH(created_at) mm, COUNT(*) c FROM arsip_vital WHERE YEAR(created_at)=$y GROUP BY MONTH(created_at)");
            if ($res) { while ($r = $res->fetch_assoc()) { $idx = ((int)$r['mm']) - 1; if ($idx >= 0 && $idx < 12) { $monthlyVital[$idx] = (int)$r['c']; } } }
        }

        $labelsYearly = [];
        $startYear = $y - 4;
        for ($yy = $startYear; $yy <= $y; $yy++) { $labelsYearly[] = sprintf('%04d', $yy); }
        $yearlyAktif = array_fill(0, count($labelsYearly), 0);
        $yearlyInaktif = array_fill(0, count($labelsYearly), 0);
        $yearlyStatis = array_fill(0, count($labelsYearly), 0);
        $yearlyVital = array_fill(0, count($labelsYearly), 0);
        $res = $conn->query("SELECT YEAR(created_at) yy, COUNT(*) c FROM arsip_aktif WHERE YEAR(created_at) BETWEEN $startYear AND $y GROUP BY YEAR(created_at)");
        if ($res) { while ($r = $res->fetch_assoc()) { $idx = ((int)$r['yy']) - $startYear; if ($idx >= 0 && $idx < count($labelsYearly)) { $yearlyAktif[$idx] = (int)$r['c']; } } }
        $res = $conn->query("SELECT YEAR(created_at) yy, COUNT(*) c FROM arsip_inaktif WHERE YEAR(created_at) BETWEEN $startYear AND $y GROUP BY YEAR(created_at)");
        if ($res) { while ($r = $res->fetch_assoc()) { $idx = ((int)$r['yy']) - $startYear; if ($idx >= 0 && $idx < count($labelsYearly)) { $yearlyInaktif[$idx] = (int)$r['c']; } } }
        $res = $conn->query("SELECT YEAR(created_at) yy, COUNT(*) c FROM arsip_statis WHERE YEAR(created_at) BETWEEN $startYear AND $y GROUP BY YEAR(created_at)");
        if ($res) { while ($r = $res->fetch_assoc()) { $idx = ((int)$r['yy']) - $startYear; if ($idx >= 0 && $idx < count($labelsYearly)) { $yearlyStatis[$idx] = (int)$r['c']; } } }
        if ($vitalHasCreated) {
            $res = $conn->query("SELECT YEAR(created_at) yy, COUNT(*) c FROM arsip_vital WHERE YEAR(created_at) BETWEEN $startYear AND $y GROUP BY YEAR(created_at)");
            if ($res) { while ($r = $res->fetch_assoc()) { $idx = ((int)$r['yy']) - $startYear; if ($idx >= 0 && $idx < count($labelsYearly)) { $yearlyVital[$idx] = (int)$r['c']; } } }
        }

        $activityData = [
            'weekly' => ['labels' => $labelsWeekly, 'aktif' => $weeklyAktif, 'inaktif' => $weeklyInaktif, 'statis' => $weeklyStatis, 'vital' => $weeklyVital],
            'monthly' => ['labels' => $labelsMonthly, 'aktif' => $monthlyAktif, 'inaktif' => $monthlyInaktif, 'statis' => $monthlyStatis, 'vital' => $monthlyVital],
            'yearly' => ['labels' => $labelsYearly, 'aktif' => $yearlyAktif, 'inaktif' => $yearlyInaktif, 'statis' => $yearlyStatis, 'vital' => $yearlyVital],
        ];
        $statsData = [
            'weekly' => ['aktif' => array_sum($weeklyAktif), 'inaktif' => array_sum($weeklyInaktif), 'statis' => array_sum($weeklyStatis), 'vital' => array_sum($weeklyVital)],
            'monthly' => ['aktif' => array_sum($monthlyAktif), 'inaktif' => array_sum($monthlyInaktif), 'statis' => array_sum($monthlyStatis), 'vital' => array_sum($monthlyVital)],
            'yearly' => ['aktif' => array_sum($yearlyAktif), 'inaktif' => array_sum($yearlyInaktif), 'statis' => array_sum($yearlyStatis), 'vital' => array_sum($yearlyVital)],
        ];

        echo json_encode(['cards' => $cards, 'activityData' => $activityData, 'statsData' => $statsData, 'weeklyLabel' => 'Data from ' . $startWeeklyLabel . ' to ' . $endWeeklyLabel]);
        exit;
    } catch (Throwable $e) {
        echo json_encode(['cards' => ['aktif' => 0, 'inaktif' => 0, 'statis' => 0, 'vital' => 0], 'activityData' => ['weekly' => ['labels' => [], 'aktif' => [], 'inaktif' => [], 'statis' => [], 'vital' => []], 'monthly' => ['labels' => [], 'aktif' => [], 'inaktif' => [], 'statis' => [], 'vital' => []], 'yearly' => ['labels' => [], 'aktif' => [], 'inaktif' => [], 'statis' => [], 'vital' => []]], 'statsData' => ['weekly' => ['aktif' => 0, 'inaktif' => 0, 'statis' => 0, 'vital' => 0], 'monthly' => ['aktif' => 0, 'inaktif' => 0, 'statis' => 0, 'vital' => 0], 'yearly' => ['aktif' => 0, 'inaktif' => 0, 'statis' => 0, 'vital' => 0]], 'weeklyLabel' => 'Data from - to -']);
        exit;
    }
}

$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;

$startDate = $start ? date('Y-m-d', strtotime($start)) : null;
$endDate = $end ? date('Y-m-d', strtotime($end)) : null;
// labels for UI
$startLabel = $startDate ? date('d-m-Y', strtotime($startDate)) : '';
$endLabel = $endDate ? date('d-m-Y', strtotime($endDate)) : '';

if (!$startDate || !$endDate) {
    echo json_encode(['aktif' => 0, 'inaktif' => 0, 'statis' => 0, 'vital' => 0, 'label' => 'Invalid date']);
    exit;
}

$aktif = 0; $inaktif = 0; $statis = 0; $vital = 0;

try {
    $r = $conn->query("SELECT COUNT(*) c FROM arsip_aktif WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'");
    if ($r) { $aktif = (int)$r->fetch_assoc()['c']; }
    $r = $conn->query("SELECT COUNT(*) c FROM arsip_inaktif WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'");
    if ($r) { $inaktif = (int)$r->fetch_assoc()['c']; }
    $r = $conn->query("SELECT COUNT(*) c FROM arsip_statis WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'");
    if ($r) { $statis = (int)$r->fetch_assoc()['c']; }

    $vitalHasCreated = false;
    $vitalColCheck = $conn->query("SHOW COLUMNS FROM arsip_vital LIKE 'created_at'");
    if ($vitalColCheck && $vitalColCheck->num_rows > 0) { $vitalHasCreated = true; }
    if ($vitalHasCreated) {
        $r = $conn->query("SELECT COUNT(*) c FROM arsip_vital WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'");
        if ($r) { $vital = (int)$r->fetch_assoc()['c']; }
    }
} catch (Throwable $e) {}

$label = ($startDate === $endDate) ? ('Data from ' . $startLabel) : ('Data from ' . $startLabel . ' to ' . $endLabel);
echo json_encode(['aktif' => $aktif, 'inaktif' => $inaktif, 'statis' => $statis, 'vital' => $vital, 'label' => $label]);
?>