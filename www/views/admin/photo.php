<?php $pageTitle = 'Photo Dashboard'; ?>
<?php include '../views/partials/header.php'; ?>

<div class="container home-page">
    <h1>Admin Photo Dashboard</h1>
    <div class="options">
        <a href="/admin/dashboard/stats" class="button">Stats Dashboard</a>
        <a href="/admin/logout" class="button logout">Log Out</a>
    </div>

    <!-- Sorting Options (Buttons) -->
    <div class="sorting">
        <!-- Sort by Image ID -->
        <a href="?sort=ImageID&order=<?= isset($_GET['sort']) && $_GET['sort'] == 'ImageID' && $_GET['order'] == 'ASC' ? 'DESC' : 'ASC'; ?>" class="button">
            Sort by Image ID
            <?php
            if (isset($_GET['sort']) && $_GET['sort'] == 'ImageID') {
                echo ($_GET['order'] == 'ASC') ? '↓' : '↑'; // Opposite arrow in button
            }
            ?>
        </a>

        <!-- Sort by Title -->
        <a href="?sort=Title&order=<?= isset($_GET['sort']) && $_GET['sort'] == 'Title' && $_GET['order'] == 'ASC' ? 'DESC' : 'ASC'; ?>" class="button">
            Sort by Title
            <?php
            if (isset($_GET['sort']) && $_GET['sort'] == 'Title') {
                echo ($_GET['order'] == 'ASC') ? '↓' : '↑'; // Opposite arrow in button
            }
            ?>
        </a>

        <!-- Sort by User (Full Name) -->
        <a href="?sort=UserID&order=<?= isset($_GET['sort']) && $_GET['sort'] == 'UserID' && $_GET['order'] == 'ASC' ? 'DESC' : 'ASC'; ?>" class="button">
            Sort by User
            <?php
            if (isset($_GET['sort']) && $_GET['sort'] == 'UserID') {
                echo ($_GET['order'] == 'ASC') ? '↓' : '↑'; // Opposite arrow in button
            }
            ?>
        </a>
    </div>

    <form method="POST" style="margin: 20px 0;">
        <button type="submit" name="restore_all" class="button">Restore All Deleted Photos</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>
                    Image ID
                    <?php
                    if (isset($_GET['sort']) && $_GET['sort'] == 'ImageID') {
                        echo ($_GET['order'] == 'ASC') ? ' ↑' : ' ↓'; // Arrow in header based on current sort order
                    }
                    ?>
                </th>
                <th>Thumbnail</th>
                <th>
                    Title
                    <?php
                    if (isset($_GET['sort']) && $_GET['sort'] == 'Title') {
                        echo ($_GET['order'] == 'ASC') ? ' ↑' : ' ↓'; // Arrow in header based on current sort order
                    }
                    ?>
                </th>
                <th>
                    User
                    <?php
                    if (isset($_GET['sort']) && $_GET['sort'] == 'UserID') {
                        echo ($_GET['order'] == 'ASC') ? ' ↑' : ' ↓'; // Arrow in header based on current sort order
                    }
                    ?>
                </th>
                <th>Flagged</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($photos as $photo): ?>
                <tr class="<?= $photo['deleted'] ? 'deleted' : ''; ?>">
                    <td><?= htmlspecialchars($photo['ImageID']); ?></td>
                    <td><img src="https://res.cloudinary.com/dng6vk65h/image/upload/w_150,h_150,c_fill/<?= htmlspecialchars($photo['Path']); ?>" alt="Thumbnail"></td>
                    <td><?= htmlspecialchars($photo['Title']); ?></td>
                    <td><?= htmlspecialchars($photo['first_name'] . ' ' . $photo['last_name'] . " (ID: " . $photo['UserID'] . ")"); ?></td>
                    <td><?= $photo['flagged'] ? 'Yes' : 'No'; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="photo_id" value="<?= $photo['ImageID']; ?>">
                            <?php if (!$photo['deleted']): ?>
                                <?php if (!$photo['flagged']): ?>
                                    <button type="submit" name="flag" class="button">Flag</button>
                                <?php else: ?>
                                    <button type="submit" name="unflag" class="button">Unflag</button>
                                <?php endif; ?>
                                <button type="submit" name="delete" class="button logout">Delete</button>
                            <?php else: ?>
                                <!-- No Restore button per your requirement -->
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../views/partials/footer.php'; ?>