<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;

interface FeedGeneratorInterface
{
    public function __invoke(FeedMeta $meta, Bookmarks $bookmarks): string;
}
