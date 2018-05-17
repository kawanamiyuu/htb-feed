<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;

interface FeedGeneratorInterface
{
    const TITLE = 'はてなブックマークの新着エントリー';

    /**
     * @param Bookmarks $bookmarks
     * @param string    $feedUrl
     * @param string    $htmlUrl
     *
     * @return string
     */
    function __invoke(Bookmarks $bookmarks, string $feedUrl, string $htmlUrl): string;
}
