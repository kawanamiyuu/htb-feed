<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Feed;

use DateTimeInterface;

final class FeedMeta
{
    private const TITLE = 'はてなブックマークの新着エントリー';

    private DateTimeInterface $publishedAt;

    private string $htmlUrl;

    private string $atomUrl;

    private string $rssUrl;

    public function __construct(DateTimeInterface $publishedAt, string $htmlUrl, string $atomUrl, string $rssUrl)
    {
        $this->publishedAt = $publishedAt;
        $this->htmlUrl = $htmlUrl;
        $this->atomUrl = $atomUrl;
        $this->rssUrl = $rssUrl;
    }

    public function title(): string
    {
        return self::TITLE;
    }

    public function publishedAt(): DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function htmlUrl(): string
    {
        return $this->htmlUrl;
    }

    public function atomUrl(): string
    {
        return $this->atomUrl;
    }

    public function rssUrl(): string
    {
        return $this->rssUrl;
    }
}
