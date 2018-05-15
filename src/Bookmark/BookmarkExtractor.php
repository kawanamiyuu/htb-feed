<?php

namespace Kawanamiyuu\HtbFeed\Bookmark;

use DateTime;
use DateTimeZone;
use Symfony\Component\DomCrawler\Crawler;

class BookmarkExtractor
{
    /**
     * @param string $html
     *
     * @return Bookmarks
     */
    public function __invoke(string $html): Bookmarks
    {
        $bookmarks = [];

        (new Crawler($html))->filter('.entrylist-item .entrylist-image-entry')->each(function (Crawler $node) use (&$bookmarks) {
            $bookmark = new Bookmark;
            $bookmark->category = $node->filter('.entrylist-contents-category a')->text();
            $bookmark->users = Users::valueOf($node->filter('.entrylist-contents-users span')->text());
            $bookmark->title = $node->filter('.entrylist-contents-title a')->attr('title');
            $bookmark->url = $node->filter('.entrylist-contents-title a')->attr('href');
            $bookmark->domain = $node->filter('.entrylist-contents-domain a span')->text();
            $bookmark->date = new DateTime($node->filter('.entrylist-contents-date')->text(), new DateTimeZone('Asia/Tokyo'));
            $bookmarks[] = $bookmark;
        });

        return new Bookmarks($bookmarks);
    }
}
