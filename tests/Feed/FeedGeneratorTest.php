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

class FeedGeneratorTest extends TestCase
{
    private FeedMeta $feedMeta;

    private Bookmarks $bookmarks;

    protected function setUp(): void
    {
        $this->feedMeta = new FeedMeta(
            new DateTime('2020-06-02T09:00:00+09:00', new DateTimeZone('Asia/Tokyo')),
            'http://example.com?category=it&users=10',
            'http://example.com/atom?category=it&users=10',
            'http://example.com/rss?category=it&users=10'
        );

        $bookmark = new Bookmark();
        $bookmark->category = Category::IT();
        $bookmark->users = Users::valueOf(10);
        $bookmark->title = 'entry title';
        $bookmark->url = 'http://entry.example.com';
        $bookmark->domain = 'entry.example.com';
        $bookmark->date = new DateTime('2020-06-01T12:00+09:00', new DateTimeZone('Asia/Tokyo'));
        $this->bookmarks = new Bookmarks([$bookmark]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testInvoke(FeedType $feedType, string $contentType, string $content): void
    {
        $feedGenerator = $feedType->generator();
        $feed = $feedGenerator($this->feedMeta, $this->bookmarks);

        $this->assertSame($contentType, $feed->contentType());
        $this->assertSame(trim($content), trim($feed->content()));
    }

    public function dataProvider(): iterable
    {
        yield [
            FeedType::HTML(),
            'text/html',
            <<<FEED
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>はてなブックマークの新着エントリー</title>
    <link rel="alternate" type="application/atom+xml" href="http://example.com/atom?category=it&users=10" title="はてなブックマークの新着エントリー" />
    <link rel="alternate" type="application/rss+xml" href="http://example.com/rss?category=it&users=10" title="はてなブックマークの新着エントリー" />
</head>
<body>

<ul>
        <li>
        <a href="http://entry.example.com" target="_blank">
            entry title        </a>
    </li>
    </ul>

</body>
</html>
FEED
        ];

        yield [
            FeedType::ATOM(),
            'application/atom+xml',
            <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title type="text">はてなブックマークの新着エントリー</title>
  <updated>2020-06-02T09:00:00+09:00</updated>
  <generator uri="https://getlaminas.org" version="2">Laminas_Feed_Writer</generator>
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
FEED
        ];

        yield [
            FeedType::RSS(),
            'application/rss+xml',
            <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
  <channel>
    <title>はてなブックマークの新着エントリー</title>
    <description>はてなブックマークの新着エントリー</description>
    <pubDate>Tue, 02 Jun 2020 09:00:00 +0900</pubDate>
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
FEED
        ];
    }
}
