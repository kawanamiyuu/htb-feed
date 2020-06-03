<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Feed;

use DateTime;
use DateTimeZone;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Kawanamiyuu\HtbFeed\Bookmark\Users;
use PHPUnit\Framework\TestCase;

class AtomGeneratorTest extends TestCase
{
    /**
     * @var FeedGeneratorInterface
     */
    private $generator;

    public function setUp(): void
    {
        $config = new Configuration(
            'http://example.com?category=it&users=10',
            'http://example.com/atom?category=it&users=10',
            'http://example.com/rss?category=it&users=10'
        );

        $this->generator = new AtomGenerator($config);
    }

    public function testInvoke()
    {
        $bookmark = new Bookmark();
        $bookmark->category = Category::IT();
        $bookmark->users = Users::valueOf(10);
        $bookmark->title = 'entry title';
        $bookmark->url = 'http://entry.example.com';
        $bookmark->domain = 'entry.example.com';
        $bookmark->date = new DateTime('2020-06-01T12:00+09:00', new DateTimeZone('Asia/Tokyo'));

        $feed = ($this->generator)(new Bookmarks([$bookmark]));

        $expected = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title type="text">はてなブックマークの新着エントリー</title>
  <updated>now</updated>
  <generator uri="http://framework.zend.com" version="2">Zend_Feed_Writer</generator>
  <link rel="alternate" type="text/html" href="http://example.com?category=it&users=10"/>
  <link rel="self" type="application/atom+xml" href="http://example.com/atom?category=it&users=10"/>
  <id>http://example.com?category=it&users=10</id>
  <entry>
    <title type="html"><![CDATA[entry title]]></title>
    <summary type="html"><![CDATA[ブクマ数: 10、カテゴリー: テクノロジー、発行元: entry.example.com]]></summary>
    <updated>2020-06-01T12:00:00+09:00</updated>
    <link rel="alternate" type="text/html" href="http://entry.example.com"/>
    <id>http://entry.example.com</id>
  </entry>
</feed>
FEED;

        $this->assertSame(
            trim($expected),
            preg_replace('#<updated>[^<]+</updated>#', '<updated>now</updated>', trim($feed), 1)
        );
    }

    public function testGetContentType()
    {
        $this->assertSame('application/atom+xml; charset=UTF-8', $this->generator->getContentType());
    }
}
