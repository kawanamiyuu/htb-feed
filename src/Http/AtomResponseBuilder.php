<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClient;
use Kawanamiyuu\HtbFeed\Feed\AtomGenerator;
use Kawanamiyuu\HtbFeed\Feed\Configuration;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AtomResponseBuilder implements ResponseBuilderInterface
{
    const CONTENT_TYPE = 'application/atom+xml; charset=UTF-8';

    /**
     * @var HtbClient
     */
    private $client;

    /**
     * @param HtbClient $client
     */
    public function __construct(HtbClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $query = (new QueryExtractor)($request);
        /* @var QueryExtractor $query */

        $bookmarks = $this->client
            ->fetch($query->category)
            ->filter(function (Bookmark $bookmark) use ($query) {
                return $bookmark->users->value() >= $query->users->value();
            });

        $config = new Configuration(Route::INDEX()->getUrl($request), Route::ATOM()->getUrl($request), Route::RSS()->getUrl($request));

        $feed = (new AtomGenerator)($bookmarks, $config);

        $response = $response
            ->withStatus(200)
            ->withHeader('Content-Type', self::CONTENT_TYPE);
        $response->getBody()->write($feed);

        return $response;
    }
}
