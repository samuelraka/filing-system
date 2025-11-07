<?php
// Export Daftar Arsip Vital to Excel with a custom layout using PHPSpreadsheet
// Reads current filters from query string to match the Vital page view

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

// Read filters from query
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_media = isset($_GET['media']) ? trim($_GET['media']) : '';
$filter_lokasi = isset($_GET['lokasi']) ? trim($_GET['lokasi']) : '';
$filter_metode = isset($_GET['metode']) ? trim($_GET['metode']) : '';
$filter_kode = isset($_GET['kode']) ? trim($_GET['kode']) : '';

// Build WHERE clause (mirrors pages/vital.php)
$where = "WHERE 1=1";

if ($keyword !== '') {
    $keyword_safe = mysqli_real_escape_string($conn, $keyword);
    $where .= " AND (v.kode_klasifikasi LIKE '%$keyword_safe%' 
                OR v.jenis_arsip LIKE '%$keyword_safe%' 
                OR v.tahun LIKE '%$keyword_safe%')";
}
if ($filter_media !== '') {
    $media_safe = mysqli_real_escape_string($conn, $filter_media);
    $where .= " AND v.media = '$media_safe'";
}
if ($filter_lokasi !== '') {
    $lokasi_safe = mysqli_real_escape_string($conn, $filter_lokasi);
    $where .= " AND v.lokasi_simpan = '$lokasi_safe'";
}
if ($filter_metode !== '') {
    $metode_safe = mysqli_real_escape_string($conn, $filter_metode);
    $where .= " AND v.metode_perlindungan = '$metode_safe'";
}
if ($filter_kode !== '') {
    $kode_safe = mysqli_real_escape_string($conn, $filter_kode);
    $where .= " AND v.kode_klasifikasi LIKE '%$kode_safe%'";
}

// Fetch data (no pagination)
$query = "SELECT v.* FROM arsip_vital v $where ORDER BY v.created_at DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    // Fallback: rebuild WHERE without kode filter if schema mismatch
    $fallbackWhere = "WHERE 1=1";
    if ($keyword !== '') {
        $keyword_safe = mysqli_real_escape_string($conn, $keyword);
        $fallbackWhere .= " AND (v.kode_klasifikasi LIKE '%$keyword_safe%' 
                        OR v.jenis_arsip LIKE '%$keyword_safe%' 
                        OR v.tahun LIKE '%$keyword_safe%')";
    }
    if ($filter_media !== '') {
        $media_safe = mysqli_real_escape_string($conn, $filter_media);
        $fallbackWhere .= " AND v.media = '$media_safe'";
    }
    if ($filter_lokasi !== '') {
        $lokasi_safe = mysqli_real_escape_string($conn, $filter_lokasi);
        $fallbackWhere .= " AND v.lokasi_simpan = '$lokasi_safe'";
    }
    if ($filter_metode !== '') {
        $metode_safe = mysqli_real_escape_string($conn, $filter_metode);
        $fallbackWhere .= " AND v.metode_perlindungan = '$metode_safe'";
    }
    $result = mysqli_query($conn, "SELECT v.* FROM arsip_vital v $fallbackWhere ORDER BY v.created_at DESC");
}

$rows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

// Build spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Daftar Arsip Vital');

// Column widths
$sheet->getColumnDimension('A')->setWidth(6);  // NO
$sheet->getColumnDimension('B')->setWidth(28); // JENIS/SERIES
$sheet->getColumnDimension('C')->setWidth(20); // TINGKAT PERKEMBANGAN
$sheet->getColumnDimension('D')->setWidth(14); // KURUN TAHUN
$sheet->getColumnDimension('E')->setWidth(14); // MEDIA
$sheet->getColumnDimension('F')->setWidth(10); // JUMLAH
$sheet->getColumnDimension('G')->setWidth(18); // JANGKA SIMPAN
$sheet->getColumnDimension('H')->setWidth(18); // LOKASI SIMPAN
$sheet->getColumnDimension('I')->setWidth(22); // METODE PERLINDUNGAN
$sheet->getColumnDimension('J')->setWidth(14); // KET

// Logo box (black)
$sheet->mergeCells('A1:C3');
$sheet->setCellValue('A1', 'LOGO KEMENKES');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:C3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
$sheet->getStyle('A1:C3')->getFont()->getColor()->setARGB('FFFFFF');
$sheet->getStyle('A1:C3')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

// Title
$sheet->mergeCells('D2:J2');
$sheet->setCellValue('D2', 'DAFTAR ARSIP VITAL');
$sheet->getStyle('D2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Meta info rows
$sheet->setCellValue('A4', 'Unit Kerja');
$sheet->setCellValue('B4', ':');
$sheet->mergeCells('C4:J4');
$sheet->setCellValue('A5', 'Unit Organisasi');
$sheet->setCellValue('B5', ':');
$sheet->mergeCells('C5:J5');
$sheet->setCellValue('A6', 'Nama Pengelola Central File');
$sheet->setCellValue('B6', ':');
$sheet->mergeCells('C6:J6');

// Table header
$headerRow = 8;
$headers = ['NO','JENIS/ SERIES ARSIP','TINGKAT PERKEMBANGAN','KURUN TAHUN','MEDIA','JUMLAH','JANGKA SIMPAN','LOKASI SIMPAN','METODE PERLINDUNGAN','KET'];
foreach ($headers as $i => $h) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$headerRow}", $h);
}
$sheet->getStyle("A{$headerRow}:J{$headerRow}")->getFont()->setBold(true);
$sheet->getStyle("A{$headerRow}:J{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getRowDimension($headerRow)->setRowHeight(22);
$sheet->getStyle("A{$headerRow}:J{$headerRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Column numbers row
$numRow = $headerRow + 1;
$nums = ['(1)','(2)','(3)','(4)','(5)','(6)','(7)','(8)','(9)','(10)'];
foreach ($nums as $i => $n) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$numRow}", $n);
}
$sheet->getStyle("A{$numRow}:J{$numRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A{$numRow}:J{$numRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Data
$startRow = $numRow + 1;
$r = $startRow;
$no = 1;
foreach ($rows as $row) {
    $sheet->setCellValue("A{$r}", $no++);
    $sheet->setCellValue("B{$r}", $row['jenis_arsip'] ?? ($row['uraian_arsip'] ?? ''));
    $sheet->setCellValue("C{$r}", $row['tingkat_perkembangan'] ?? '');
    $sheet->setCellValue("D{$r}", $row['kurun_tahun'] ?? ($row['kurun_waktu'] ?? ''));
    $sheet->setCellValue("E{$r}", $row['media'] ?? '');
    $sheet->setCellValue("F{$r}", $row['jumlah'] ?? '');
    $sheet->setCellValue("G{$r}", $row['jangka_simpan'] ?? '');
    $sheet->setCellValue("H{$r}", $row['lokasi_simpan'] ?? '');
    $sheet->setCellValue("I{$r}", $row['metode_perlindungan'] ?? '');
    $sheet->setCellValue("J{$r}", $row['keterangan'] ?? '');
    $r++;
}

// Table borders
if ($r > $startRow) {
    $sheet->getStyle("A{$startRow}:J" . ($r - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
}

// Footer (signatures)
$footerTop = $r + 2;
$sheet->setCellValue("A{$footerTop}", 'Pelaksana');
$sheet->setCellValue("A" . ($footerTop + 1), 'Ttd');
$sheet->setCellValue("A" . ($footerTop + 3), 'Nama Petugas');

$sheet->setCellValue("G{$footerTop}", 'Kota, Tanggal/Bulan/Tahun');
$sheet->setCellValue("G" . ($footerTop + 1), 'Jabatan');
$sheet->setCellValue("G" . ($footerTop + 2), 'Ttd');
$sheet->setCellValue("G" . ($footerTop + 3), 'Nama');

// Outer border
$sheet->getStyle("A1:J" . ($footerTop + 4))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);

// Output to browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Daftar_Arsip_Vital.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>