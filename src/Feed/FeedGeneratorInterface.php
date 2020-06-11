<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;

interface FeedGeneratorInterface
{
    /**
     * @param FeedMeta  $meta
     * @param Bookmarks $bookmarks
     *
     * @return string
     */
    public function __invoke(FeedMeta $meta, Bookmarks $bookmarks): string;
}
