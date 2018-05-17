<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;

interface FeedGeneratorInterface
{
    /**
     * @param Bookmarks     $bookmarks
     * @param Configuration $config
     *
     * @return string
     */
    function __invoke(Bookmarks $bookmarks, Configuration $config): string;
}
