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
     * @return array<string, string>
     */
    protected static function enumerate(): array
    {
        return [
            'HTML' => HtmlGenerator::class,
            'ATOM' => AtomGenerator::class,
            'RSS' => RssGenerator::class,
        ];
    }

    public function generator(): FeedGeneratorInterface
    {
        $feedGenerator = $this->getConstantValue();
        return new $feedGenerator();
    }
}
