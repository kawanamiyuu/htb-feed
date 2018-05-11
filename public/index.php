<?php

use Kawanamiyuu\HtbFeed\Feed\AtomGenerator;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClientFactory;
use Zend\Diactoros\ServerRequestFactory;

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

const MAX_PAGE = 10;
const FEED_TITLE = 'はてなブックマークの新着エントリー';

$request = ServerRequestFactory::fromGlobals();

$feedUrl = (string) $request->getUri();

if (isset($request->getQueryParams()['category'])) {
    $category = Category::valueOf($request->getQueryParams()['category']);
} else {
    $category = Category::ALL();
}

$minUsers = (int) ($request->getQueryParams()['users'] ?? '100');

$bookmarks = HtbClientFactory::create()
    ->fetch($category, MAX_PAGE)
    ->filter(function (Bookmark $bookmark) use ($minUsers) {
        return $bookmark->users >= $minUsers;
    })
    ->sort(function (Bookmark $a, Bookmark $b) {
        // date DESC
        return $b->date < $a->date ? -1 : 1;
    });

$generator = new AtomGenerator($bookmarks, FEED_TITLE, $feedUrl);

header('Content-Type: application/atom+xml; charset=UTF-8');
echo  $generator() . "\n";
