<?php

namespace Kawanamiyuu\HtbFeed;

use GuzzleHttp\Client;

class HtbClientFactory
{
    /**
     * @return HtbClient
     */
    public static function create(): HtbClient
    {
        $loader = new EntryListLoader(new Client);
        $extractor = new BookmarkExtractor;

        return new HtbClient($loader, $extractor);
    }
}
