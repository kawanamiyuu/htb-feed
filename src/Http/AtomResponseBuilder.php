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

    const CONTENT_TYPE = 'application/atom+xml; charset=UTF-8';

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $query = (new QueryValidator)($request);
        /* @var QueryValidator $query */

        $bookmarks = HtbClientFactory::create()
            ->fetch($query->category)
            ->filter(function (Bookmark $bookmark) use ($query) {
                return $bookmark->users >= $query->users;
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
