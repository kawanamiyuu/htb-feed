<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed;

/**
 * @param array<string, mixed> $vars
 *
 * @return string
 */
function load_html_template(array $vars): string
{
    extract($vars);
    unset($vars);

    ob_start();
    require __DIR__ . '/html.php';

    return ob_get_clean();
}
