<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use function Kawanamiyuu\HtbFeed\load_html_template;

class HtmlGenerator implements FeedGeneratorInterface
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Bookmarks $bookmarks): string
    {
        return load_html_template([
            'bookmarks' => $bookmarks,
            'title' => $this->config->title(),
            'atomUrl' => $this->config->atomUrl(),
            'rssUrl' => $this->config->rssUrl()
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
