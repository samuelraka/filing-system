<?php
// Export Daftar Arsip Aktif to Excel with a custom layout using PHPSpreadsheet
// Mirrors Vital/Statis top styling, adds grouped Pokok/Sub/Sub-Sub Masalah rows,
// and respects current filters from the Aktif page.

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

// Read filters from query (mirrors pages/aktif.php)
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_kode = isset($_GET['kode']) ? trim($_GET['kode']) : '';
$filter_skaad = isset($_GET['skaad']) ? trim($_GET['skaad']) : '';
$filter_tahun = isset($_GET['tahun']) ? trim($_GET['tahun']) : '';

// Build WHERE clause
$where = "WHERE 1=1";
if ($keyword !== '') {
    $k = mysqli_real_escape_string($conn, $keyword);
    $where .= " AND (
        ia.tanggal LIKE '%$k%' OR
        ssm.kode_subsub LIKE '%$k%' OR
        ia.keterangan_skaad LIKE '%$k%' OR
        ia.uraian_informasi LIKE '%$k%' OR
        aa.nomor_berkas LIKE '%$k%' OR
        ia.nomor_item LIKE '%$k%'
    )";
}
if ($filter_kode !== '') {
    $kode_safe = mysqli_real_escape_string($conn, $filter_kode);
    $where .= " AND ssm.kode_subsub = '$kode_safe'";
}
if ($filter_skaad !== '') {
    $skaad_safe = mysqli_real_escape_string($conn, $filter_skaad);
    $where .= " AND ia.keterangan_skaad = '$skaad_safe'";
}
if ($filter_tahun !== '') {
    $tahun_safe = mysqli_real_escape_string($conn, $filter_tahun);
    // Filter berdasarkan tahun dari tanggal item arsip
    $where .= " AND YEAR(ia.tanggal) = '$tahun_safe'";
}

// Fetch all data (no pagination), including hierarchy labels
$query = "
    SELECT 
        aa.id_arsip,
        aa.nomor_berkas,
        aa.jumlah_item,
        aa.keterangan AS keterangan_berkas,
        ia.id_item,
        ia.nomor_item,
        ia.tanggal,
        ia.keterangan_skaad,
        ia.uraian_informasi,
        ssm.id_subsub, ssm.kode_subsub, ssm.topik_subsub,
        sm.id_sub, sm.kode_sub, sm.topik_sub,
        pm.id_pokok, pm.kode_pokok, pm.topik_pokok
    FROM arsip_aktif aa
    LEFT JOIN item_arsip ia ON ia.id_arsip = aa.id_arsip
    JOIN sub_sub_masalah ssm ON aa.id_subsub = ssm.id_subsub
    JOIN sub_masalah sm ON ssm.id_sub = sm.id_sub
    JOIN pokok_masalah pm ON sm.id_pokok = pm.id_pokok
    $where
    ORDER BY pm.kode_pokok ASC, sm.kode_sub ASC, ssm.kode_subsub ASC, aa.nomor_berkas ASC, ia.nomor_item ASC
";
$result = mysqli_query($conn, $query);

// Group by classification (pokok/sub/sub-sub), then by nomor berkas (id_arsip)
$groups = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $classKey = $row['id_subsub'];
        if (!isset($groups[$classKey])) {
            $groups[$classKey] = [
                'meta' => [
                    'kode_pokok' => $row['kode_pokok'],
                    'topik_pokok' => $row['topik_pokok'],
                    'kode_sub' => $row['kode_sub'],
                    'topik_sub' => $row['topik_sub'],
                    'kode_subsub' => $row['kode_subsub'],
                    'topik_subsub' => $row['topik_subsub'],
                ],
                'berkas' => []
            ];
        }

        $arsipId = $row['id_arsip'];
        if (!isset($groups[$classKey]['berkas'][$arsipId])) {
            $groups[$classKey]['berkas'][$arsipId] = [
                'meta' => [
                    'nomor_berkas' => $row['nomor_berkas'],
                    'jumlah_item' => $row['jumlah_item'],
                    'keterangan_berkas' => $row['keterangan_berkas'],
                ],
                'items' => []
            ];
        }

        if (!empty($row['id_item'])) {
            $groups[$classKey]['berkas'][$arsipId]['items'][] = [
                'nomor_item' => $row['nomor_item'],
                'kode_klasifikasi' => $row['kode_subsub'],
                'uraian_informasi' => $row['uraian_informasi'],
                'tanggal' => $row['tanggal'],
                'keterangan_skaad' => $row['keterangan_skaad'],
            ];
        }
    }
}

// Build spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Daftar Arsip Aktif');
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

// Column widths (A-H)
$sheet->getColumnDimension('A')->setWidth(12); // Nomor Berkas
$sheet->getColumnDimension('B')->setWidth(12); // Nomor Item Arsip
$sheet->getColumnDimension('C')->setWidth(16); // Kode Klasifikasi Arsip
$sheet->getColumnDimension('D')->setWidth(44); // Uraian Informasi Arsip
$sheet->getColumnDimension('E')->setWidth(16); // Tanggal
$sheet->getColumnDimension('F')->setWidth(18); // Jumlah Item Arsip
$sheet->getColumnDimension('G')->setWidth(18); // Keterangan SKAAD
$sheet->getColumnDimension('H')->setWidth(18); // Keterangan

// Logo area + top style (same as Vital/Statis)
$sheet->mergeCells('A1:C3');
$sheet->setCellValue('A1', '');

// Insert logo image
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
        // ignore
    }
}

// Title
$sheet->mergeCells('D2:H2');
$sheet->setCellValue('D2', 'DAFTAR ARSIP AKTIF');
$sheet->getStyle('D2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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
$headers = [
    'NOMOR BERKAS',
    'NO. ITEM ARSIP',
    'KODE KLASIFIKASI ARSIP',
    'URAIAN INFORMASI ARSIP',
    'TANGGAL',
    'JUMLAH ITEM ARSIP',
    'KETERANGAN SKAAD',
    'KETERANGAN'
];
foreach ($headers as $i => $h) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$headerRow}", $h);
}
$sheet->getStyle("A{$headerRow}:H{$headerRow}")->getFont()->setBold(true)->setSize(10);
$sheet->getStyle("A{$headerRow}:H{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle("A{$headerRow}:H{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DAEEF3');
$sheet->getRowDimension($headerRow)->setRowHeight(40);
$sheet->getStyle("A{$headerRow}:H{$headerRow}")->getAlignment()->setWrapText(true);
$sheet->getStyle("A{$headerRow}:H{$headerRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Column numbers row
$numRow = $headerRow + 1;
$nums = ['1','2','3','4','5','6','7','8'];
foreach ($nums as $i => $n) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$numRow}", $n);
}
$sheet->getStyle("A{$numRow}:H{$numRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle("A{$numRow}:H{$numRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle("A{$numRow}:H{$numRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('205867');
$sheet->getStyle("A{$numRow}:H{$numRow}")->getFont()->setSize(10)->getColor()->setARGB('FFFFFF');
$sheet->getRowDimension($numRow)->setRowHeight(18);

$detail_row = $numRow + 1;
$details = [
    'Nomor Urut Berkas',
    'Nomor urut Arsip yang tersimpan dalam Folder',
    'Berisi Tanda Pengenal Arsip (Lihat Pola Klasifikasi Arsip)',
    'Berisi isi Keseluruhan Surat : Asal surat, Nomor, tanggal Surat, Perihal, Penerima, tempat kegiatan (Undangan), tembusan (URAIAN LENGKAP)',
    'Tanggal surat',
    'Berisi Jumlah Arsip dalam Setiap Jenis Arsip (diatas 10lembar ditulis 1 Berkas)',
    'Biasa, Terbatas, dan Rahasia',
    'KETERANGAN'
];
foreach ($details as $i => $d) {
    $col = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue("{$col}{$detail_row}", $d);
}
$sheet->getStyle("A{$detail_row}:H{$detail_row}")->getAlignment()->setWrapText(true);
$sheet->getStyle("A{$detail_row}:H{$detail_row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle("A{$detail_row}:H{$detail_row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getRowDimension($detail_row)->setRowHeight(95);

// Data rows with grouped headers (Pokok/Sub/Sub-Sub)
$startRow = $detail_row + 1;
$r = $startRow;
foreach ($groups as $group) {
    $m = $group['meta'];

    // Group heading rows (merged, bold)
    $sheet->mergeCells("A{$r}:H{$r}");
    $sheet->setCellValue("A{$r}", 'Pokok Masalah : ' . ($m['kode_pokok'] ?? '') . '. ' . ($m['topik_pokok'] ?? ''));
    $sheet->getStyle("A{$r}")->getFont()->setBold(true);
    $sheet->getStyle("A{$r}:H{$r}")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
    $r++;

    $sheet->mergeCells("A{$r}:H{$r}");
    $sheet->setCellValue("A{$r}", 'Sub Masalah : ' . ($m['kode_pokok'] ?? '') . '. ' . ($m['kode_sub'] ?? '') . '. ' . ($m['topik_sub'] ?? ''));
    $sheet->getStyle("A{$r}")->getFont()->setBold(true);
    $sheet->getStyle("A{$r}:H{$r}")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
    $r++;

    $sheet->mergeCells("A{$r}:H{$r}");
    $sheet->setCellValue("A{$r}", 'Sub-Sub Masalah : ' . ($m['kode_pokok'] ?? '') . '. ' . ($m['kode_sub'] ?? '') . '. ' . ($m['kode_subsub'] ?? '') . '. ' . ($m['topik_subsub'] ?? ''));
    $sheet->getStyle("A{$r}")->getFont()->setBold(true);
    $sheet->getStyle("A{$r}:H{$r}")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
    $r++;

    // Iterate each berkas under this classification
    foreach ($group['berkas'] as $berkas) {
        $bm = $berkas['meta'];
        $items = $berkas['items'];

        // If there are no items, still render a single blank row for the berkas
        $rowCount = max(count($items), 1);
        $startDataRow = $r;
        $endDataRow = $r + $rowCount - 1;

        $sheet->getStyle("A{$startDataRow}:A{$endDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_TOP);

        if (count($items) === 0) {
            $sheet->setCellValue("A{$r}", $bm['nomor_berkas'] ?? '');
            $sheet->setCellValue("B{$r}", '');
            $sheet->setCellValue("C{$r}", $m['kode_subsub'] ?? '');
            $sheet->setCellValue("D{$r}", '');
            $sheet->setCellValue("E{$r}", '');
            $sheet->setCellValue("F{$r}", $bm['jumlah_item'] ?? '');
            $sheet->setCellValue("G{$r}", '');
            $sheet->setCellValue("H{$r}", $bm['keterangan_berkas'] ?? '');
            $sheet->getRowDimension($r)->setRowHeight(28);
            $r++;
        } else {
            foreach ($items as $idx => $it) {
                // Nomor Berkas appears once at the first row of this berkas
                $sheet->setCellValue("A{$r}", ($idx === 0) ? ($bm['nomor_berkas'] ?? '') : '');
                $sheet->setCellValue("B{$r}", $it['nomor_item'] ?? '');
                $sheet->setCellValue("C{$r}", $it['kode_klasifikasi'] ?? '');
                $sheet->setCellValue("D{$r}", $it['uraian_informasi'] ?? '');
                $sheet->setCellValue("E{$r}", $it['tanggal'] ?? '');
                $sheet->setCellValue("F{$r}", $bm['jumlah_item'] ?? '');
                $sheet->setCellValue("G{$r}", $it['keterangan_skaad'] ?? '');
                $sheet->setCellValue("H{$r}", $bm['keterangan_berkas'] ?? '');
                // alignments
                $sheet->getStyle("A{$r}:C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E{$r}:H{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("A{$r}:H{$r}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                $sheet->getRowDimension($r)->setRowHeight(28);
                $r++;
            }
        }

        // Borders around the berkas block
        $sheet->getStyle("A{$startDataRow}:H{$endDataRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}

// Footer (signatures) — match Statis/Vital style
$footerTop = $r + 2;
$sheet->setCellValue("A" . ($footerTop + 1), 'Pelaksana');
$sheet->setCellValue("A" . ($footerTop + 5), 'Nama Petugas');
$sheet->setCellValue("A" . ($footerTop + 6), 'NIP.');
$sheet->getStyle("A" . ($footerTop + 6))->getFont()->setBold(true);

$sheet->mergeCells("G{$footerTop}:H{$footerTop}");
$sheet->setCellValue("G{$footerTop}", 'Kota, Tanggal/Bulan/Tahun');
$sheet->setCellValue("G" . ($footerTop + 1), 'Jabatan');
$sheet->setCellValue("G" . ($footerTop + 5), 'Nama');
$sheet->setCellValue("G" . ($footerTop + 6), 'NIP.');
$sheet->getStyle("G" . ($footerTop + 6))->getFont()->setBold(true);

// Output to browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Daftar Arsip Aktif.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>