<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;

interface FeedGeneratorInterface
{
    /**
     * @param FeedMeta $meta
     */
    public function __construct(FeedMeta $meta);

    /**
     * @param Bookmarks     $bookmarks
     *
     * @return string
     */
    public function __invoke(Bookmarks $bookmarks): string;

    /**
     * @return string
     */
    public function getContentType(): string;
}
