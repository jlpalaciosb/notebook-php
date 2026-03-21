<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/..') . '/src/bootstrap.php';
require_once BASE_PATH . '/lib/database.php';
require_once BASE_PATH . '/lib/utilities.php';

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /user/login.php');
    exit();
}

if (!isset($_GET['date']) || empty($_GET['date']) || formatErrorYMD($_GET['date'])) {
    http_response_code(400);
    include_once BASE_PATH . '/templates/400.php';
    exit();
}

if (!existsEntry($_SESSION['user'], $_GET['date'])) {
    $stmt = $GLOBALS['connection']->prepare('INSERT INTO entries (owner,date,content) VALUES (:o,:d,:c)');
    $stmt->bindParam(':o', $_SESSION['user']);
    $stmt->bindParam(':d', $_GET['date']);
    $content = encrypt('', $_SESSION['crypt_key']);
    $stmt->bindParam(':c', $content);
    $stmt->execute();
}

header('Location: /entry/edit.php?date=' . $_GET['date']);
