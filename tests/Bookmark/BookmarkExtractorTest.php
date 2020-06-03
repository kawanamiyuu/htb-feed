<?php

declare(strict_types=1);

namespace Bookmark;

use DateTime;
use Kawanamiyuu\HtbFeed\Bookmark\BookmarkExtractor;
use Kawanamiyuu\HtbFeed\Bookmark\Category;
use PHPUnit\Framework\TestCase;

class BookmarkExtractorTest extends TestCase
{

    public function testInvoke(): void
    {
        $html = <<<HTML
<ul class="entrylist-item">
<li class="entrylist-image-entry">
  <div class="entrylist-contents">
    <div class="entrylist-contents-main">
      <h3 class="entrylist-contents-title">
        <a href="http://entry.example.com" title="entry title">dummy title</a>
      </h3>
      <span class="entrylist-contents-users">
        <a><span>10</span> users</a>
      </span>
      <p class="entrylist-contents-domain">
        <a>
          <img src="dummy favicon">
          <span>entry.example.com</span>
        </a>
      </p>
      <div class="entrylist-contents-body">
        <a>
          <p class="entrylist-contents-description">dummy description</p>
          <p class="entrylist-contents-thumb">
            <span style="background-image:url('dummy url');"></span>
          </p>
        </a>
      </div>
      <ul class="entrylist-contents-meta">
        <li class="entrylist-contents-category">
          <a>テクノロジー</a>
        </li>
        <li class="entrylist-contents-date">2020/06/01 12:00</li>
      </ul>
    </div>
  </div>
</li>
</ul>
HTML;

        $bookmarks = (new BookmarkExtractor())($html)->toArray();

        $this->assertCount(1, $bookmarks);
        $this->assertSame(Category::IT()->value(), $bookmarks[0]->category->value());
        $this->assertSame(10, $bookmarks[0]->users->value());
        $this->assertSame('entry title', $bookmarks[0]->title);
        $this->assertSame('http://entry.example.com', $bookmarks[0]->url);
        $this->assertSame('entry.example.com', $bookmarks[0]->domain);
        $this->assertSame((new DateTime('2020-06-01T12:00+09:00'))->getTimestamp(), $bookmarks[0]->date->getTimestamp());
    }
}
