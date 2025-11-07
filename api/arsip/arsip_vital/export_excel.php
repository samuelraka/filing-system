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
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

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
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

// Column widths
$sheet->getColumnDimension('A')->setWidth(6);  // NO
$sheet->getColumnDimension('B')->setWidth(20); // JENIS/SERIES
$sheet->getColumnDimension('C')->setWidth(20); // TINGKAT PERKEMBANGAN
$sheet->getColumnDimension('D')->setWidth(14); // KURUN TAHUN
$sheet->getColumnDimension('E')->setWidth(14); // MEDIA
$sheet->getColumnDimension('F')->setWidth(10); // JUMLAH
$sheet->getColumnDimension('G')->setWidth(18); // JANGKA SIMPAN
$sheet->getColumnDimension('H')->setWidth(18); // LOKASI SIMPAN
$sheet->getColumnDimension('I')->setWidth(21); // METODE PERLINDUNGAN
$sheet->getColumnDimension('J')->setWidth(14); // KET

// Logo box (black)
$sheet->mergeCells('A1:C3');
$sheet->setCellValue('A1', '');

// Insert actual logo image (tries absolute and project-relative paths)
$logoCandidates = [
    __DIR__ . '/../../../assets/images/Logo KemenkesPKY.jpg',
];
$logoPath = null;
foreach ($logoCandidates as $cand) {
    if (file_exists($cand)) { $logoPath = $cand; break; }
}
// Optional override via query: ?logo_path=...
if (isset($_GET['logo_path']) && is_string($_GET['logo_path']) && file_exists($_GET['logo_path'])) {
    $logoPath = $_GET['logo_path'];
}
if ($logoPath) {
    try {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo Kemenkes PKY');
        $drawing->setPath($logoPath);
        $drawing->setHeight(70); // adjust as needed
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(8);
        $drawing->setOffsetY(6);
        $drawing->setWorksheet($sheet);
        // Give some vertical room for the image across merged rows
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(25);
        $sheet->getRowDimension(3)->setRowHeight(25);
    } catch (\Throwable $e) {
        // If image insertion fails, keep placeholder border only
        // No fatal error; export should proceed
    }
}

// Title
$sheet->mergeCells('D2:J2');
$sheet->setCellValue('D2', 'DAFTAR ARSIP VITAL');
$sheet->getStyle('D2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

// Meta info rows
$sheet->mergeCells('A4:B4');
$sheet->setCellValue('A4', 'Unit Kerja');
$sheet->setCellValue('C4', ':');
$sheet->mergeCells('A5:B5');
$sheet->setCellValue('A5', 'Unit Organisasi');
$sheet->setCellValue('C5', ':');
$sheet->mergeCells('A6:B6');
$sheet->setCellValue('A6', 'Nama Pengelola Central File');
$sheet->setCellValue('C6', ':');

// Table header
$headerRow = 8;
$headers = ['NO','JENIS/ SERIES ARSIP','TINGKAT PERKEMBANGAN','KURUN TAHUN','MEDIA','JUMLAH','JANGKA SIMPAN','LOKASI SIMPAN','METODE PERLINDUNGAN','KET'];
foreach ($headers as $i => $h) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$headerRow}", $h);
}
$sheet->getStyle("A{$headerRow}:J{$headerRow}")->getFont()->setBold(true)->setSize(10);
$sheet->getStyle("A{$headerRow}:J{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle("A{$headerRow}:J{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DAEEF3');
$sheet->getStyle("A{$headerRow}:J{$headerRow}")->getAlignment()->setWrapText(true);
$sheet->getRowDimension($headerRow)->setRowHeight(40);
$sheet->getStyle("A{$headerRow}:J{$headerRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);


// Column numbers row
$numRow = $headerRow + 1;
$nums = ['1','2','3','4','5','6','7','8','9','10'];
foreach ($nums as $i => $n) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$numRow}", $n);
}
$sheet->getStyle("A{$numRow}:J{$numRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle("A{$numRow}:J{$numRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle("A{$numRow}:J{$numRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('205867');
$sheet->getStyle("A{$numRow}:J{$numRow}")->getFont()->setSize(10)->getColor()->setARGB('FFFFFF');
$sheet->getRowDimension($numRow)->setRowHeight(18);

// Data
$startRow = $numRow + 1;
$r = $startRow;
$no = 1;
foreach ($rows as $row) {
    $sheet->setCellValue("A{$r}", $no++);
    $sheet->getRowDimension($r)->setRowHeight(30);
    $sheet->getStyle("A{$r}")->getFont()->setSize(10);
    $sheet->getStyle("A{$r}:J{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
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
$sheet->mergeCells("A{$footerTop}:B{$footerTop}");
$sheet->setCellValue("A" . ($footerTop + 1), 'Pelaksana');
$sheet->setCellValue("A" . ($footerTop + 5), 'Nama Petugas');
$sheet->setCellValue("A" . ($footerTop + 6), 'NIP. ');
$sheet->getStyle("A" . ($footerTop + 6))->getFont()->setBold(true);

$sheet->mergeCells("I{$footerTop}:J{$footerTop}");
$sheet->setCellValue("I{$footerTop}", 'Kota, Tanggal/Bulan/Tahun');
$sheet->setCellValue("I" . ($footerTop + 1), 'Jabatan');
$sheet->setCellValue("I" . ($footerTop + 5), 'Nama');
$sheet->setCellValue("I" . ($footerTop + 6), 'NIP.');
$sheet->getStyle("I" . ($footerTop + 6))->getFont()->setBold(true);

// Output to browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Daftar Arsip Vital.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>