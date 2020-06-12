<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Bookmark;

use K9u\Enum\AbstractEnum;
use LogicException;

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
final class Category extends AbstractEnum
{
    /**
     * @return array<string, array{string, string}>
     */
    protected static function enumerate(): array
    {
        return [
            'ALL' => ['all', '総合'],
            'GENERAL' => ['general', '一般'],
            'SOCIAL' => ['social', '世の中'],
            'ECONOMICS' => ['economics', '政治と経済'],
            'LIFE' => ['life', '暮らし'],
            'KNOWLEDGE' => ['knowledge', '学び'],
            'IT' => ['it', 'テクノロジー'],
            'FUN' => ['fun', 'おもしろ'],
            'ENTERTAINMENT' => ['entertainment', 'エンタメ'],
            'GAME' => ['game', 'アニメとゲーム']
        ];
    }

    public function value(): string
    {
        return $this->getConstantValue()[0];
    }

    public function label(): string
    {
        return $this->getConstantValue()[1];
    }

    public static function valueOf(string $value): Category
    {
        foreach (self::constants() as $constant) {
            if ($constant->value() === $value) {
                return $constant;
            }
        }

        throw new LogicException("unknown category: {$value}");
    }

    public static function labelOf(string $label): Category
    {
        foreach (self::constants() as $constant) {
            if ($constant->label() === $label) {
                return $constant;
            }
        }

        throw new LogicException("unknown category: {$label}");
    }
}
