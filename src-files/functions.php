<?php

namespace Kawanamiyuu\HtbFeed;

/**
 * @param array $vars
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
