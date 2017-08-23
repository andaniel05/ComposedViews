<?php

namespace PlatformPHP\ComposedViews\Tests;

use PlatformPHP\ComposedViews\{AbstractPage, PageEvents};
use PlatformPHP\ComposedViews\Event\{BeforeInsertionEvent, AfterInsertionEvent,
    BeforeDeletionEvent, AfterDeletionEvent};
use PlatformPHP\ComposedViews\Component\AbstractComponent;
use PHPUnit\Framework\TestCase;

class AbstractComponentTest extends TestCase
{
    public function setUp()
    {
        $this->component = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component'])
            ->getMockForAbstractClass();
    }

    public function getTestClass(): string
    {
        return AbstractComponent::class;
    }

    public function provider1()
    {
        return [ ['component1'], ['component2'] ];
    }

    /**
     * @dataProvider provider1
     */
    public function testArgumentGetters($id)
    {
        $component = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs([$id])
            ->getMockForAbstractClass();

        $this->assertEquals($id, $component->getId());
    }

    public function testGetParentReturnNullByDefault()
    {
        $this->assertNull($this->component->getParent());
    }

    public function setParentInComponent()
    {
        $this->parent = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['parent'])
            ->getMockForAbstractClass();

        $this->component->setParent($this->parent);
    }

    public function testGetParentReturnTheInsertedComponentBySetParent()
    {
        $this->setParentInComponent();

        $this->assertSame($this->parent, $this->component->getParent());
    }

    public function provider2()
    {
        return [
            ['child1', 'child2']
        ];
    }

    /**
     * @dataProvider provider2
     */
    public function testDetachInvokeDropComponentInTheParent($childId)
    {
        // Arrange
        //

        $parent = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['parent'])
            ->setMethods(['dropComponent'])
            ->getMockForAbstractClass();
        $parent->expects($this->once())
            ->method('dropComponent')
            ->with($this->equalTo($childId));

        $child = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs([$childId])
            ->getMockForAbstractClass();

        $parent->addComponent($child);

        // Act
        $child->detach();
    }

    public function testDetachDoNotNothingWhenParentIsNull()
    {
        $this->component->detach();

        $this->assertTrue(true);
    }

    public function testGetPage_ReturnNullByDefault()
    {
        $this->assertNull($this->component->getPage());
    }

    public function testChildrenHtml()
    {
        $component1Html = uniqid();
        $component1 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component1'])
            ->setMethods(['html'])
            ->getMockForAbstractClass();
        $component1->method('html')->willReturn($component1Html);

        $component2 = $this->getMockForAbstractClass(
            AbstractComponent::class, ['component2']
        );

        $component2->addComponent($component1);

            $expected = <<<HTML
<div class="cv-component cv-component1" id="cv-component1">
    $component1Html
</div>
HTML;

        $this->assertXmlStringEqualsXmlString($expected, $component2->childrenHtml());
    }





    public function getComponentMock(string $id = 'component')
    {
        $component = $this->getMockForAbstractClass(AbstractComponent::class, [$id]);

        return $component;
    }

    public function testGetAllComponentsReturnAnEmptyArrayByDefault()
    {
        $component = $this->getComponentMock();

        $this->assertEquals([], $component->getAllComponents());
    }

    public function testGetComponentReturnNullIfComponentNotExists()
    {
        $component = $this->getComponentMock();

        $this->assertNull($component->getComponent('component1'));
    }

    public function insertTwoComponents()
    {
        $this->component1 = $this->createMock(AbstractComponent::class);
        $this->component1->method('getId')->willReturn('component1');
        $this->component2 = $this->createMock(AbstractComponent::class);
        $this->component2->method('getId')->willReturn('component2');

        $this->container = $this->getComponentMock();
        $this->container->addComponent($this->component1);
        $this->container->addComponent($this->component2);
    }

    public function testGetComponentReturnTheComponentIfExists()
    {
        $this->insertTwoComponents();

        $this->assertSame(
            $this->component1,
            $this->container->getComponent('component1')
        );
    }

    public function testGetAllComponentsReturnAnArrayWithAllInsertedComponents()
    {
        $this->insertTwoComponents();

        $expected = [
            'component1' => $this->component1,
            'component2' => $this->component2,
        ];

        $this->assertEquals($expected, $this->container->getAllComponents());
    }

    public function testDropComponentRemoveTheComponentWhenExists()
    {
        $this->insertTwoComponents();

        $this->container->dropComponent('component2');

        $this->assertEquals(
            ['component1' => $this->component1],
            $this->container->getAllComponents()
        );
    }

    public function testExistsComponentReturnFalseWhenComponentNotExists()
    {
        $this->insertTwoComponents();

        $this->assertFalse($this->container->existsComponent('component5'));
    }

    public function testExistsComponentReturnTrueWhenComponentExists()
    {
        $this->insertTwoComponents();

        $this->assertTrue($this->container->existsComponent('component1'));
        $this->assertTrue($this->container->existsComponent('component2'));
    }

    public function initializeNestedComponents()
    {
        $component = $this->getComponentMock();

        $component1 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component1'])
            ->getMockForAbstractClass();

        $component2 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component2'])
            ->getMockForAbstractClass();

        $component3 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component3'])
            ->getMockForAbstractClass();

        $component4 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component4'])
            ->getMockForAbstractClass();

        $component5 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component5'])
            ->getMockForAbstractClass();

        $component1->addComponent($component2);
        $component2->addComponent($component3);
        $component4->addComponent($component5);

        $component->addComponent($component1);
        $component->addComponent($component4);

        $this->container  = $component;
        $this->component1 = $component1;
        $this->component2 = $component2;
        $this->component3 = $component3;
        $this->component4 = $component4;
        $this->component5 = $component5;
    }

    public function testGetComponentSearchTheComponentInAllTheTree()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component2,
            $this->container->getComponent('component2')
        );
        $this->assertSame(
            $this->component3,
            $this->container->getComponent('component3')
        );
        $this->assertSame(
            $this->component5,
            $this->container->getComponent('component5')
        );
    }

    public function testGetComponentWhenIdIsComplex1()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component5,
            $this->container->getComponent('component4 component5')
        );
    }

    public function testGetComponentWhenIdIsComplex2()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component3,
            $this->container->getComponent('component1 component3')
        );
    }

    public function testGetComponentWhenIdIsComplex3()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component3,
            $this->container->getComponent('component1 component2 component3')
        );
    }

    public function testGetComponentWhenIdIsComplex4()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component3,
            $this->container->getComponent('component2 component3')
        );
    }

    public function testGetComponentWhenIdIsComplex5()
    {
        $this->initializeNestedComponents();

        $this->assertNull(
            $this->container->getComponent('component1 component5')
        );
    }

    public function insertChildComponent()
    {
        $this->container = $this->getComponentMock();
        $this->child = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['child'])
            ->getMockForAbstractClass();

        $this->container->addComponent($this->child);
    }

    public function testAddComponentRegisterToItSelfAsParentInTheChild()
    {
        if ($this->getTestClass() == ComponentContainerTrait::class) {
            $this->markTestSkipped();
        }

        $this->insertChildComponent();

        $this->assertSame($this->container, $this->child->getParent());
    }

    public function testDropComponentSetNullAsParentInTheChild()
    {
        $this->insertChildComponent();

        $this->container->dropComponent('child');

        $this->assertNull($this->child->getParent());
    }

    public function initialization1()
    {
        $this->child = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['child'])
            ->setMethods(['setParent'])
            ->getMockForAbstractClass();

        $this->container = $this->getComponentMock();
        $this->container->addComponent($this->child);
    }

    public function testDropComponentInvokeSetParentWithNullInTheChild()
    {
        $this->initialization1();

        $this->child->expects($this->once())
            ->method('setParent')
            ->with($this->equalTo(null));

        $this->container->dropComponent('child');
    }

    public function testDropComponentNotInvokeToSetParentWithNullInTheChildWhenNotifyChildArgumentIsFalse()
    {
        $this->initialization1();

        $this->child->expects($this->exactly(0))
            ->method('setParent');

        $this->container->dropComponent('child', false);
    }

    public function testGetPage_ReturnTheInsertedPage()
    {
        $page = $this->getMockForAbstractClass(AbstractPage::class);
        $component1 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component1'])
            ->getMockForAbstractClass();

        $component1->setPage($page);

        $this->assertSame($page, $component1->getPage());
    }

    public function testTheBeforeInsertionEventIsTriggeredOnThePageWhenANewComponentIsInserted()
    {
        $page = $this->getMockForAbstractClass(AbstractPage::class);
        $parent = $this->getMockForAbstractClass(AbstractComponent::class, ['parent']);
        $child = $this->getMockForAbstractClass(AbstractComponent::class, ['child']);

        $executed = false;
        $page->on(PageEvents::BEFORE_INSERTION, function (BeforeInsertionEvent $event) use (&$executed, $parent, $child) {
            $executed = true;
            $this->assertEquals($parent, $event->getParent());
            $this->assertEquals($child, $event->getChild());
            $this->assertFalse($parent->existsComponent('child'));
        });

        $parent->setPage($page);
        $parent->addComponent($child); // Act

        $this->assertTrue($executed);
        $this->assertTrue($parent->existsComponent('child'));
    }

    public function testTheBeforeInsertionEventCanCancelTheInsertion()
    {
        $page = $this->getMockForAbstractClass(AbstractPage::class);
        $parent = $this->getMockForAbstractClass(AbstractComponent::class, ['parent']);
        $child = $this->getMockForAbstractClass(AbstractComponent::class, ['child']);

        $executed = false;
        $page->on(PageEvents::BEFORE_INSERTION, function (BeforeInsertionEvent $event) use (&$executed, $parent, $child) {
            $executed = true;
            $event->cancel(true);
        });

        $parent->setPage($page);
        $parent->addComponent($child); // Act

        $this->assertTrue($executed);
        $this->assertFalse($parent->existsComponent('child'));
    }

    public function testTheAfterInsertionEventIsTriggeredOnThePageWhenANewComponentIsInserted()
    {
        $page = $this->getMockForAbstractClass(AbstractPage::class);
        $parent = $this->getMockForAbstractClass(AbstractComponent::class, ['parent']);
        $child = $this->getMockForAbstractClass(AbstractComponent::class, ['child']);

        $executed = false;
        $page->on(PageEvents::AFTER_INSERTION, function (AfterInsertionEvent $event) use (&$executed, $parent, $child) {
            $executed = true;
            $this->assertEquals($parent, $event->getParent());
            $this->assertEquals($child, $event->getChild());
            $this->assertTrue($parent->existsComponent('child'));
        });

        $parent->setPage($page);
        $parent->addComponent($child); // Act

        $this->assertTrue($executed);
    }

    public function testTheBeforeDeletionEventIsTriggeredOnThePageWhenAComponentWillBeDeleted()
    {
        $page = $this->getMockForAbstractClass(AbstractPage::class);
        $parent = $this->getMockForAbstractClass(AbstractComponent::class, ['parent']);
        $child = $this->getMockForAbstractClass(AbstractComponent::class, ['child']);

        $executed = false;
        $page->on(PageEvents::BEFORE_DELETION, function (BeforeDeletionEvent $event) use (&$executed, $parent, $child) {
            $executed = true;
            $this->assertEquals($parent, $event->getParent());
            $this->assertEquals($child, $event->getChild());
            $this->assertTrue($parent->existsComponent('child'));
        });

        $parent->setPage($page);
        $parent->addComponent($child);
        $parent->dropComponent('child'); // Act

        $this->assertTrue($executed);
        $this->assertFalse($parent->existsComponent('child'));
    }

    public function testTheBeforeDeletionEventCanCancelTheDeletion()
    {
        $page = $this->getMockForAbstractClass(AbstractPage::class);
        $parent = $this->getMockForAbstractClass(AbstractComponent::class, ['parent']);
        $child = $this->getMockForAbstractClass(AbstractComponent::class, ['child']);

        $executed = false;
        $page->on(PageEvents::BEFORE_DELETION, function (BeforeDeletionEvent $event) use (&$executed, $parent, $child) {
            $executed = true;
            $event->cancel(true);
        });

        $parent->setPage($page);
        $parent->addComponent($child);
        $parent->dropComponent('child'); // Act

        $this->assertTrue($executed);
        $this->assertTrue($parent->existsComponent('child'));
    }
}