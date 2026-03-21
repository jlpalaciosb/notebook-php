<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/..') . '/src/bootstrap.php';
require_once BASE_PATH . '/lib/database.php';

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /user/login.php');
    exit();
}

$stmt = $GLOBALS['connection']->prepare('DELETE FROM entries WHERE date = :d AND owner=:o');
$stmt->bindParam(':d', $_GET['date']);
$stmt->bindParam(':o', $_SESSION['user']);
$stmt->execute();
echo 'ok';
