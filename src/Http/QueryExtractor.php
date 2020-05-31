<?php

namespace Kawanamiyuu\HtbFeed\Http;

use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Kawanamiyuu\HtbFeed\Bookmark\Users;
use Psr\Http\Message\ServerRequestInterface;

class QueryExtractor
{
    /**
     * @var Category
     */
    public $category;

    /**
     * @var Users
     */
    public $users;

    /**
     * @param ServerRequestInterface $request
     *
     * @return QueryExtractor
     */
    public function __invoke(ServerRequestInterface $request): QueryExtractor
    {
        $category = $request->getQueryParams()['category'];
        $this->category = isset($category) ? Category::valueOf($category) : Category::ALL();

        $users = $request->getQueryParams()['users'];
        $this->users = isset($users) ? Users::valueOf($users) : Users::valueOf(100);

        return $this;
    }
}
