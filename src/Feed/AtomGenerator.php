<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use DateTime;
use DateTimeZone;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use Laminas\Feed\Writer\Feed;

class AtomGenerator implements FeedGeneratorInterface
{
    /**
     * @var FeedMeta
     */
    private $meta;

    /**
     * @param FeedMeta $meta
     */
    public function __construct(FeedMeta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Bookmarks $bookmarks): string
    {
        // Atom の仕様
        // http://www.futomi.com/lecture/japanese/rfc4287.html

        $feed = new Feed();
        $feed->setTitle($this->meta->title());
        $feed->setFeedLink($this->meta->atomUrl(), 'atom');
        $feed->setLink($this->meta->htmlUrl());
        // feed:updated
        $feed->setDateModified(new DateTime('now', new DateTimeZone('Asia/Tokyo')));

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

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType(): string
    {
        return 'application/atom+xml; charset=UTF-8';
    }
}
