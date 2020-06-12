<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Bookmark;

use DateTimeInterface;

final class Bookmark
{
    public Category $category;

    public Users $users;

    public string $title;

    public string $url;

    public string $domain;

    public DateTimeInterface $date;
}
