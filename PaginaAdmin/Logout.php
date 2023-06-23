<?php
session_start();
unset($_SESSION["user"]);
unset($_SESSION["parola"]);
header("Location:http://localhost/aplicatie/Logare/Login.php");
?>