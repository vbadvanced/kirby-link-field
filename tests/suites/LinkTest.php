<?php

namespace Oblik\LinkField;

use Kirby\Cms\Field;
use Oblik\LinkField\Link;
use PHPUnit\Framework\TestCase;

final class LinkTest extends TestCase
{
    public function testParsesData()
    {
        $field = new Field(null, 'link', "type: url\nvalue: https://example.com");
        $link = $field->toLinkObject();
        $this->assertEquals('https://example.com', $link->url());
    }

    public function testHandlesBadFormatting()
    {
        $field = new Field(null, 'link', 'https://example.com');
        $link = $field->toLinkObject();
        $this->assertEquals('https://example.com', $link->url());
    }

    public function testOutputsAttributes()
    {
        $link = new Link([
            'type' => 'url',
            'value' => 'https://example.com',
            'popup' => true
        ]);

        $this->assertEquals(
            'href="https://example.com" target="_blank" testattr="test"',
            $link->attr(['testAttr' => 'test'])
        );
    }

    public function testLinkTitles()
    {
        $pageLink = site()->pageLink()->toLinkObject();
        $fileLink = site()->fileLink()->toLinkObject();

        $this->assertEquals(
            'Home',
            $pageLink->title()
        );

        $this->assertEquals(
            'test.png',
            $fileLink->title()
        );
    }

    public function testCreatesTag()
    {
        $link = new Link([
            'type' => 'url',
            'value' => 'https://example.com',
            'text' => 'my text',
            'popup' => true,
            'fragment' => 'foo'
        ]);

        $this->assertEquals(
            '<a href="https://example.com" rel="noopener noreferrer" target="_blank" testattr="test">my text</a>',
            $link->tag(['testAttr' => 'test'])
        );
    }

    public function testTelLink()
    {
        $link = new Link([
            'type' => 'tel',
            'value' => '123 / 456 . 789'
        ]);

        $this->assertEquals(
            '<a href="tel:123456789">123 / 456 . 789</a>',
            $link->tag()
        );
    }

    public function testMailLink()
    {
        $link = new Link([
            'type' => 'email',
            'value' => 'test@example.com'
        ]);

        $this->assertEquals(
            '<a href="mailto:test@example.com">test@example.com</a>',
            $link->tag()
        );
    }

    public function testCastToString()
    {
        $link = new Link([
            'type' => 'email',
            'value' => 'test@example.com'
        ]);

        $this->assertEquals(
            'mailto:test@example.com',
            (string)$link
        );
    }
}