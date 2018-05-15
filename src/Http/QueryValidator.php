<?php

namespace Kawanamiyuu\HtbFeed\Http;


use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Psr\Http\Message\ServerRequestInterface;

class QueryValidator
{
    /**
     * @var Category
     */
    public $category;

    /**
     * @var int
     */
    public $users;

    /**
     * @param ServerRequestInterface $request
     *
     * @return QueryValidator
     */
    public function __invoke(ServerRequestInterface $request): QueryValidator
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
            if (! ctype_digit($users)) {
                throw new \LogicException('"users" must be positive integer.');
            }
            $this->users = $users;
        } else {
            // default
            $this->users = 100;
        }

        return $this;
    }
}
