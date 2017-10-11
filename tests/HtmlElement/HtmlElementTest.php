<?php

namespace Andaniel05\ComposedViews\Tests\HtmlElement;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\HtmlElement\HtmlElement;

class HtmlElementTest extends TestCase
{
    public function setUp()
    {
        $this->element = new HtmlElement;
    }

    public function testGetTag_ReturnDivByDefault()
    {
        $this->assertEquals('div', $this->element->getTag());
    }

    public function testGetTag_ReturnTagArgument()
    {
        $tag = uniqid();
        $element = new HtmlElement($tag);

        $this->assertEquals($tag, $element->getTag());
    }

    public function testGetTag_ReturnInsertedValueBySetTag()
    {
        $tag = uniqid();
        $element = new HtmlElement;

        $element->setTag($tag);

        $this->assertEquals($tag, $element->getTag());
    }

    public function testGetAttributes_ReturnAnEmptyArrayByDefault()
    {
        $this->assertEquals([], $this->element->getAttributes());
    }

    public function testGetAttributes_ReturnAttributesArgument()
    {
        $attributes = range(0, rand(0, 10));

        $element = new HtmlElement('div', $attributes);

        $this->assertEquals($attributes, $element->getAttributes());
    }

    public function testGetAttributes_ReturnInsertedValueBySetAttributes()
    {
        $attributes = range(0, rand(0, 10));
        $element = new HtmlElement('div');

        $element->setAttributes($attributes);

        $this->assertEquals($attributes, $element->getAttributes());
    }

    public function testGetContent_ReturnNullByDefault()
    {
        $this->assertNull($this->element->getContent());
    }

    public function testGetContent_ReturnContentArgument()
    {
        $content = uniqid();
        $element = new HtmlElement('div', [], $content);

        $this->assertEquals($content, $element->getContent());
    }

    public function testGetContent_ReturnInsertedValueBySetContent()
    {
        $content = uniqid();
        $element = new HtmlElement('div', []);

        $element->setContent($content);

        $this->assertEquals($content, $element->getContent());
    }

    public function testGetEndTag_ReturnTrueByDefault()
    {
        $this->assertTrue($this->element->getEndTag());
    }

    public function endTagProvider()
    {
        return [
            [true],
            [false],
            [null],
        ];
    }

    /**
     * @dataProvider endTagProvider
     */
    public function testGetEndTag_ReturnEndTagArgument($endTag)
    {
        $element = new HtmlElement('div', [], null, $endTag);

        $this->assertEquals($endTag, $element->getEndTag());
    }

    /**
     * @dataProvider endTagProvider
     */
    public function testGetEndTag_ReturnInsertedValueBySetEndTag($endTag)
    {
        $this->element->setEndTag($endTag);

        $this->assertEquals($endTag, $this->element->getEndTag());
    }

    public function testAddAttribute()
    {
        $attr = uniqid();
        $value = uniqid();

        $this->element->addAttribute($attr, $value);

        $this->assertArraySubset(
            [$attr => $value], $this->element->getAttributes()
        );
    }

    /**
     * @depends testAddAttribute
     */
    public function testDeleteAttribute()
    {
        $attr = uniqid();
        $value = uniqid();
        $this->element->addAttribute($attr, $value);

        $this->element->deleteAttribute($attr);

        $this->assertEquals([], $this->element->getAttributes());
    }

    public function testHtml_DefaultCase()
    {
        $this->assertEquals('<div></div>', $this->element->html());
    }

    public function testHtml_RenderTheRightTagName()
    {
        $tag = uniqid();
        $element = new HtmlElement($tag);

        $html = $element->html();

        $this->assertStringStartsWith("<$tag>", $html);
        $this->assertStringEndsWith("</$tag>", $html);
    }

    public function providerHtml_RenderTheAttributesAndHisValues()
    {
        $attr1 = uniqid('attr1');
        $value1 = uniqid();

        $attr2 = uniqid('attr2');
        $value2 = uniqid();

        return [
            [
                "<div {$attr1}=\"{$value1}\"></div>",
                [$attr1 => $value1]
            ],
            [
                "<div {$attr1}=\"{$value1}\" {$attr2}=\"{$value2}\"></div>",
                [$attr1 => $value1, $attr2 => $value2]
            ],
        ];
    }

    /**
     * @dataProvider providerHtml_RenderTheAttributesAndHisValues
     */
    public function testHtml_RenderTheAttributesAndHisValues($expected, $attributes)
    {
        $this->element->setAttributes($attributes);

        $this->assertEquals($expected, $this->element->html());
    }
}