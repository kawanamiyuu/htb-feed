<?php

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Zend\Feed\Writer\Feed as ZendFeed;

require dirname(__DIR__) . '/vendor/autoload.php';

const CATEGORY = 'it';
const MAX_PAGE = 5;
const MIN_USERS = 50;
const FEED_TITLE = 'はてなブックマークの新着エントリー';
const FEED_URL = 'http://www.example.com/atom';

final class Bookmark {
    /** @var string */
    public $category;
    /** @var int */
    public $users;
    /** @var string */
    public $title;
    /** @var string */
    public $url;
    /** @var string */
    public $domain;
    /** @var \DateTime */
    public $date;
}

final class BookmarkExtractor {

    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct(string $category, int $page = 1)
    {
        $url = sprintf('http://b.hatena.ne.jp/entrylist/%s?page=%d', $category, $page);
        $this->crawler = $crawler = (new Client)->request('GET', $url);
    }

    /**
     * @return Bookmark[]
     */
    public function __invoke(): array
    {
        /* @var Bookmark[] $bookmarks */
        $bookmarks = [];

        $this->crawler->filter('.entrylist-item .entrylist-image-entry')->each(function (Crawler $node) use (&$bookmarks) {
            $bookmark = new Bookmark;
            $bookmark->category = $node->filter('.entrylist-contents-category a')->text();
            $bookmark->users = (int) $node->filter('.entrylist-contents-users span')->text();
            $bookmark->title = $node->filter('.entrylist-contents-title a')->attr('title');
            $bookmark->url = $node->filter('.entrylist-contents-title a')->attr('href');
            $bookmark->domain = $node->filter('.entrylist-contents-domain a span')->text();
            $bookmark->date = new DateTime($node->filter('.entrylist-contents-date')->text(), new DateTimeZone('Asia/Tokyo'));
            $bookmarks[] = $bookmark;
        });

        return $bookmarks;
    }
}

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
        $feed = new ZendFeed;
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

/* @var Bookmark[] $bookmarks */
$bookmarks = [];
foreach (range(1, MAX_PAGE) as $page) {
    $extractor = new BookmarkExtractor(CATEGORY, $page);
    $bookmarks = array_merge($bookmarks, array_filter($extractor(), function (Bookmark $bookmark) {
        return $bookmark->users >= MIN_USERS;
    }));
}

$generator = new AtomGenerator($bookmarks, FEED_TITLE, FEED_URL);
echo  $generator() . "\n";
