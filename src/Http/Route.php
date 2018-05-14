<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @method static Route INDEX()
 * @method static Route ATOM()
 */
class Route
{
    private const routes = [
        '/' => HtmlResponseBuilder::class,
        '/atom' => AtomResponseBuilder::class
    ];

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $builder;

    /**
     * @param string $path
     * @param string $builder
     */
    private function __construct(string $path, string $builder)
    {
        $this->path = $path;
        $this->builder = $builder;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return Route
     */
    public static function matches(ServerRequestInterface $request): self
    {
        $path = rtrim($request->getUri()->getPath(), '/');
        $path = $path === '' ? '/' : $path;

        if (! in_array($path, array_keys(self::routes))) {
            return new self($path, NotFoundResponseBuilder::class);
        }

        return new self($path, self::routes[$path]);
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return Route
     */
    public static function __callStatic(string $name, array $arguments = []): self
    {
        // unused
        unset($arguments);

        $name = strtolower($name);
        $path = '/' . ($name === 'index' ? '' : $name);
        if (! in_array($path, array_keys(self::routes))) {
            throw new \LogicException("unknown route '{$path}'");
        }

        return new self($path, self::routes[$path]);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    public function getUrl(ServerRequestInterface $request): string
    {
        $uri = $request->getUri();
        $url = sprintf('%s://%s%s?%s', $uri->getScheme(), $uri->getAuthority(), $this->path, $uri->getQuery());
        // trim trailing '?' if query does not exist
        return rtrim($url, '?');
    }

    /**
     * @return string
     */
    public function builder(): string
    {
        return $this->builder;
    }
}
