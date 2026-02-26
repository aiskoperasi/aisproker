<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WorkProgram;

$mappings = [
    'Pengadan Barang dan Jasa' => 'Pengadaan Barang Dan Jasa',
    'PENGADAN BARANG DAN JASA' => 'Pengadaan Barang Dan Jasa',
    'Peminjaman dan Pengmbalian Barang' => 'Peminjaman Dan Pengembalian Barang',
    'Penggunaan Barang Habi Pakai' => 'Penggunaan Barang Habis Pakai',
    'Pengadaan dan Perbaikan Sarana dan Prasarana' => 'Pengadaan Dan Perbaikan Sarana Dan Prasarana',
    'Pemeliharaan dan Perbaikan' => 'Pemeliharaan Dan Perbaikan',
    'Keamanan, Kenyamanan dan Kondusifitas Lingkungan Sekolah' => 'Keamanan, Kenyamanan Dan Kondusifitas Lingkungan Sekolah',
    'Kebersihan dan Keindahan Sekolah' => 'Kebersihan Dan Keindahan Sekolah',
    'Pembinaan Anggota Security dan Tim OB' => 'Pembinaan Anggota Security Dan Tim OB'
];

$updatedCount = 0;
foreach ($mappings as $old => $new) {
    $count = WorkProgram::where('parent_name', $old)->update(['parent_name' => $new]);
    if ($count > 0) {
        echo "Updated '$old' to '$new' ($count rows)\n";
        $updatedCount += $count;
    }
}

// Cleanup any remaining with extra spaces or slightly different casing
$programs = WorkProgram::all();
foreach ($programs as $p) {
    if (!$p->parent_name)
        continue;

    $normalized = strtoupper(preg_replace('/\s+/', ' ', trim($p->parent_name)));

    $targets = [
        'PENGADAAN DAN PERBAIKAN SARANA DAN PRASARANA' => 'Pengadaan Dan Perbaikan Sarana Dan Prasarana',
        'PENGADAAN BARANG DAN JASA' => 'Pengadaan Barang Dan Jasa',
        'PEMELIHARAAN DAN PERBAIKAN' => 'Pemeliharaan Dan Perbaikan',
        'LAYANAN PENGGUNAAN FASILITAS' => 'Layanan Penggunaan Fasilitas',
        'PEMINJAMAN DAN PENGEMBALIAN BARANG' => 'Peminjaman Dan Pengembalian Barang',
        'PENGGUNAAN BARANG HABIS PAKAI' => 'Penggunaan Barang Habis Pakai',
        'KEAMANAN, KENYAMANAN DAN KONDUSIFITAS LINGKUNGAN SEKOLAH' => 'Keamanan, Kenyamanan Dan Kondusifitas Lingkungan Sekolah',
        'KEBERSIHAN DAN KEINDAHAN SEKOLAH' => 'Kebersihan Dan Keindahan Sekolah',
        'PEMBINAAN ANGGOTA SECURITY DAN TIM OB' => 'Pembinaan Anggota Security Dan Tim OB',
        'PENGADAN BARANG DAN JASA' => 'Pengadaan Barang Dan Jasa',
        'PEMINJAMAN DAN PENGMBALIAN BARANG' => 'Peminjaman Dan Pengembalian Barang',
        'PENGGUNAAN BARANG HABI PAKAI' => 'Penggunaan Barang Habis Pakai',
    ];

    if (isset($targets[$normalized]) && $p->parent_name !== $targets[$normalized]) {
        echo "Normalizing: '{$p->parent_name}' -> '{$targets[$normalized]}'\n";
        $p->update(['parent_name' => $targets[$normalized]]);
        $updatedCount++;
    }
}

echo "\nDatabase fix finished. Total records updated: $updatedCount\n";
