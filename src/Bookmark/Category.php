<?php

namespace Kawanamiyuu\HtbFeed\Bookmark;

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
        'all' => '総合',
        'general' => '一般',
        'social' => '世の中',
        'economics' => '政治と経済',
        'life' => '暮らし',
        'knowledge' => '学び',
        'it' => 'テクノロジー',
        'fun' => 'おもしろ',
        'entertainment' => 'エンタメ',
        'game' => 'アニメとゲーム'
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $label;

    /**
     * @param string $value
     * @param string $label
     */
    private function __construct(string $value, string $label)
    {
        $this->value = $value;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function label(): string
    {
        return $this->label;
    }

    /**
     * @param string $value
     *
     * @return Category
     */
    public static function valueOf(string $value): Category
    {
        if (array_key_exists($value, self::CATEGORIES)) {
            return new self($value, self::CATEGORIES[$value]);
        }

        throw new \LogicException("unknown category '{$value}'");
    }

    /**
     * @param string $label
     *
     * @return Category
     */
    public static function labelOf(string $label): Category
    {
        if (false !== ($value = array_search($label, self::CATEGORIES))) {
            assert(is_string($value));
            return new self($value, $label);
        }

        throw new \LogicException("unknown category '{$label}'");
    }

    /**
     * @param string $name
     * @param array<mixed> $arguments
     *
     * @return Category
     */
    public static function __callStatic(string $name, array $arguments = []): Category
    {
        // unused
        unset($arguments);

        return self::valueOf(strtolower($name));
    }
}
