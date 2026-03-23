<?php
session_start();

$theme = $_SESSION['theme'] ?? 'l';

session_unset();
session_destroy();

session_start();
$_SESSION['theme'] = $theme;

header("Location: /user/login.php");
