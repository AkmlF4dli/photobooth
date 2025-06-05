<?php
session_start();
$dir = "../print/";
$images = array_diff(scandir($dir), array('..', '.'));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Image File Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            padding: 30px;
            color: #333;
        }

        h2 {
            color: #007acc;
        }

        .card {
            display: inline-block;
            background-color: #e6f2ff;
            border: 2px solid #b3d9ff;
            border-radius: 10px;
            padding: 15px;
            margin: 15px;
            text-align: center;
            box-shadow: 2px 2px 6px rgba(0, 102, 204, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        img {
            max-width: 200px;
            height: auto;
            border-radius: 8px;
            border: 1px solid #b3d9ff;
            background-color: #fff;
            padding: 4px;
            margin-bottom: 10px;
        }

        button {
            background-color: #007acc;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #005f99;
        }

        a {
            display: inline-block;
            text-decoration: none;
            background-color: #00aaff;
            color: white;
            padding: 8px 12px;
            margin-top: 5px;
            border-radius: 5px;
        }

        a:hover {
            background-color: #008fcc;
        }

        /* Latar belakang popup */
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }

        /* Konten popup */
        .popup-content {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            min-width: 300px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .popup-content h2 {
            margin-top: 0;
        }

        .popup-content button.close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 50%;
            cursor: pointer;
        }

        /* Tombol Buka Popup */
        .open-btn {
            padding: 10px 20px;
            background: #007acc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .open-btn:hover {
            background: #005f99;
        }
    </style>
</head>
<body>

<!-- Popup -->
<div class="popup-overlay" id="popup">
    <div class="popup-content"> 
	<h2>Login</h2>
        <p>Silakan isi data Anda di bawah ini:</p>
        <form method="POST" action="verify.php">
            <label>Nama:</label><br>
            <input type="text" name="name"><br><br>
            <label>Password:</label><br>
            <input type="password" name="password"><br><br>
            <button type="submit">Kirim</button>
        </form>
    </div>
</div>


<?php
if (isset($_SESSION['login']) == "berhasil"){
?>
<button onclick="window.location.href = './logout.php'">Logout</button>
<?php
}
?>
<h2>Administrator</h2>

<?php if (empty($images)): ?>
    <p>Tidak ada gambar ditemukan.</p>
<?php else: ?>
    <?php foreach ($images as $img): ?>
        <div class="card">
            <img src="<?= $dir . $img ?>" alt="<?= $img ?>">
            <p><?= $img ?></p>
            <form action="/admin/delete.php" method="post" onsubmit="return confirm('Yakin ingin menghapus?')">
                <input type="hidden" name="filename" value="<?= $img ?>">
                <button type="submit">Hapus</button>
            </form>
            <a href="<?= $dir . $img ?>" download>Download</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    function openPopup() {
        document.getElementById('popup').style.display = 'flex';
    }
<?php 
if (isset($_SESSION['login']) != "berhasil")
{
?>
openPopup();
<?php
}
?>
</script>


</body>
</html>

