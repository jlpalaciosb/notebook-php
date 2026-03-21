<header class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary">
    <a href="/" class="d-block text-decoration-none">
        <?php require_once BASE_PATH . '/templates/logo.php' ?>
    </a>

    <div class="d-flex align-items-center gap-3">
        <span class="text-dark-50 small">
            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['user'] ?>
        </span>
        <a href="/user/logout.php" class="btn btn-sm btn-outline-danger">Salir</a>
    </div>
</header>
