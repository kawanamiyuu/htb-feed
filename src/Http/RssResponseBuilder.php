<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClient;
use Kawanamiyuu\HtbFeed\Feed\RssGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RssResponseBuilder implements ResponseBuilderInterface
{
    const CONTENT_TYPE = 'application/rss+xml; charset=UTF-8';

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

        $feedUrl = (string) $request->getUri();
        $htmlUrl = Route::INDEX()->getUrl($request);

        $feed = (new RssGenerator)($bookmarks, $feedUrl, $htmlUrl);

        $response = $response
            ->withStatus(200)
            ->withHeader('Content-Type', self::CONTENT_TYPE);
        $response->getBody()->write($feed);

        return $response;
    }
}
