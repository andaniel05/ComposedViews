<?php

namespace Andaniel05\ComposedViews\Tests\HtmlElement;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\HtmlElement\{HtmlElement, HtmlElementInterface};

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

    public function testGetAttribute_ReturnNullWhenAttributeDoNotExists()
    {
        $this->assertNull($this->element->getAttribute('attribute1'));
    }

    public function testGetAttribute_ReturnInsertedValueBySetAttribute()
    {
        $attribute = uniqid();
        $value = uniqid();
        $element = new HtmlElement;

        $element->setAttribute($attribute, $value);

        $this->assertEquals($value, $element->getAttribute($attribute));
    }

    public function testGetContent_ReturnAnEmptyArrayByDefault()
    {
        $this->assertEquals([], $this->element->getContent());
    }

    public function testGetContent_ReturnContentArgument()
    {
        $content = range(0, rand(0, 10));
        $element = new HtmlElement('div', [], $content);

        $this->assertEquals($content, $element->getContent());
    }

    public function testGetContent_ReturnInsertedValueBySetContent()
    {
        $content = range(0, rand(0, 10));
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
        $element = new HtmlElement('div', [], [], $endTag);

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

    public function testSetAttribute()
    {
        $attr = uniqid();
        $value = uniqid();

        $this->element->setAttribute($attr, $value);

        $this->assertArraySubset(
            [$attr => $value], $this->element->getAttributes()
        );
    }

    /**
     * @depends testSetAttribute
     */
    public function testDeleteAttribute()
    {
        $attr = uniqid();
        $value = uniqid();
        $this->element->setAttribute($attr, $value);

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

    public function testHtml_RenderTheStringContentType()
    {
        $content = uniqid();
        $this->element->setContent([$content]);

        $this->assertEquals("<div>{$content}</div>", $this->element->html());
    }

    public function testHtml_RenderTheHtmlElementContentType()
    {
        $elemHtml = uniqid();
        $elem = $this->createMock(HtmlElementInterface::class);
        $elem->method('html')->willReturn($elemHtml);

        $this->element->setContent([$elem]);

        $this->assertEquals("<div>{$elemHtml}</div>", $this->element->html());
    }

    public function testHtml_RenderAllTheContentType()
    {
        $elemHtml = uniqid();
        $elem = $this->createMock(HtmlElementInterface::class);
        $elem->method('html')->willReturn($elemHtml);

        $content1 = uniqid();

        $this->element->setContent([$content1, $elem]);

        $this->assertEquals("<div>{$content1}{$elemHtml}</div>", $this->element->html());
    }

    public function testHtml_DoNotRenderTheEndTagWhenEndTagIsFalse()
    {
        $this->element->setEndTag(false);

        $this->assertEquals("<div>", $this->element->html());
    }

    public function testHtml_DoNotRenderInLineEndTagWhenEndTagIsNull()
    {
        $this->element->setEndTag(null);

        $this->assertEquals("<div />", $this->element->html());
    }

    public function testAddContent()
    {
        $content = uniqid();

        $this->element->addContent($content);

        $this->assertArraySubset([$content], $this->element->getContent());
    }

    /**
     * @depends testAddContent
     */
    public function testDeleteContent()
    {
        $content = uniqid();
        $this->element->addContent($content);

        $this->element->deleteContent(0);

        $this->assertEquals([], $this->element->getContent());
    }
}