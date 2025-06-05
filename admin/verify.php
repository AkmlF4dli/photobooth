<?php 
session_start();

$setuser = "admin";
$setpassword = "3f8502a28f0709b09c8410df251b6614d0280aba850e42c765729936e694dd87";

$nama = $_POST['name'];
$password = $_POST['password'];
$hashpassword = hash('sha256', $password);

if ($nama == $setuser && $hashpassword == $setpassword){
  $_SESSION['login'] = "berhasil";
  header("Location: index.php");
}
else{
  header("Location: index.php");
}
?>
