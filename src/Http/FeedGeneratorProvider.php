<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Feed\FeedGeneratorInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\ProviderInterface;

class FeedGeneratorProvider implements ProviderInterface
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return FeedGeneratorInterface
     */
    public function get(): FeedGeneratorInterface
    {
        return (new FeedGeneratorFactory())->newInstance($this->request);
    }
}
