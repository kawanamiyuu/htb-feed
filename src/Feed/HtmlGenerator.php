<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use function Kawanamiyuu\HtbFeed\load_html_template;

class HtmlGenerator implements FeedGeneratorInterface
{
    /**
     * @param Bookmarks     $bookmarks
     * @param Configuration $config
     *
     * @return string
     */
    function __invoke(Bookmarks $bookmarks, Configuration $config): string
    {
        return load_html_template([
            'bookmarks' => $bookmarks,
            'title' => $config->title(),
            'atomUrl' => $config->atomUrl(),
            'rssUrl' => $config->rssUrl()
        ]);
    }
}
