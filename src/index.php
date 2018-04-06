<?php

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

require dirname(__DIR__) . '/vendor/autoload.php';

const CATEGORY = 'it';
const MAX_PAGE = 2;
const MIN_USERS = 10;

final class Bookmark {
    /** @var string */
    public $category;
    /** @var int */
    public $users;
    /** @var string */
    public $title;
    /** @var string */
    public $url;
    /** @var string */
    public $domain;
    /** @var \DateTime */
    public $date;
}

final class BookmarkExtractor {

    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct(string $category, int $page = 1)
    {
        $url = sprintf('http://b.hatena.ne.jp/entrylist/%s?page=%d', $category, $page);
        $this->crawler = $crawler = (new Client)->request('GET', $url);
    }

    /**
     * @return Bookmark[]
     */
    public function __invoke(): array
    {
        /* @var Bookmark[] $bookmarks */
        $bookmarks = [];

        $this->crawler->filter('.entrylist-item .entrylist-image-entry')->each(function (Crawler $node) use (&$bookmarks) {
            $bookmark = new Bookmark;
            $bookmark->category = $node->filter('.entrylist-contents-category a')->text();
            $bookmark->users = (int) $node->filter('.entrylist-contents-users span')->text();
            $bookmark->title = $node->filter('.entrylist-contents-title a')->attr('title');
            $bookmark->url = $node->filter('.entrylist-contents-title a')->attr('href');
            $bookmark->domain = $node->filter('.entrylist-contents-domain a span')->text();
            $bookmark->date = new DateTime($node->filter('.entrylist-contents-date')->text(), new DateTimeZone('Asia/Tokyo'));
            $bookmarks[] = $bookmark;
        });

        return $bookmarks;
    }
}

/* @var Bookmark[] $bookmarks */
$bookmarks = [];
foreach (range(1, MAX_PAGE) as $page) {
    $extractor = new BookmarkExtractor(CATEGORY, $page);
    $bookmarks = array_merge($bookmarks, array_filter($extractor(), function (Bookmark $bookmark) {
        return $bookmark->users >= MIN_USERS;
    }));
}

var_dump($bookmarks);
