<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClient;
use Kawanamiyuu\HtbFeed\Bookmark\Users;
use Kawanamiyuu\HtbFeed\Feed\Configuration;
use Kawanamiyuu\HtbFeed\Feed\FeedGeneratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FeedResponseBuilderMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponsePrototypeFactory
     */
    private $prototypeFactory;

    /**
     * @var HtbClient
     */
    private $htbClient;

    /**
     * @param ResponsePrototypeFactory $prototypeFactory
     * @param HtbClient                $htbClient
     */
    public function __construct(ResponsePrototypeFactory $prototypeFactory, HtbClient $htbClient)
    {
        $this->prototypeFactory = $prototypeFactory;
        $this->htbClient = $htbClient;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
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

        $feedGenerator = $this->createFeedGenerator($request);
        $feed = ($feedGenerator)($bookmarks);

        $response = $this->prototypeFactory->newInstance()
            ->withStatus(200)
            ->withHeader('Content-Type', $feedGenerator->getContentType());

        $response->getBody()->write($feed);

        return $response;
    }

    private function createFeedGenerator(ServerRequestInterface $request): FeedGeneratorInterface
    {
        $config = new Configuration(
            Route::HTML()->getUrl($request),
            Route::ATOM()->getUrl($request),
            Route::RSS()->getUrl($request)
        );

        $generator = Route::resolve($request)->feedType()->generator();

        return new $generator($config);
    }
}
