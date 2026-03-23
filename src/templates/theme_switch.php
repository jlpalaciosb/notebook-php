<?php
$currentTheme = ($_SESSION['theme'] ?? 'l') === 'd' ? 'dark' : 'light';
?>

<div class="position-fixed bottom-0 start-0 p-3" style="z-index: 2000;">
    <div class="form-check form-switch bg-body border rounded-pill px-4 py-2 shadow-sm d-flex align-items-center">
        <input class="form-check-input ms-0 my-0 cursor-pointer me-2" type="checkbox" id="themeSwitch"
        <?php echo $currentTheme === 'dark' ? 'checked' : ''; ?>>
        <div class="form-check-label mb-0" for="themeSwitch">
            <i class="bi <?php echo $currentTheme === 'dark' ? 'bi-moon-stars-fill text-white' : 'bi-sun-fill text-warning'; ?> fs-5" id="themeIcon"></i>
        </div>
    </div>
</div>

<script>
(function() {
    const html = document.documentElement;
    const themeSwitch = document.getElementById('themeSwitch');
    const themeIcon = document.getElementById('themeIcon');

    if (themeSwitch) {
        themeSwitch.addEventListener('change', function() {
            const isDark = this.checked;
            const theme = isDark ? 'dark' : 'light';
            
            // UI
            html.setAttribute('data-bs-theme', theme);
            
            // Icon
            themeIcon.className = isDark ? 'bi bi-moon-stars-fill text-white fs-5' : 'bi bi-sun-fill text-warning fs-5';

            // Persistence
            fetch('/user/theme.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'theme=' + theme
            }).catch(console.error);
        });
    }
})();
</script>
