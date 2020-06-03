<?php

namespace Kawanamiyuu\HtbFeed\Bookmark;

use DateTimeInterface;

final class Bookmark
{
    /** @var Category */
    public $category;

    /** @var Users */
    public $users;

    /** @var string */
    public $title;

    /** @var string */
    public $url;

    /** @var string */
    public $domain;

    /** @var DateTimeInterface */
    public $date;
}
