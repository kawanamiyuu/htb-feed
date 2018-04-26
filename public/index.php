<?php

use Kawanamiyuu\HtbFeed\AtomGenerator;
use Kawanamiyuu\HtbFeed\Bookmark;
use Kawanamiyuu\HtbFeed\Category;
use Kawanamiyuu\HtbFeed\HtbClientFactory;

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

const MAX_PAGE = 10;
const FEED_TITLE = 'はてなブックマークの新着エントリー';
const FEED_URL = 'http://www.example.com/atom';

if (isset($_GET['category'])) {
    $category = Category::valueOf($_GET['category']);
} else {
    $category = Category::ALL();
}

$minUsers = (int) ($_GET['users'] ?? '100');

$bookmarks = HtbClientFactory::create()
    ->fetch($category, MAX_PAGE)
    ->filter(function (Bookmark $bookmark) use ($minUsers) {
        return $bookmark->users >= $minUsers;
    })
    ->sort(function (Bookmark $a, Bookmark $b) {
        // date DESC
        return $b->date < $a->date ? -1 : 1;
    });

$generator = new AtomGenerator($bookmarks, FEED_TITLE, FEED_URL);

header('Content-Type: application/atom+xml; charset=UTF-8');
echo  $generator() . "\n";
