<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Http;

use K9u\RequestMapper\Annotation\GetMapping;
use K9u\RequestMapper\PathParams;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClient;
use Kawanamiyuu\HtbFeed\Bookmark\Users;
use Kawanamiyuu\HtbFeed\Feed\FeedMeta;
use Kawanamiyuu\HtbFeed\Feed\FeedType;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private ResponseFactoryInterface $responseFactory;

    private StreamFactoryInterface $streamFactory;

    private HtbClient $htbClient;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        HtbClient $htbClient
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->htbClient = $htbClient;
    }

    /**
     * @GetMapping("/{format<(html|rss|atom)?>}")
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $pathParams = $request->getAttribute(PathParams::class);
        assert($pathParams instanceof PathParams);

        $category = isset($request->getQueryParams()['category'])
            ? Category::valueOf($request->getQueryParams()['category']) // TODO: validation
            : Category::ALL();

        $users = isset($request->getQueryParams()['users'])
            ? Users::valueOf($request->getQueryParams()['users'])  // TODO: validation
            : Users::valueOf(100);

        $bookmarks = $this->htbClient
            ->fetch($category)
            ->filter(function (Bookmark $bookmark) use ($users) {
                return $bookmark->users->value() >= $users->value();
            });

        $feedType = FeedType::formatOf($pathParams['format'] ? $pathParams['format'] : 'html');
        $feedGenerator = $feedType->generator();

        $feedMeta = new FeedMeta(
            (string) $request->getUri()->withPath(FeedType::HTML()->format()),
            (string) $request->getUri()->withPath(FeedType::ATOM()->format()),
            (string) $request->getUri()->withPath(FeedType::RSS()->format())
        );

        $feed = $feedGenerator($feedMeta, $bookmarks);

        return $this->responseFactory->createResponse()
            ->withHeader('Content-Type', sprintf('%s; charset=UTF-8', $feed->contentType()))
            ->withBody($this->streamFactory->createStream($feed->content()));
    }
}
