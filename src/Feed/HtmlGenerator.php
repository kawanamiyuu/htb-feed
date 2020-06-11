<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;

use function Kawanamiyuu\HtbFeed\load_html_template;

class HtmlGenerator implements FeedGeneratorInterface
{
    /**
     * @var FeedMeta
     */
    private $meta;

    /**
     * @param FeedMeta $meta
     */
    public function __construct(FeedMeta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Bookmarks $bookmarks): string
    {
        return load_html_template([
            'bookmarks' => $bookmarks,
            'title' => $this->meta->title(),
            'atomUrl' => $this->meta->atomUrl(),
            'rssUrl' => $this->meta->rssUrl()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType(): string
    {
        return 'text/html; charset=UTF-8';
    }
}
