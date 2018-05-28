<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use DateTime;
use DateTimeZone;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use Zend\Feed\Writer\Feed;

class RssGenerator implements FeedGeneratorInterface
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Bookmarks $bookmarks): string
    {
        // RSS 2.0 の仕様
        // http://www.futomi.com/lecture/japanese/rss20.html#hrelementsOfLtitemgt

        $feed = new Feed();
        $feed->setTitle($this->config->title());
        $feed->setFeedLink($this->config->rssUrl(), 'rss');
        $feed->setLink($this->config->htmlUrl());
        $feed->setDescription($this->config->title());
        // channel:pubDate (optional)
        $feed->setDateModified(new DateTime('now', new DateTimeZone('Asia/Tokyo')));

        foreach ($bookmarks as $bookmark) {
            $entry = $feed->createEntry();
            $entry->setTitle($bookmark->title);
            $entry->setLink($bookmark->url);
            // item:pubDate (optional)
            $entry->setDateModified($bookmark->date);
            // item:description (optional)
            $entry->setDescription(sprintf(
                'ブクマ数: %s、カテゴリー: %s、発行元: %s',
                $bookmark->users->value(),
                $bookmark->category->label(),
                $bookmark->domain
            ));
            $feed->addEntry($entry);
        }

        $xml = $feed->export('rss');

        // NOTE: DOMElement の仕様で、"&" が自動的に
        //       HTML エスケープ ("&" -> "&amp;") されてしまうので "&" に戻す
        $xml = str_replace('&amp;', '&', $xml);

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType(): string
    {
        return 'application/rss+xml; charset=UTF-8';
    }
}
