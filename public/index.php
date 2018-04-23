<?php

use Kawanamiyuu\HtbFeed\AtomGenerator;
use Kawanamiyuu\HtbFeed\Bookmark;
use Kawanamiyuu\HtbFeed\HtbClientFactory;

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

const CATEGORY = 'it';
const MAX_PAGE = 10;
const FEED_TITLE = 'はてなブックマークの新着エントリー';
const FEED_URL = 'http://www.example.com/atom';

$minUsers = (int) ($_GET['users'] ?? '') ?: 50;

$bookmarks = HtbClientFactory::create()
    ->fetch(CATEGORY, MAX_PAGE)
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
