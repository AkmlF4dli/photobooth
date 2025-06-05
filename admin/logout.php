<?php
session_start();
session_unset();  // optional: clear all session variables
session_destroy(); // ends the session completely
header("Location: index.php");
?>
