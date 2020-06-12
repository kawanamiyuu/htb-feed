<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;

use function Kawanamiyuu\HtbFeed\load_html_template;

class HtmlGenerator implements FeedGeneratorInterface
{
    public function __invoke(FeedMeta $meta, Bookmarks $bookmarks): Feed
    {
        $html = load_html_template([
            'bookmarks' => $bookmarks,
            'title' => $meta->title(),
            'atomUrl' => $meta->atomUrl(),
            'rssUrl' => $meta->rssUrl()
        ]);

        return new Feed($html, 'text/html');
    }
}
