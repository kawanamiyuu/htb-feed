<?php

namespace Kawanamiyuu\HtbFeed\Feed;

/**
 * @method static FeedType HTML()
 * @method static FeedType ATOM()
 * @method static FeedType RSS()
 */
final class FeedType
{
    public const HTML = 'html';
    public const ATOM = 'atom';
    public const RSS = 'rss';

    private const TYPES = [
        self::HTML => HtmlGenerator::class,
        self::ATOM => AtomGenerator::class,
        self::RSS => RssGenerator::class
    ];

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $generator;

    /**
     * @param string $type
     * @param string $generator
     */
    public function __construct(string $type, string $generator)
    {
        $this->type = $type;
        $this->generator = $generator;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function generator(): string
    {
        return $this->generator;
    }

    /**
     * @param string $type
     *
     * @return FeedType
     */
    public static function typeOf(string $type): FeedType
    {
        if (! array_key_exists($type, self::TYPES)) {
            throw new \LogicException("unknown feed type '{$type}'");
        }

        return new self($type, self::TYPES[$type]);
    }

    /**
     * @param string $name
     * @param array<mixed>  $arguments
     *
     * @return FeedType
     */
    public static function __callStatic(string $name, array $arguments = []): FeedType
    {
        // unused
        unset($arguments);

        return self::typeOf(strtolower($name));
    }
}
