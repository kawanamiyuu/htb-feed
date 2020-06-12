<?php

namespace Kawanamiyuu\HtbFeed\Feed;

final class FeedMeta
{
    private const TITLE = 'はてなブックマークの新着エントリー';

    private string $htmlUrl;

    private string $atomUrl;

    private string $rssUrl;

    /**
     * @param string $htmlUrl
     * @param string $atomUrl
     * @param string $rssUrl
     */
    public function __construct(string $htmlUrl, string $atomUrl, string $rssUrl)
    {
        $this->htmlUrl = $htmlUrl;
        $this->atomUrl = $atomUrl;
        $this->rssUrl = $rssUrl;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return self::TITLE;
    }

    /**
     * @return string
     */
    public function htmlUrl(): string
    {
        return $this->htmlUrl;
    }

    /**
     * @return string
     */
    public function atomUrl(): string
    {
        return $this->atomUrl;
    }

    /**
     * @return string
     */
    public function rssUrl(): string
    {
        return $this->rssUrl;
    }
}
