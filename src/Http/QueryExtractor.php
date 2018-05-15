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
        if (isset($category)) {
            $this->category = Category::valueOf($category);
        } else {
            // default
            $this->category = Category::ALL();
        }

        $users = $request->getQueryParams()['users'];
        if (isset($users)) {
            $this->users = Users::valueOf($users);
        } else {
            // default
            $this->users = Users::valueOf(100);
        }

        return $this;
    }
}
