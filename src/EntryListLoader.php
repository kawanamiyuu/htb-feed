<?php

namespace Kawanamiyuu\HtbFeed;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class EntryListLoader
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param Category $category
     * @param int      $page
     *
     * @return PromiseInterface
     */
    public function __invoke(Category $category, int $page): PromiseInterface
    {
        $url = sprintf('http://b.hatena.ne.jp/entrylist/%s?page=%d', $category->value(), $page);

        return $this->client->requestAsync('GET', $url)->then(function (ResponseInterface $response) {
            return $response->getBody()->getContents();
        });
    }
}
