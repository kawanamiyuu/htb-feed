<?php

namespace Kawanamiyuu\HtbFeed\Http;

use K9u\Enum\AbstractEnum;
use Kawanamiyuu\HtbFeed\Feed\FeedType;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @method static Route INDEX()
 * @method static Route HTML()
 * @method static Route ATOM()
 * @method static Route RSS()
 */
final class Route extends AbstractEnum
{
    /**
     * @return array<string, array{string, string}>
     */
    protected static function enumerate(): array
    {
        return [
            'INDEX' => ['/', FeedType::HTML()],
            'HTML' => ['/html', FeedType::HTML()],
            'ATOM' => ['/atom', FeedType::ATOM()],
            'RSS' => ['/rss', FeedType::RSS()]
        ];
    }

    public function path(): string
    {
        return $this->getConstantValue()[0];
    }

    public function feedType(): FeedType
    {
        return $this->getConstantValue()[1];
    }

    public static function resolve(ServerRequestInterface $request): ?Route
    {
        $path = '/' . trim($request->getUri()->getPath(), '/');

        foreach (self::constants() as $constant) {
            if ($constant->path() === $path) {
                return $constant;
            }
        }

        return null;
    }

    public function getUrl(ServerRequestInterface $request): string
    {
        $uri = $request->getUri();
        $url = sprintf('%s://%s%s?%s', $uri->getScheme(), $uri->getAuthority(), $this->path(), $uri->getQuery());
        // trim trailing '?' if query does not exist
        return rtrim($url, '?');
    }
}
