<?php

namespace Kawanamiyuu\HtbFeed\Bookmark;

final class Bookmark
{
    /** @var string */
    public $category;

    /** @var Users */
    public $users;

    /** @var string */
    public $title;

    /** @var string */
    public $url;

    /** @var string */
    public $domain;

    /** @var \DateTime */
    public $date;
}
