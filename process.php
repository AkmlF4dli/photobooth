<link rel="stylesheet" href="processstyle.css">
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pastikan folder uploads ada dan writable
$uploadDir = 'photos';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
if (!is_writable($uploadDir)) {
    die("Folder 'photos' tidak writable.");
}

// Ambil email dan filter dari POST, dengan fallback null
$email = $_POST['email'] ?? null;
$filters = [];
for ($i = 0; $i <= 3; $i++) {
    $filters[] = $_POST['filter' . $i] ?? null;
}

if (!$email) {
    die("Email tidak dikirim.");
}

// Proses foto
$photos = [];
for ($i = 1; $i <= 3; $i++) {
    $photoKey = 'photo' . $i;
    if (isset($_POST[$photoKey]) && !empty($_POST[$photoKey])) {
        $data = $_POST[$photoKey];
        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = base64_decode($data);

        if ($data === false) {
            die("Gagal decode base64 foto ke-$i.");
        }

        $tmpFile = "$uploadDir/photo_$i.jpg";
        file_put_contents($tmpFile, $data);

        if (!file_exists($tmpFile) || filesize($tmpFile) === 0) {
            die("File foto ke-$i gagal dibuat.");
        }

        $image = @imagecreatefromjpeg($tmpFile);
        if (!$image) {
            die("Gagal membuka gambar foto ke-$i, file bukan JPEG valid.");
        }

        // Terapkan filter jika ada
        if (!empty($filters[$i - 1])) {
            switch ($filters[$i - 1]) {
                case 'grayscale':
                    imagefilter($image, IMG_FILTER_GRAYSCALE);
                    break;
                case 'sepia':
                    imagefilter($image, IMG_FILTER_GRAYSCALE);
                    imagefilter($image, IMG_FILTER_COLORIZE, 90, 60, 40);
                    break;
            }
        }

	$photos[] = $image;
    }
}

if (count($photos) < 3) {
    die("Jumlah foto kurang dari 3, tidak bisa buat kolase.");
}


// Buat kolase 2x2
$width = imagesx($photos[0]);
$height = imagesy($photos[0]);

// Base untuk foto
$collage = imagecreatetruecolor($width * 1, $height * 3);

// Penempatan Foto pada Base
imagecopy($collage, $photos[0], 0, 0, 0, 0, $width, $height);
imagecopy($collage, $photos[1], 0, $height, 0, 0, $width, $height);
imagecopy($collage, $photos[2], 0, $height + $height, 0, 0, $width, $height + $height);


//print
$printDir = "./print";
$outputPath = $printDir . '/collage_' . time() . "1x3" . '.jpg';
imagejpeg($collage, $outputPath);
 
// Kirim email (pastikan kamu punya file send_email.php dengan fungsi sendPhotoEmail)
require 'send_email.php';
sendPhotoEmail($email, $outputPath);

echo "<h2>Kolase berhasil dikirim ke $email!</h2>";
echo "<img src='$outputPath' style='max-width:400px'>";

?>

