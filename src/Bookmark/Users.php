<?php

namespace Kawanamiyuu\HtbFeed\Bookmark;

use LogicException;

final class Users
{
    /**
     * @var int
     */
    private $value;

    /**
     * @param int $users
     */
    private function __construct(int $users)
    {
        $this->value = $users;
    }

    /**
     * @param mixed $users
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

    /**
     * @return int
     */
    public function value(): int
    {
        return $this->value;
    }
}
