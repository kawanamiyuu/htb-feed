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

class RssGeneratorTest extends TestCase
{
    /**
     * @var FeedGeneratorInterface
     */
    private $generator;

    public function setUp(): void
    {
        $meta = new FeedMeta(
            'http://example.com?category=it&users=10',
            'http://example.com/atom?category=it&users=10',
            'http://example.com/rss?category=it&users=10'
        );

        $this->generator = new RssGenerator($meta);
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
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
  <channel>
    <title>はてなブックマークの新着エントリー</title>
    <description>はてなブックマークの新着エントリー</description>
    <pubDate>now</pubDate>
    <generator>Laminas_Feed_Writer 2 (https://getlaminas.org)</generator>
    <link>http://example.com?category=it&users=10</link>
    <atom:link rel="self" type="application/rss+xml" href="http://example.com/rss?category=it&users=10"/>
    <item>
      <title>entry title</title>
      <description><![CDATA[ブクマ数: 10、カテゴリー: テクノロジー、発行元: entry.example.com]]></description>
      <pubDate>Mon, 01 Jun 2020 12:00:00 +0900</pubDate>
      <link>http://entry.example.com</link>
      <guid>http://entry.example.com</guid>
      <slash:comments>0</slash:comments>
    </item>
  </channel>
</rss>
FEED;

        $this->assertSame(
            trim($expected),
            preg_replace('#<pubDate>[^<]+</pubDate>#', '<pubDate>now</pubDate>', trim($feed), 1)
        );
    }

    public function testGetContentType()
    {
        $this->assertSame('application/rss+xml; charset=UTF-8', $this->generator->getContentType());
    }
}
