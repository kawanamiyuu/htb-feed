<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClientFactory;
use Kawanamiyuu\HtbFeed\Feed\AtomGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AtomResponseBuilder implements ResponseBuilderInterface
{
    const TITLE = 'はてなブックマークの新着エントリー';

    const MAX_PAGE = 10;

    const CONTENT_TYPE = 'application/atom+xml; charset=UTF-8';

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

        $feedUrl = (string) $request->getUri();
        $htmlUrl = Route::INDEX()->getUrl($request);

        $feed = (new AtomGenerator)($bookmarks, self::TITLE, $feedUrl, $htmlUrl);

        $response = $response
            ->withStatus(200)
            ->withHeader('Content-Type', self::CONTENT_TYPE);
        $response->getBody()->write($feed);

        return $response;
    }
}
