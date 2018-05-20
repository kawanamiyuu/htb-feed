<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;

interface FeedGeneratorInterface
{
    /**
     * @param Configuration $config
     */
    public function __construct(Configuration $config);

    /**
     * @param Bookmarks     $bookmarks
     *
     * @return string
     */
    function __invoke(Bookmarks $bookmarks): string;

    /**
     * @return string
     */
    function getContentType(): string;
}
