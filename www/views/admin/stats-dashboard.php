<?php $pageTitle = 'Stats Dashboard'; ?>
<?php include '../views/partials/header.php'; ?>

<body>
    <div class="container home-page">
        <h1>Admin Stats Dashboard</h1>
        <p>
            Welcome to the Admin Stats Dashboard! Here, you can view insights into the platform's data, including the total photos uploaded, the most popular city for photos, and any flagged users. Use the buttons below to navigate.
        </p>
        <div class="options">
            <a href="/admin/dashboard/photos" class="button">Photo Dashboard</a>
            <a href="/admin/logout" class="button logout">Log Out</a>
        </div>
        <section class="stats">
            <h2>Statistics Overview</h2>
            <div class="stats-card">
                <p><strong>Total Photos:</strong> <?= $totalPhotos; ?></p>
            </div>
            <div class="stats-card">
                <p><strong>Most Popular City:</strong> <?= htmlspecialchars($mostPopularCity); ?></p>
            </div>
            <div class="stats-card">
                <p><strong>Flagged Users:</strong></p>
                <ul>
                    <?php if (!empty($flaggedUsers)): ?>
                        <?php foreach ($flaggedUsers as $user): ?>
                            <li><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No flagged users.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </section>
    </div>
    <?php include '../views/partials/footer.php'; ?>