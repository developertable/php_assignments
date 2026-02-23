<?php
/**
 * Breadcrumb Navigation Component
 * Usage: include breadcrumb and pass $breadcrumbs array
 */
if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/php-assignments/Assignment_4/index.php">Home</a></li>
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
            <?php if ($index === array_key_last($breadcrumbs)): ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($crumb['name']); ?></li>
            <?php else: ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo htmlspecialchars($crumb['url']); ?>">
                        <?php echo htmlspecialchars($crumb['name']); ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
<?php endif; ?>