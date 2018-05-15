<?php

namespace Kawanamiyuu\HtbFeed\Feed;

use DateTime;
use DateTimeZone;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use Zend\Feed\Writer\Feed;

class AtomGenerator
{
    private const FEED_TYPE = 'atom';

    /**
     * @param Bookmarks $bookmarks
     * @param string    $feedTitle
     * @param string    $feedUrl
     * @param string    $htmlUrl
     *
     * @return string
     */
    public function __invoke(Bookmarks $bookmarks, string $feedTitle, string $feedUrl, string $htmlUrl): string
    {
        $feed = new Feed;
        $feed->setTitle($feedTitle);
        $feed->setFeedLink($feedUrl, self::FEED_TYPE);
        $feed->setLink($htmlUrl);
        // feed:updated
        $feed->setDateModified(new DateTime('now', new DateTimeZone('Asia/Tokyo')));
        // feed:author
//        $feed->addAuthor(['name'  => FEED_URL]);

        foreach ($bookmarks as $bookmark) {
            $entry = $feed->createEntry();
            $entry->setTitle($bookmark->title);
            $entry->setLink($bookmark->url);
            // atom:updated
            $entry->setDateModified($bookmark->date);
            // atom:summary
            $entry->setDescription(sprintf('ブクマ数: %s、カテゴリー: %s、発行元: %s',
                $bookmark->users->value(), $bookmark->category, $bookmark->domain));
            // atom:author
//            $entry->addAuthor(['name' => $bookmark->domain]);
            // atom:published
//            $entry->setDateCreated($bookmark->date);
            // atom:content
//            $entry->setContent($bookmark->title);
            $feed->addEntry($entry);
        }

        $xml = $feed->export(self::FEED_TYPE);

        // NOTE: DOMElement::setAttribute の仕様で、属性値 (リンク URL) 文字列中の "&" が
        //       自動的に HTML エスケープ ("&" -> "&amp;") されてしまうので "&" に戻す
        $xml = preg_replace_callback('/href="[^"]+"/', function ($matches) {
            return str_replace('&amp;', '&', $matches[0]);
        }, $xml);

        return $xml;
    }
}
