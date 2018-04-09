<?php

namespace Kawanamiyuu\HtbFeed;

use DateTime;
use Zend\Feed\Writer\Feed;

class AtomGenerator {

    private const FEED_TYPE = 'atom';

    /**
     * @var Bookmark[]
     */
    private $bookmarks;

    /**
     * @var string
     */
    private $feedUrl;

    /**
     * @var string
     */
    private $feedTitle;

    /**
     * @param Bookmark[] $bookmarks
     * @param string     $feedTitle
     * @param string     $feedUrl
     */
    public function __construct(array $bookmarks, string $feedTitle, string $feedUrl)
    {
        $this->bookmarks = $bookmarks;
        $this->feedTitle = $feedTitle;
        $this->feedUrl = $feedUrl;
    }

    public function __invoke(): string
    {
        $feed = new Feed;
        $feed->setTitle($this->feedTitle);
        $feed->setLink($this->feedUrl);
        $feed->setFeedLink($this->feedUrl, self::FEED_TYPE);
        // feed:updated
        $feed->setDateModified(new DateTime);
        // feed:author
//        $feed->addAuthor(['name'  => FEED_URL]);

        foreach ($this->bookmarks as $bookmark) {
            $entry = $feed->createEntry();
            $entry->setTitle($bookmark->title);
            $entry->setLink($bookmark->url);
            // atom:updated
            $entry->setDateModified($bookmark->date);
            // atom:summary
            $entry->setDescription($bookmark->title);
            // atom:author
//            $entry->addAuthor(['name' => $bookmark->domain]);
            // atom:published
//            $entry->setDateCreated($bookmark->date);
            // atom:content
//            $entry->setContent($bookmark->title);
            $feed->addEntry($entry);
        }

        return $feed->export(self::FEED_TYPE);
    }
}
