<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Feed\FeedType;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @method static Route INDEX()
 * @method static Route HTML()
 * @method static Route ATOM()
 * @method static Route RSS()
 */
class Route
{
    private const ROUTES = [
        '/' => FeedType::HTML,
        '/html' => FeedType::HTML,
        '/atom' => FeedType::ATOM,
        '/rss' => FeedType::RSS
    ];

    /**
     * @var string
     */
    private $path;

    /**
     * @var FeedType
     */
    private $feedType;

    /**
     * @param string   $path
     * @param FeedType $feedType
     */
    private function __construct(string $path, FeedType $feedType)
    {
        $this->path = $path;
        $this->feedType = $feedType;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return FeedType
     */
    public function feedType(): FeedType
    {
        return $this->feedType;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return Route
     */
    public static function resolve(ServerRequestInterface $request): Route
    {
        return self::__callStatic(trim($request->getUri()->getPath(), '/'));
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

        if (! array_key_exists($path, self::ROUTES)) {
            throw new \LogicException("unknown route '{$path}'");
        }

        return new self($path, FeedType::typeOf(self::ROUTES[$path]));
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
}
