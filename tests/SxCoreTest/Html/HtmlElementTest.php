<?php

namespace SxCoreTest\Html;

use PHPUnit_Framework_TestCase;
use SxCore\Html\HtmlElement;

class HtmlElementTest extends PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $htmlElement = new HtmlElement;

        $this->assertInstanceOf('\SxCore\Html\HtmlElement', $htmlElement);
        $this->assertEquals('<div></div>', $htmlElement->render());
        $this->assertEquals('<div></div>', $htmlElement);

        $htmlElementSpan = new HtmlElement('span');

        $this->assertEquals('<span></span>', $htmlElementSpan->render());
        $this->assertEquals('<span></span>', $htmlElementSpan);
    }

    public function testSetAppendContent()
    {

        $htmlElement = new HtmlElement;

        $htmlElement->setAppendContent();
        $htmlElement->spawnChild('p');
        $htmlElement->setContent('Bacon');

        $this->assertEquals('<div><p></p>Bacon</div>', $htmlElement->render());
        $this->assertNotEquals($htmlElement->render(), '<div>Bacon<p></p></div>');
    }

    public function testSetPrependContent()
    {

        $htmlElement = new HtmlElement;

        $htmlElement->setPrependContent();
        $htmlElement->spawnChild('p');
        $htmlElement->setContent('Bacon');

        $this->assertNotEquals($htmlElement->render(), '<div><p></p>Bacon</div>');
        $this->assertEquals('<div>Bacon<p></p></div>', $htmlElement->render());
    }

    public function testSetAttributes()
    {
        $htmlElement = new HtmlElement;
        $attributes  = array(
            'class' => 'you-know-it',
            'style' => 'Fo sho',
        );

        $htmlElement->setAttributes($attributes);
        $this->assertEquals('<div class="you-know-it" style="Fo sho"></div>', $htmlElement->render());
        $htmlElement->addAttribute('my', 'foo');
        $htmlElement->setAttributes($attributes);
        $this->assertEquals('<div class="you-know-it" style="Fo sho"></div>', $htmlElement->render());
    }

    /**
     * @expectedException \SxCore\Html\Exception\InvalidArgumentException
     */
    public function testAddAttributeInvalidTypeKey()
    {
        $htmlElement = new HtmlElement;

        $htmlElement->addAttribute(123, 'abc');
    }

    /**
     * @expectedException \SxCore\Html\Exception\InvalidArgumentException
     */
    public function testAddAttributeInvalidTypeValue()
    {
        $htmlElement = new HtmlElement;

        $htmlElement->addAttribute('abc', new \StdClass);
    }

    public function testAddAttributes()
    {
        $htmlElement = new HtmlElement;
        $attributes  = array(
            'class' => 'you-know-it',
            'style' => 'Fo sho',
        );

        $htmlElement->setAttributes($attributes);
        $htmlElement->addAttributes(array(
            'my'   => 'foo',
            'your' => 'bar',
        ));
        $this->assertEquals('<div class="you-know-it" style="Fo sho" my="foo" your="bar"></div>', $htmlElement->render());
    }

    public function testAddAttribute()
    {
        $htmlElement = new HtmlElement;
        $attributes  = array(
            'class' => 'you-know-it',
            'style' => 'Fo sho',
        );

        $htmlElement->setAttributes($attributes);
        $htmlElement->addAttribute('my', 'foo');
        $this->assertEquals('<div class="you-know-it" style="Fo sho" my="foo"></div>', $htmlElement->render());
    }

    public function testRemoveAttribute()
    {
        $htmlElement = new HtmlElement;
        $attributes  = array(
            'my'   => 'foo',
            'your' => 'bar',
            'our'  => 'baz',
        );

        $htmlElement->setAttributes($attributes);
        $htmlElement->removeAttribute('your');

        $this->assertEquals('<div my="foo" our="baz"></div>', $htmlElement->render());
    }

    public function testGetAttributes()
    {
        $htmlElement = new HtmlElement;
        $attributes  = array(
            'my'   => 'foo',
            'your' => 'bar',
            'our'  => 'baz',
        );

        $htmlElement->setAttributes($attributes);
        $htmlElement->addAttribute('gimme', 'bacon');

        $this->assertEquals(array_merge($attributes, array('gimme' => 'bacon')), $htmlElement->getAttributes());
    }

    public function testAddClass()
    {
        $htmlElement = new HtmlElement;

        $htmlElement->addClass('bacon');
        $this->assertEquals('<div class="bacon"></div>', $htmlElement->render());
        $htmlElement->addClass('Nugget');
        $this->assertEquals('<div class="bacon Nugget"></div>', $htmlElement->render());
    }

    public function testSetContent()
    {
        $htmlElement = new HtmlElement;

        $htmlElement->setContent('Nugget biscuit, nugget in a biscuit. Dip it all in mashed potatoes.');

        $this->assertEquals(
            '<div>Nugget biscuit, nugget in a biscuit. Dip it all in mashed potatoes.</div>',
            $htmlElement->render()
        );
    }

    public function testAppendContent()
    {
        $htmlElement = new HtmlElement;

        $htmlElement->setContent('Knock knock. ');
        $this->assertEquals('<div>Knock knock. </div>', $htmlElement->render());
        $htmlElement->appendContent('Go away.');
        $this->assertEquals('<div>Knock knock. Go away.</div>', $htmlElement->render());
    }

    public function testPrependContent()
    {
        $htmlElement = new HtmlElement;

        $htmlElement->setContent('But I did not even knock!');
        $this->assertEquals('<div>But I did not even knock!</div>', $htmlElement->render());
        $htmlElement->prependContent('Go away. ');
        $this->assertEquals('<div>Go away. But I did not even knock!</div>', $htmlElement->render());
    }

    public function testRemoveContent()
    {
        $htmlElement = new HtmlElement;

        $htmlElement->setContent('Where did you go?');
        $htmlElement->removeContent();

        $this->assertEmpty($htmlElement->getContent());
        $this->assertEquals('<div></div>', $htmlElement->render());
    }

    public function testGetContent()
    {
        $htmlElement = new HtmlElement;

        $htmlElement->setContent('Where did you go?');

        $this->assertEquals('Where did you go?', $htmlElement->getContent());
    }

    public function testSpawnChild()
    {
        $htmlElement  = new HtmlElement;
        $childElement = $htmlElement->spawnChild('video');

        $this->assertInstanceOf('\SxCore\Html\HtmlElement', $childElement);
        $this->assertEquals('<video></video>', $childElement->render());
    }

    public function testAddChild()
    {
        $htmlElement  = new HtmlElement;
        $childElement = new HtmlElement('section');

        $htmlElement->addChild($childElement);
        $this->assertEquals('<section></section>', $childElement->render());
    }

    public function testAddChildren()
    {
        $htmlElement   = new HtmlElement;
        $childElements = array(
            new HtmlElement('bacon'),
            new HtmlElement('foo'),
            new HtmlElement('bar'),
        );

        $htmlElement->addChildren($childElements);
        $this->assertEquals('<div><bacon></bacon><foo></foo><bar></bar></div>', $htmlElement->render());
    }

    public function testRemoveChildren()
    {
        $htmlElement   = new HtmlElement;
        $childElements = array(
            new HtmlElement('bacon'),
            new HtmlElement('foo'),
            new HtmlElement('bar'),
        );

        $htmlElement->addChildren($childElements);
        $htmlElement->removeChildren();
        $this->assertEquals('<div></div>', $htmlElement->render());
    }

    public function testSetChildren()
    {
        $htmlElement   = new HtmlElement;
        $childElements = array(
            new HtmlElement('bacon'),
            new HtmlElement('foo'),
            new HtmlElement('bar'),
        );

        $htmlElement->setChildren($childElements);
        $this->assertEquals('<div><bacon></bacon><foo></foo><bar></bar></div>', $htmlElement->render());
    }

    public function testHasChildren()
    {
        $htmlElement   = new HtmlElement;
        $childElements = array(
            new HtmlElement('bacon'),
            new HtmlElement('foo'),
            new HtmlElement('bar'),
        );

        $this->assertFalse($htmlElement->hasChildren());
        $htmlElement->setChildren($childElements);
        $this->assertTrue($htmlElement->hasChildren());
    }
}