<?php
// Export Daftar Arsip Inaktif to Excel with PHPSpreadsheet
// Logic: no Pokok/Sub/Sub-Sub header rows; blank "Nomor Berkas" for
// subsequent rows within the same Sub-Sub Masalah (kode_subsub).

include_once '../../../config/database.php';

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

// Filters (mirror pages/inaktif.php)
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_kode = isset($_GET['kode']) ? trim($_GET['kode']) : '';
$filter_tk = isset($_GET['tk']) ? trim($_GET['tk']) : '';
$filterLok = isset($_GET['lok']) ? trim($_GET['lok']) : '';
$filterKat = isset($_GET['kat']) ? trim($_GET['kat']) : '';

$where = "WHERE 1=1";
if ($keyword !== '') {
    $k = mysqli_real_escape_string($conn, $keyword);
    $where .= " AND (\n        ai.nomor_berkas LIKE '%$k%' OR\n        ia.nomor_item LIKE '%$k%' OR\n        ia.kurun_waktu LIKE '%$k%' OR\n        ssm.kode_subsub LIKE '%$k%' OR\n        ia.tingkat_perkembangan LIKE '%$k%' OR\n        ia.lokasi_simpan LIKE '%$k%' OR\n        ia.kategori_arsip LIKE '%$k%' OR\n        ia.nomor_boks LIKE '%$k%'\n    )";
}
if ($filter_kode !== '') {
    $kode_safe = mysqli_real_escape_string($conn, $filter_kode);
    $where .= " AND ssm.kode_subsub = '$kode_safe'";
}
if ($filter_tk !== '') {
    $tk_safe = mysqli_real_escape_string($conn, $filter_tk);
    $where .= " AND ia.tingkat_perkembangan = '$tk_safe'";
}
if ($filterLok !== '') {
    $lok_safe = mysqli_real_escape_string($conn, $filterLok);
    $where .= " AND ia.lokasi_simpan = '$lok_safe'";
}
if ($filterKat !== '') {
    $kat_safe = mysqli_real_escape_string($conn, $filterKat);
    $where .= " AND ia.kategori_arsip = '$kat_safe'";
}

$sql = "\n    SELECT\n        ai.id_arsip, ai.nomor_berkas, ai.jumlah_item,\n        ia.id_item, ia.nomor_item, ia.uraian_informasi, ia.kurun_waktu, ia.tingkat_perkembangan, ia.keterangan, ia.nomor_boks, ia.lokasi_simpan, ia.jangka_simpan, ia.kategori_arsip,\n        ssm.kode_subsub AS kode_klasifikasi\n    FROM arsip_inaktif ai\n    LEFT JOIN item_arsip_inaktif ia ON ai.id_arsip = ia.id_arsip\n    LEFT JOIN sub_sub_masalah ssm ON ai.id_subsub = ssm.id_subsub\n    $where\n    ORDER BY ssm.kode_subsub ASC, ai.nomor_berkas ASC, ia.nomor_item ASC\n";
$res = mysqli_query($conn, $sql);

$rows = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) { $rows[] = $row; }
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Daftar Arsip Inaktif');
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

// Column widths (A-L)
$sheet->getColumnDimension('A')->setWidth(12); // Nomor Berkas
$sheet->getColumnDimension('B')->setWidth(14); // Nomor Item Arsip
$sheet->getColumnDimension('C')->setWidth(16); // Kode Klasifikasi Arsip
$sheet->getColumnDimension('D')->setWidth(48); // Uraian Informasi Arsip
$sheet->getColumnDimension('E')->setWidth(16); // Kurun Waktu
$sheet->getColumnDimension('F')->setWidth(18); // Tingkat Perkembangan
$sheet->getColumnDimension('G')->setWidth(16); // Jumlah Item Arsip
$sheet->getColumnDimension('H')->setWidth(18); // Keterangan
$sheet->getColumnDimension('I')->setWidth(22); // No Definitif Folder dan Boks
$sheet->getColumnDimension('J')->setWidth(18); // Lokasi Simpan
$sheet->getColumnDimension('K')->setWidth(22); // Jangka Simpan dan Nasib Akhir
$sheet->getColumnDimension('L')->setWidth(18); // Kategori Arsip

// Logo + Title (match style of other exports)
$sheet->mergeCells('A1:C3');
$sheet->setCellValue('A1', '');
$logoPath = __DIR__ . '/../../../assets/images/Logo KemenkesPKY.jpg';
if (file_exists($logoPath)) {
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
    } catch (\Throwable $e) {}
}

$sheet->mergeCells('D2:L2');
$sheet->setCellValue('D2', 'DAFTAR ARSIP INAKTIF');
$sheet->getStyle('D2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Meta info rows
$sheet->mergeCells('A4:B4'); $sheet->setCellValue('A4', 'Unit Kerja'); $sheet->setCellValue('C4', ':');
$sheet->mergeCells('A5:B5'); $sheet->setCellValue('A5', 'Unit Organisasi'); $sheet->setCellValue('C5', ':');
$sheet->mergeCells('A6:B6'); $sheet->setCellValue('A6', 'Nama Pengelola Central File'); $sheet->setCellValue('C6', ':');

// Table header
$headerRow = 8;
$headers = [
    'NOMOR BERKAS',
    'NO. ITEM ARSIP',
    'KODE KLASIFIKASI ARSIP',
    'URAIAN INFORMASI ARSIP',
    'KURUN WAKTU',
    'TINGKAT PERKEMBANGAN',
    'JUMLAH ITEM ARSIP',
    'KETERANGAN',
    'NO DEFINITIF FOLDER DAN BOKS',
    'LOKASI SIMPAN',
    'JANGKA SIMPAN DAN NASIB AKHIR',
    'KATEGORI ARSIP'
];
foreach ($headers as $i => $h) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$headerRow}", $h);
}
$sheet->getStyle("A{$headerRow}:L{$headerRow}")->getFont()->setBold(true)->setSize(10);
$sheet->getStyle("A{$headerRow}:L{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle("A{$headerRow}:L{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DAEEF3');
$sheet->getRowDimension($headerRow)->setRowHeight(40);
$sheet->getStyle("A{$headerRow}:L{$headerRow}")->getAlignment()->setWrapText(true);
$sheet->getStyle("A{$headerRow}:L{$headerRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Column numbers
$numRow = $headerRow + 1;
$nums = range(1, 12);
foreach ($nums as $i => $n) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$numRow}", (string)$n);
}
$sheet->getStyle("A{$numRow}:L{$numRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle("A{$numRow}:L{$numRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle("A{$numRow}:L{$numRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('205867');
$sheet->getStyle("A{$numRow}:L{$numRow}")->getFont()->setSize(10)->getColor()->setARGB('FFFFFF');
$sheet->getRowDimension($numRow)->setRowHeight(18);

// Data rows — blank Nomor Berkas for subsequent rows of the same kode_subsub
$r = $numRow + 1;
$lastKode = null;
foreach ($rows as $row) {
    $kode = $row['kode_klasifikasi'] ?? '';
    if ($lastKode === null || $lastKode !== $kode) {
        $sheet->setCellValue("A{$r}", $row['nomor_berkas'] ?? '');
        $lastKode = $kode;
    } else {
        $sheet->setCellValue("A{$r}", '');
    }

    $sheet->setCellValue("B{$r}", $row['nomor_item'] ?? '');
    $sheet->setCellValue("C{$r}", $kode);
    $sheet->setCellValue("D{$r}", $row['uraian_informasi'] ?? '');
    $sheet->setCellValue("E{$r}", $row['kurun_waktu'] ?? '');
    $sheet->setCellValue("F{$r}", $row['tingkat_perkembangan'] ?? '');
    $jumlahTxt = isset($row['jumlah_item']) ? ($row['jumlah_item'] . ' Lembar') : '';
    $sheet->setCellValue("G{$r}", $jumlahTxt);
    $sheet->setCellValue("H{$r}", $row['keterangan'] ?? '');
    $sheet->setCellValue("I{$r}", $row['nomor_boks'] ?? '');
    $sheet->setCellValue("J{$r}", $row['lokasi_simpan'] ?? '');
    $sheet->setCellValue("K{$r}", $row['jangka_simpan'] ?? '');
    $sheet->setCellValue("L{$r}", $row['kategori_arsip'] ?? '');

    $sheet->getStyle("A{$r}:L{$r}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
    $sheet->getStyle("A{$r}:C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("E{$r}:L{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getRowDimension($r)->setRowHeight(28);
    $r++;
}

// Borders for data area
if ($r > ($numRow + 1)) {
    $sheet->getStyle("A" . ($numRow + 1) . ":L" . ($r - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
}

// Footer placeholders
$footerTop = $r + 2;
$sheet->setCellValue("A" . ($footerTop + 1), 'Pelaksana');
$sheet->setCellValue("A" . ($footerTop + 5), 'Nama Petugas');
$sheet->setCellValue("A" . ($footerTop + 6), 'NIP.');
$sheet->getStyle("A" . ($footerTop + 6))->getFont()->setBold(true);
$sheet->mergeCells("K{$footerTop}:L{$footerTop}");
$sheet->setCellValue("K{$footerTop}", 'Kota, Tanggal/Bulan/Tahun');
$sheet->setCellValue("K" . ($footerTop + 1), 'Jabatan');
$sheet->setCellValue("K" . ($footerTop + 5), 'Nama');
$sheet->setCellValue("K" . ($footerTop + 6), 'NIP.');
$sheet->getStyle("K" . ($footerTop + 6))->getFont()->setBold(true);

// Output
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Daftar Arsip Inaktif.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>