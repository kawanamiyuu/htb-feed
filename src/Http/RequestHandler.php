<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClient;
use Kawanamiyuu\HtbFeed\Bookmark\Users;
use Kawanamiyuu\HtbFeed\Feed\FeedMeta;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private ResponsePrototypeFactory $prototypeFactory;

    private HtbClient $htbClient;

    public function __construct(ResponsePrototypeFactory $prototypeFactory, HtbClient $htbClient)
    {
        $this->prototypeFactory = $prototypeFactory;
        $this->htbClient = $htbClient;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route = Route::resolve($request);
        if ($route === null) {
            return $this->prototypeFactory->newInstance()->withStatus(404);
        }

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

        $feedMeta = new FeedMeta(
            Route::HTML()->getUrl($request),
            Route::ATOM()->getUrl($request),
            Route::RSS()->getUrl($request)
        );

        $feedGenerator = $route->feedType()->generator();
        $feed = ($feedGenerator)($feedMeta, $bookmarks);

        $response = $this->prototypeFactory->newInstance()
            ->withStatus(200)
            ->withHeader('Content-Type', sprintf('%s; charset=UTF-8', $feed->contentType()));

        $response->getBody()->write($feed->content());

        return $response;
    }
}
