<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use DateTime;
use DateTimeZone;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use Zend\Feed\Writer\Feed;

class RssGenerator implements FeedGeneratorInterface
{
    private const FEED_TYPE = 'rss';

    /**
     * {@inheritdoc}
     */
    public function __invoke(Bookmarks $bookmarks, Configuration $config): string
    {
        // RSS 2.0 の仕様
        // http://www.futomi.com/lecture/japanese/rss20.html#hrelementsOfLtitemgt

        $feed = new Feed();
        $feed->setTitle($config->title());
        $feed->setFeedLink($config->rssUrl(), self::FEED_TYPE);
        $feed->setLink($config->htmlUrl());
        $feed->setDescription($config->title());
        // channel:pubDate (optional)
        $feed->setDateModified(new DateTime('now', new DateTimeZone('Asia/Tokyo')));

        foreach ($bookmarks as $bookmark) {
            $entry = $feed->createEntry();
            $entry->setTitle($bookmark->title);
            $entry->setLink($bookmark->url);
            // item:pubDate (optional)
            $entry->setDateModified($bookmark->date);
            // item:description (optional)
            $entry->setDescription(sprintf('ブクマ数: %s、カテゴリー: %s、発行元: %s',
                $bookmark->users->value(), $bookmark->category->label(), $bookmark->domain));
            $feed->addEntry($entry);
        }

        $xml = $feed->export(self::FEED_TYPE);

        // NOTE: DOMElement の仕様で、"&" が自動的に
        //       HTML エスケープ ("&" -> "&amp;") されてしまうので "&" に戻す
        $xml = str_replace('&amp;', '&', $xml);

        return $xml;
    }
}
