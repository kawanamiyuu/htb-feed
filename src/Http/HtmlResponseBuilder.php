<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClientFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class HtmlResponseBuilder implements ResponseBuilderInterface
{
    const TITLE = 'はてなブックマークの新着エントリー';

    const MAX_PAGE = 10;

    const CONTENT_TYPE = 'text/html; charset=UTF-8';

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (isset($request->getQueryParams()['category'])) {
            $category = Category::valueOf($request->getQueryParams()['category']);
        } else {
            $category = Category::ALL();
        }

        $minUsers = (int) ($request->getQueryParams()['users'] ?? '100');

        $bookmarks = HtbClientFactory::create()
            ->fetch($category, self::MAX_PAGE)
            ->filter(function (Bookmark $bookmark) use ($minUsers) {
                return $bookmark->users >= $minUsers;
            })
            ->sort(function (Bookmark $a, Bookmark $b) {
                // date DESC
                return $b->date < $a->date ? -1 : 1;
            });

        $feedUrl = $this->getFeedUrl($request->getUri());

        $html = $this->buildHtml($feedUrl, self::TITLE, $bookmarks);

        $response = $response->withHeader('Content-Type', self::CONTENT_TYPE);
        $response->getBody()->write($html);

        return $response;
    }

    /**
     * @param UriInterface $uri
     *
     * @return string
     */
    private function getFeedUrl(UriInterface $uri): string
    {
        $url = sprintf('%s://%s/atom?%s', $uri->getScheme(), $uri->getAuthority(), $uri->getQuery());
        // trim trailing '?' if query does not exist
        $url = rtrim($url, '?');

        return $url;
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
