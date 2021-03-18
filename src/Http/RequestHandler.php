<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Http;

use DateTime;
use DateTimeZone;
use K9u\RequestMapper\Annotation\GetMapping;
use K9u\RequestMapper\PathParams;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Kawanamiyuu\HtbFeed\Bookmark\HtbClient;
use Kawanamiyuu\HtbFeed\Bookmark\Users;
use Kawanamiyuu\HtbFeed\Feed\FeedMeta;
use Kawanamiyuu\HtbFeed\Feed\FeedType;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @SuppressWarnings("PMD.CouplingBetweenObjects") // FIXME
 */
class RequestHandler implements RequestHandlerInterface
{
    private ResponseFactoryInterface $responseFactory;

    private StreamFactoryInterface $streamFactory;

    private ValidatorInterface $validator;

    private HtbClient $htbClient;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        ValidatorInterface $validator,
        HtbClient $htbClient
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->validator = $validator;
        $this->htbClient = $htbClient;
    }

    /**
     * @GetMapping("/{feedType<(html|rss|atom)?>}")
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $pathParams = $request->getAttribute(PathParams::class);
        assert($pathParams instanceof PathParams);

        $queryParams = QueryParams::fromArray($request->getQueryParams());

        $violations = $this->validator->validate($queryParams);
        if (count($violations) > 0) {
            // FIXME: response error message
            error_log((string) $violations);
            return $this->responseFactory->createResponse()->withStatus(400);
        }

        $category = Category::valueOf($queryParams->category);

        $users = Users::valueOf($queryParams->users);

        $bookmarks = $this->htbClient
            ->fetch($category)
            ->filter(function (Bookmark $bookmark) use ($users) {
                return $bookmark->users->value() >= $users->value();
            });

        $feedMeta = new FeedMeta(
            new DateTime('now', new DateTimeZone('Asia/Tokyo')),
            (string) $request->getUri()->withPath(FeedType::HTML()->value()),
            (string) $request->getUri()->withPath(FeedType::ATOM()->value()),
            (string) $request->getUri()->withPath(FeedType::RSS()->value())
        );

        $feedType = FeedType::valueOf($pathParams['feedType'] ? $pathParams['feedType'] : 'html');
        $feedGenerator = $feedType->generator();

        $feed = $feedGenerator($feedMeta, $bookmarks);

        return $this->responseFactory->createResponse()
            ->withHeader('Content-Type', sprintf('%s; charset=UTF-8', $feed->contentType()))
            ->withBody($this->streamFactory->createStream($feed->content()));
    }
}
