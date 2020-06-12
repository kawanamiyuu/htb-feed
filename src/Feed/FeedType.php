<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Feed;

use K9u\Enum\AbstractEnum;

/**
 * @method static FeedType HTML()
 * @method static FeedType ATOM()
 * @method static FeedType RSS()
 */
final class FeedType extends AbstractEnum
{
    /**
     * @return array<string, array{string, string}>
     */
    protected static function enumerate(): array
    {
        return [
            'HTML' => [HtmlGenerator::class, 'text/html'],
            'ATOM' => [AtomGenerator::class, 'application/atom+xml'],
            'RSS' => [RssGenerator::class, 'application/rss+xml'],
        ];
    }

    public function generator(): string
    {
        return $this->getConstantValue()[0];
    }

    public function contentType(): string
    {
        return $this->getConstantValue()[1];
    }
}
