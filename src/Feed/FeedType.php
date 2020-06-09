<?php

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
            'HTML' => ['html', HtmlGenerator::class],
            'ATOM' => ['atom', AtomGenerator::class],
            'RSS' => ['rss', RssGenerator::class]
        ];
    }

    public function type(): string
    {
        return $this->getConstantValue()[0];
    }

    public function generator(): string
    {
        return $this->getConstantValue()[1];
    }
}
