<div class="options">
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <a href="/admin/stats" class="button">Stats Dashboard</a>
        <a href="/admin/photos" class="button">Photo Dashboard</a>
        <a href="/admin?logout=true" class="button logout">Log Out</a>
    <?php else: ?>
        <a href="/admin" class="button">Admin Login</a>
    <?php endif; ?>
</div>