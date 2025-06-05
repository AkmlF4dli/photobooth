<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = basename($_POST['filename']);
    $filepath = "../print/" . $filename;

    if (file_exists($filepath)) {
        unlink($filepath);
        echo "<script>alert('Gambar berhasil dihapus.'); window.location.href = '/admin/index.php';</script>";
    } else {
        echo "<script>alert('File tidak ditemukan.'); window.location.href = '/admin/index.php';</script>";
    }
} else {
    header("Location: ./index.php");
    exit;
}

