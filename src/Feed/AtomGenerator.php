<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use Laminas\Feed\Writer\Feed as LaminasFeed;

class AtomGenerator implements FeedGeneratorInterface
{
    public function __invoke(FeedMeta $meta, Bookmarks $bookmarks): Feed
    {
        // Atom の仕様
        // http://www.futomi.com/lecture/japanese/rfc4287.html

        $feed = new LaminasFeed();
        $feed->setTitle($meta->title());
        $feed->setFeedLink($meta->atomUrl(), 'atom');
        $feed->setLink($meta->htmlUrl());
        // feed:updated
        $feed->setDateModified($meta->publishedAt());

        foreach ($bookmarks as $bookmark) {
            $entry = $feed->createEntry();
            $entry->setTitle($bookmark->title);
            $entry->setLink($bookmark->url);
            // atom:updated
            $entry->setDateModified($bookmark->date);
            // atom:summary (optional)
            $entry->setDescription(sprintf(
                'ブクマ数: %s、カテゴリー: %s、発行元: %s',
                $bookmark->users->value(),
                $bookmark->category->label(),
                $bookmark->domain
            ));
            $feed->addEntry($entry);
        }

        $xml = $feed->export('atom');

        // NOTE: DOMElement の仕様で、"&" が自動的に
        //       HTML エスケープ ("&" -> "&amp;") されてしまうので "&" に戻す
        $xml = str_replace('&amp;', '&', $xml);

        return new Feed($xml, 'application/atom+xml');
    }
}
