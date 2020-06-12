<?php

namespace Kawanamiyuu\HtbFeed\Bookmark;

use LogicException;

final class Users
{
    private int $value;

    private function __construct(int $users)
    {
        $this->value = $users;
    }

    /**
     * @param int|string $users
     *
     * @return Users
     */
    public static function valueOf($users): Users
    {
        if (is_int($users) && $users >= 0) {
            return new self($users);
        }
        if (ctype_digit($users)) {
            return new self((int) $users);
        }

        throw new LogicException('"users" must be positive integer.');
    }

    public function value(): int
    {
        return $this->value;
    }
}
