<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Http;

use Symfony\Component\Validator\Constraints as Assert;

class QueryParams
{
    /**
     * @Assert\Choice(
     *     {"all", "general", "social", "economics", "life", "knowledge", "it", "fun", "entertainment", "game"}
     * )
     */
    public ?string $category;

    /**
     * @Assert\Positive
     */
    public ?string $users;

    public static function fromArray(array $data): self
    {
        $params = new QueryParams();
        $params->category = $data['category'] ?? 'all';
        $params->users = $data['users'] ?? '100';

        return $params;
    }
}
