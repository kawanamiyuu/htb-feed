<?php
/* @var \Kawanamiyuu\HtbFeed\Bookmark\Bookmarks $bookmarks */
/* @var string $title */
/* @var string $atomUrl */
/* @var string $rssUrl */
?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title, ENT_QUOTES) ?></title>
    <link rel="alternate" type="application/atom+xml" href="<?= $atomUrl ?>" title="<?= htmlspecialchars($title, ENT_QUOTES) ?>" />
    <link rel="alternate" type="application/rss+xml" href="<?= $rssUrl ?>" title="<?= htmlspecialchars($title, ENT_QUOTES) ?>" />
</head>
<body>

<ul>
    <?php foreach ($bookmarks as $bookmark): ?>
    <li>
        <a href="<?= $bookmark->url ?>" target="_blank">
            <?= htmlspecialchars($bookmark->title, ENT_QUOTES) ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>

</body>
</html>
