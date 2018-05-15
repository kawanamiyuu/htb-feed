<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HtmlResponseBuilder implements ResponseBuilderInterface
{
    const TITLE = 'はてなブックマークの新着エントリー';

    const CONTENT_TYPE = 'text/html; charset=UTF-8';

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

        $feedUrl = Route::ATOM()->getUrl($request);

        $html = $this->buildHtml($feedUrl, self::TITLE, $bookmarks);

        $response = $response
            ->withStatus(200)
            ->withHeader('Content-Type', self::CONTENT_TYPE);
        $response->getBody()->write($html);

        return $response;
    }

    /**
     * @param string    $feedUrl
     * @param string    $title
     * @param Bookmarks $bookmarks
     *
     * @return string
     */
    private function buildHtml(string $feedUrl, string $title, Bookmarks $bookmarks): string
    {
        ob_start();
        require dirname(__DIR__, 2) . '/src-files/html.php';
        return ob_get_clean();
    }
}
