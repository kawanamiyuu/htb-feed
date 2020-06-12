<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Feed;

final class Feed
{
    private string $content;

    private string $contentType;

    public function __construct(string $content, string $contentType)
    {
        $this->content = $content;
        $this->contentType = $contentType;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function contentType(): string
    {
        return $this->contentType;
    }
}
