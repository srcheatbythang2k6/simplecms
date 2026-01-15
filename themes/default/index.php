<?php require 'header.php'; ?>

<?php foreach ($posts as $post): ?>
    <h2><?= e($post['title']) ?></h2>
    <p><?= nl2br(e($post['content'])) ?></p>
    <hr>
<?php endforeach; ?>

<?php require 'footer.php'; ?>
