<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/..') . '/src/bootstrap.php';
require_once BASE_PATH . '/lib/database.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme = isset($_POST['theme']) && $_POST['theme'] === 'dark' ? 'd' : 'l';
    $_SESSION['theme'] = $theme;

    if (isset($_SESSION['user'])) {
        $stmt = db()->prepare('UPDATE users SET theme = :t WHERE name = :n');
        $stmt->execute([':t' => $theme, ':n' => $_SESSION['user']]);
    }

    echo json_encode(['success' => true]);
    exit();
}
