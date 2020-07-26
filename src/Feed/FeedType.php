<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Feed;

use K9u\Enum\AbstractEnum;
use LogicException;

/**
 * @method static FeedType HTML()
 * @method static FeedType ATOM()
 * @method static FeedType RSS()
 */
final class FeedType extends AbstractEnum
{
    /**
     * @return array<string, array{string, class-string}>
     */
    protected static function enumerate(): array
    {
        return [
            'HTML' => ['html', HtmlGenerator::class],
            'ATOM' => ['atom', AtomGenerator::class],
            'RSS' => ['rss', RssGenerator::class],
        ];
    }

    public function format(): string
    {
        return $this->getConstantValue()[0];
    }

    public function generator(): FeedGeneratorInterface
    {
        $feedGenerator = $this->getConstantValue()[1];
        return new $feedGenerator();
    }

    public static function formatOf(string $format): FeedType
    {
        foreach (self::constants() as $constant) {
            if ($constant->format() === $format) {
                return $constant;
            }
        }

        throw new LogicException("unknown format: {$format}");
    }
}
