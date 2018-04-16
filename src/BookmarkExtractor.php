<?php

namespace Kawanamiyuu\HtbFeed;

use DateTime;
use DateTimeZone;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class BookmarkExtractor {

    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @param string $category
     * @param int    $page
     */
    public function __construct(string $category, int $page = 1)
    {
        $url = sprintf('http://b.hatena.ne.jp/entrylist/%s?page=%d', $category, $page);
        $this->crawler = (new Client)->request('GET', $url);
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
