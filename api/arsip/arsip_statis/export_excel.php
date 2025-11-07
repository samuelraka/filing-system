<?php
// Export Daftar Arsip Statis to Excel with a custom layout using PHPSpreadsheet
// Mirrors the Vital export style and respects current filters from the Statis page

// Load DB connection
include_once '../../../config/database.php';

// Ensure PHPSpreadsheet is installed
$autoload = __DIR__ . '/../../../vendor/autoload.php';
if (!file_exists($autoload)) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "PHPSpreadsheet is not installed. Run: composer require phpoffice/phpspreadsheet";
    exit;
}
require $autoload;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Read filters from query (mirrors pages/statis.php)
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_jenis = isset($_GET['jenis']) ? trim($_GET['jenis']) : '';
$filter_tahun = isset($_GET['tahun']) ? trim($_GET['tahun']) : '';

// Build WHERE clause
$where = "WHERE 1=1";
if ($keyword !== '') {
    $keyword_safe = mysqli_real_escape_string($conn, $keyword);
    $where .= " AND (s.kode_subsub LIKE '%$keyword_safe%'
                OR a.jenis_arsip LIKE '%$keyword_safe%'
                OR a.tahun LIKE '%$keyword_safe%')";
}
if ($filter_jenis !== '') {
    $jenis_safe = mysqli_real_escape_string($conn, $filter_jenis);
    $where .= " AND a.jenis_arsip = '$jenis_safe'";
}
if ($filter_tahun !== '') {
    $tahun_safe = mysqli_real_escape_string($conn, $filter_tahun);
    $where .= " AND a.tahun = '$tahun_safe'";
}

// Fetch all data (no pagination)
$query = "
    SELECT a.*, s.kode_subsub, s.topik_subsub
    FROM arsip_statis a
    JOIN sub_sub_masalah s ON a.id_subsub = s.id_subsub
    $where
    ORDER BY a.created_at DESC
";
$result = mysqli_query($conn, $query);
$rows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

// Build spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Daftar Arsip Statis');
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

// Column widths (A-G)
$sheet->getColumnDimension('A')->setWidth(6);   // NO
$sheet->getColumnDimension('B')->setWidth(18);  // KODE KLASIFIKASI
$sheet->getColumnDimension('C')->setWidth(22);  // JENIS/SERIES
$sheet->getColumnDimension('D')->setWidth(12);  // TAHUN
$sheet->getColumnDimension('E')->setWidth(10);  // JUMLAH
$sheet->getColumnDimension('F')->setWidth(20);  // TINGKAT PERKEMBANGAN
$sheet->getColumnDimension('G')->setWidth(22);  // KETERANGAN

// Logo area
$sheet->mergeCells('A1:C3');
$sheet->setCellValue('A1', '');
$sheet->getStyle('A1:C3')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

// Insert logo image (same logic as Vital)
$logoCandidates = [
    __DIR__ . '/../../../assets/images/Logo KemenkesPKY.jpg',
];
$logoPath = null;
foreach ($logoCandidates as $cand) {
    if (file_exists($cand)) { $logoPath = $cand; break; }
}
if (isset($_GET['logo_path']) && is_string($_GET['logo_path']) && file_exists($_GET['logo_path'])) {
    $logoPath = $_GET['logo_path'];
}
if ($logoPath) {
    try {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo Kemenkes PKY');
        $drawing->setPath($logoPath);
        $drawing->setHeight(70);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(8);
        $drawing->setOffsetY(6);
        $drawing->setWorksheet($sheet);
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(25);
        $sheet->getRowDimension(3)->setRowHeight(25);
    } catch (\Throwable $e) {
        // Skip logo on failure
    }
}

// Title
$sheet->mergeCells('D2:G2');
$sheet->setCellValue('D2', 'DAFTAR ARSIP STATIS');
$sheet->getStyle('D2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Meta info rows
$sheet->setCellValue('A4', 'Unit Kerja');
$sheet->setCellValue('C4', ':');
$sheet->mergeCells('A4:B4');
$sheet->setCellValue('A5', 'Unit Organisasi');
$sheet->setCellValue('C5', ':');
$sheet->mergeCells('A5:B5');
$sheet->setCellValue('A6', 'Nama Pengelola Central File');
$sheet->setCellValue('C6', ':');
$sheet->mergeCells('A6:B6');

// Table header
$headerRow = 8;
$headers = [
    'NO',
    'KODE KLASIFIKASI ARSIP',
    'JENIS/ SERIES ARSIP',
    'TAHUN',
    'JUMLAH',
    'TINGKAT PERKEMBANGAN',
    'KET'
];
foreach ($headers as $i => $h) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$headerRow}", $h);
}
$sheet->getStyle("A{$headerRow}:G{$headerRow}")->getFont()->setBold(true)->setSize(10);
$sheet->getStyle("A{$headerRow}:G{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle("A{$headerRow}:G{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DAEEF3');
$sheet->getStyle("A{$headerRow}:G{$headerRow}")->getAlignment()->setWrapText(true);
$sheet->getRowDimension($headerRow)->setRowHeight(40);
$sheet->getStyle("A{$headerRow}:G{$headerRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Column numbers row
$numRow = $headerRow + 1;
$nums = ['1','2','3','4','5','6','7'];
foreach ($nums as $i => $n) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$numRow}", $n);
}
$sheet->getStyle("A{$numRow}:G{$numRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle("A{$numRow}:G{$numRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle("A{$numRow}:G{$numRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('205867');
$sheet->getStyle("A{$numRow}:G{$numRow}")->getFont()->setSize(10)->getColor()->setARGB('FFFFFF');
$sheet->getRowDimension($numRow)->setRowHeight(18);

// Data rows
$startRow = $numRow + 1;
$r = $startRow;
$no = 1;
foreach ($rows as $row) {
    $sheet->setCellValue("A{$r}", $no++);
    $sheet->getRowDimension($r)->setRowHeight(30);
    $sheet->getStyle("A{$r}")->getFont()->setSize(10);
    $sheet->getStyle("A{$r}:F{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A{$r}:G{$r}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue("B{$r}", $row['kode_subsub'] ?? '');
    $sheet->setCellValue("C{$r}", $row['jenis_arsip'] ?? '');
    $sheet->setCellValue("D{$r}", $row['tahun'] ?? '');
    $sheet->setCellValue("E{$r}", $row['jumlah'] ?? '');
    $sheet->setCellValue("F{$r}", $row['tingkat_perkembangan'] ?? '');
    $sheet->setCellValue("G{$r}", $row['keterangan'] ?? '');
    $r++;
}

// Table borders
if ($r > $startRow) {
    $sheet->getStyle("A{$startRow}:G" . ($r - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
}

// Footer (signatures)
$footerTop = $r + 2;
$sheet->setCellValue("A" . ($footerTop + 1), 'Pelaksana');
$sheet->setCellValue("A" . ($footerTop + 5), 'Nama Petugas');
$sheet->setCellValue("A" . ($footerTop + 6), 'NIP.');
$sheet->getStyle("A" . ($footerTop + 6))->getFont()->setBold(true);

$sheet->mergeCells("F{$footerTop}:G{$footerTop}");
$sheet->setCellValue("F{$footerTop}", 'Kota, Tanggal/Bulan/Tahun');
$sheet->setCellValue("F" . ($footerTop + 1), 'Jabatan');
$sheet->setCellValue("F" . ($footerTop + 5), 'Nama');
$sheet->setCellValue("F" . ($footerTop + 6), 'NIP.');
$sheet->getStyle("F" . ($footerTop + 6))->getFont()->setBold(true);

// Output to browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Daftar Arsip Statis.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>