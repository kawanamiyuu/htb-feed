<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Psr\Http\Message\UriInterface;

class Route
{
    private const routes = [
        '/atom' => AtomResponseBuilder::class
    ];

    /**
     * @var string
     */
    private $builder;

    /**
     * @param string $builder
     */
    private function __construct(string $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param UriInterface $uri
     *
     * @return Route
     */
    public static function matches(UriInterface $uri): self
    {
        $path = rtrim($uri->getPath(), '/');
        $path = $path === '' ? '/' : $path;

        if (! in_array($path, array_keys(self::routes))) {
            return new self(NotFoundResponseBuilder::class);
        }

        return new self(self::routes[$path]);
    }

    /**
     * @return string
     */
    public function builder(): string
    {
        return $this->builder;
    }
}
