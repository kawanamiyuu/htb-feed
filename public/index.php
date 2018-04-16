<?php

use GuzzleHttp\Psr7\ServerRequest;
use Kawanamiyuu\HtbFeed\AtomGenerator;
use Kawanamiyuu\HtbFeed\Bookmark;
use Kawanamiyuu\HtbFeed\BookmarkExtractor;

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

const CATEGORY = 'it';
const MAX_PAGE = 5;
const FEED_TITLE = 'はてなブックマークの新着エントリー';
const FEED_URL = 'http://www.example.com/atom';

$request = ServerRequest::fromGlobals();

$minUsers = (int) ($request->getQueryParams()['users'] ?? "") ?: 50;

/* @var Bookmark[] $bookmarks */
$bookmarks = [];

foreach (range(1, MAX_PAGE) as $page) {
    $extractor = new BookmarkExtractor(CATEGORY, $page);

    $bookmarks = array_merge($bookmarks, array_filter($extractor(), function (Bookmark $bookmark) use ($minUsers) {
        return $bookmark->users >= $minUsers;
    }));
}

$generator = new AtomGenerator($bookmarks, FEED_TITLE, FEED_URL);

header('Content-Type: application/atom+xml');
echo  $generator() . "\n";
