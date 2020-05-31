<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClient;
use Kawanamiyuu\HtbFeed\Feed\FeedGeneratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FeedResponseBuilder implements ResponseBuilderInterface
{
    /**
     * @var HtbClient
     */
    private $client;

    /**
     * @var FeedGeneratorInterface
     */
    private $feedGenerator;

    /**
     * @param HtbClient              $client
     * @param FeedGeneratorInterface $feedGenerator
     */
    public function __construct(HtbClient $client, FeedGeneratorInterface $feedGenerator)
    {
        $this->client = $client;
        $this->feedGenerator = $feedGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $query = (new QueryExtractor())($request);
        /* @var QueryExtractor $query */

        $bookmarks = $this->client
            ->fetch($query->category)
            ->filter(function (Bookmark $bookmark) use ($query) {
                return $bookmark->users->value() >= $query->users->value();
            });

        $feed = ($this->feedGenerator)($bookmarks);

        $response = $response
            ->withStatus(200)
            ->withHeader('Content-Type', $this->feedGenerator->getContentType());
        $response->getBody()->write($feed);

        return $response;
    }
}
