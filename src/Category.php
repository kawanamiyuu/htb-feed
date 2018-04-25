<?php

namespace Kawanamiyuu\HtbFeed;

/**
 * @method static Category ALL()
 * @method static Category GENERAL()
 * @method static Category SOCIAL()
 * @method static Category ECONOMICS()
 * @method static Category LIFE()
 * @method static Category KNOWLEDGE()
 * @method static Category IT()
 * @method static Category FUN()
 * @method static Category ENTERTAINMENT()
 * @method static Category GAME()
 */
final class Category
{
    private const CATEGORIES = [
        'all',
        'general',
        'social',
        'economics',
        'life',
        'knowledge',
        'it',
        'fun',
        'entertainment',
        'game'
    ];

    /**
     * @var string
     */
    private $category;

    /**
     * @param string $category
     */
    private function __construct(string $category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     *
     * @return Category
     */
    public static function valueOf(string $category): Category
    {
        $category = strtolower($category);
        if (in_array($category, self::CATEGORIES)) {
            return new self($category);
        }

        throw new \LogicException("unknown category '{$category}'");
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return Category
     */
    public static function __callStatic(string $name, array $arguments = []): Category
    {
        // unused
        unset($arguments);

        return self::valueOf($name);
    }
}
