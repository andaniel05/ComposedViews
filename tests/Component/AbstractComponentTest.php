<?php

namespace Andaniel05\ComposedViews\Tests\Component;

use Andaniel05\ComposedViews\{AbstractPage, PageEvents};
use Andaniel05\ComposedViews\Event\{BeforeInsertionEvent, AfterInsertionEvent,
    BeforeDeletionEvent, AfterDeletionEvent};
use Andaniel05\ComposedViews\Component\AbstractComponent;
use PHPUnit\Framework\TestCase;

class AbstractComponentTest extends TestCase
{
    public function setUp()
    {
        $this->component = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component'])
            ->getMockForAbstractClass();
    }

    public function testArgumentGetters()
    {
        $id = uniqid();

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

    public function testDetachInvokeDropChildInTheParent()
    {
        // Arrange
        //

        $childId = uniqid();

        $parent = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['parent'])
            ->setMethods(['dropChild'])
            ->getMockForAbstractClass();
        $parent->expects($this->once())
            ->method('dropChild')
            ->with($this->equalTo($childId));

        $child = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs([$childId])
            ->getMockForAbstractClass();

        $parent->addChild($child);

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

    public function testRenderizeChildren()
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

        $component2->addChild($component1);

            $expected = <<<HTML
<div class="cv-component cv-component1" id="cv-component1">
    $component1Html
</div>
HTML;

        $this->assertXmlStringEqualsXmlString($expected, $component2->renderizeChildren());
    }





    public function getComponentMock(string $id = 'component')
    {
        $component = $this->getMockForAbstractClass(AbstractComponent::class, [$id]);

        return $component;
    }

    public function testGetChildrenReturnAnEmptyArrayByDefault()
    {
        $component = $this->getComponentMock();

        $this->assertEquals([], $component->getChildren());
    }

    public function testGetChildReturnNullIfComponentNotExists()
    {
        $component = $this->getComponentMock();

        $this->assertNull($component->getChild('component1'));
    }

    public function insertTwoComponents()
    {
        $this->component1 = $this->createMock(AbstractComponent::class);
        $this->component1->method('getId')->willReturn('component1');
        $this->component2 = $this->createMock(AbstractComponent::class);
        $this->component2->method('getId')->willReturn('component2');

        $this->container = $this->getComponentMock();
        $this->container->addChild($this->component1);
        $this->container->addChild($this->component2);
    }

    public function testGetChildReturnTheComponentIfExists()
    {
        $this->insertTwoComponents();

        $this->assertSame(
            $this->component1,
            $this->container->getChild('component1')
        );
    }

    public function testGetChildrenReturnAnArrayWithAllInsertedComponents()
    {
        $this->insertTwoComponents();

        $expected = [
            'component1' => $this->component1,
            'component2' => $this->component2,
        ];

        $this->assertEquals($expected, $this->container->getChildren());
    }

    public function testDropChildRemoveTheComponentWhenExists()
    {
        $this->insertTwoComponents();

        $this->container->dropChild('component2');

        $this->assertEquals(
            ['component1' => $this->component1],
            $this->container->getChildren()
        );
    }

    public function testHasRootChildReturnFalseWhenComponentNotExists()
    {
        $this->insertTwoComponents();

        $this->assertFalse($this->container->hasRootChild('component5'));
    }

    public function testHasRootChildReturnTrueWhenComponentExists()
    {
        $this->insertTwoComponents();

        $this->assertTrue($this->container->hasRootChild('component1'));
        $this->assertTrue($this->container->hasRootChild('component2'));
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

        $component1->addChild($component2);
        $component2->addChild($component3);
        $component4->addChild($component5);

        $component->addChild($component1);
        $component->addChild($component4);

        $this->container  = $component;
        $this->component1 = $component1;
        $this->component2 = $component2;
        $this->component3 = $component3;
        $this->component4 = $component4;
        $this->component5 = $component5;
    }

    public function testGetChildSearchTheComponentInAllTheTree()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component2,
            $this->container->getChild('component2')
        );
        $this->assertSame(
            $this->component3,
            $this->container->getChild('component3')
        );
        $this->assertSame(
            $this->component5,
            $this->container->getChild('component5')
        );
    }

    public function testGetChildWhenIdIsComplex1()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component5,
            $this->container->getChild('component4 component5')
        );
    }

    public function testGetChildWhenIdIsComplex2()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component3,
            $this->container->getChild('component1 component3')
        );
    }

    public function testGetChildWhenIdIsComplex3()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component3,
            $this->container->getChild('component1 component2 component3')
        );
    }

    public function testGetChildWhenIdIsComplex4()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component3,
            $this->container->getChild('component2 component3')
        );
    }

    public function testGetChildWhenIdIsComplex5()
    {
        $this->initializeNestedComponents();

        $this->assertNull(
            $this->container->getChild('component1 component5')
        );
    }

    public function insertChildComponent()
    {
        $this->container = $this->getComponentMock();
        $this->child = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['child'])
            ->getMockForAbstractClass();

        $this->container->addChild($this->child);
    }

    public function testAddChildRegisterToItSelfAsParentInTheChild()
    {
        $this->insertChildComponent();

        $this->assertSame($this->container, $this->child->getParent());
    }

    public function testDropChildSetNullAsParentInTheChild()
    {
        $this->insertChildComponent();

        $this->container->dropChild('child');

        $this->assertNull($this->child->getParent());
    }

    public function initialization1()
    {
        $this->child = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['child'])
            ->setMethods(['setParent'])
            ->getMockForAbstractClass();

        $this->container = $this->getComponentMock();
        $this->container->addChild($this->child);
    }

    public function testDropChildInvokeSetParentWithNullInTheChild()
    {
        $this->initialization1();

        $this->child->expects($this->once())
            ->method('setParent')
            ->with($this->equalTo(null));

        $this->container->dropChild('child');
    }

    public function testDropChildNotInvokeToSetParentWithNullInTheChildWhenNotifyChildArgumentIsFalse()
    {
        $this->initialization1();

        $this->child->expects($this->exactly(0))
            ->method('setParent');

        $this->container->dropChild('child', false);
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
            $this->assertFalse($parent->hasRootChild('child'));
        });

        $parent->setPage($page);
        $parent->addChild($child); // Act

        $this->assertTrue($executed);
        $this->assertTrue($parent->hasRootChild('child'));
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
        $parent->addChild($child); // Act

        $this->assertTrue($executed);
        $this->assertFalse($parent->hasRootChild('child'));
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
            $this->assertTrue($parent->hasRootChild('child'));
        });

        $parent->setPage($page);
        $parent->addChild($child); // Act

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
            $this->assertTrue($parent->hasRootChild('child'));
        });

        $parent->setPage($page);
        $parent->addChild($child);
        $parent->dropChild('child'); // Act

        $this->assertTrue($executed);
        $this->assertFalse($parent->hasRootChild('child'));
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
        $parent->addChild($child);
        $parent->dropChild('child'); // Act

        $this->assertTrue($executed);
        $this->assertTrue($parent->hasRootChild('child'));
    }

    public function testTheAfterDeletionEventIsTriggeredOnThePageWhenAComponentWillBeDeleted()
    {
        $page = $this->getMockForAbstractClass(AbstractPage::class);
        $parent = $this->getMockForAbstractClass(AbstractComponent::class, ['parent']);
        $child = $this->getMockForAbstractClass(AbstractComponent::class, ['child']);

        $executed = false;
        $page->on(PageEvents::AFTER_DELETION, function (AfterDeletionEvent $event) use (&$executed, $parent, $child) {
            $executed = true;
            $this->assertEquals($parent, $event->getParent());
            $this->assertEquals($child, $event->getChild());
            $this->assertFalse($parent->hasRootChild('child'));
        });

        $parent->setPage($page);
        $parent->addChild($child);
        $parent->dropChild('child'); // Act

        $this->assertTrue($executed);
    }

    public function createAuxComponents()
    {
        $this->comp1 = $this->getMockForAbstractClass(
            AbstractComponent::class, ['comp1']
        );
        $this->comp2 = $this->getMockForAbstractClass(
            AbstractComponent::class, ['comp2']
        );
        $this->comp3 = $this->getMockForAbstractClass(
            AbstractComponent::class, ['comp3']
        );
        $this->comp4 = $this->getMockForAbstractClass(
            AbstractComponent::class, ['comp4']
        );
        $this->comp5 = $this->getMockForAbstractClass(
            AbstractComponent::class, ['comp5']
        );
    }

    public function testTraverse1()
    {
        $this->createAuxComponents();

        $this->component->addChild($this->comp1);
        $this->component->addChild($this->comp2);
        $this->component->addChild($this->comp3);

        $iter = $this->component->traverse();

        $this->assertEquals($this->comp1, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp2, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp3, $iter->current());
    }

    public function testTraverse2()
    {
        $this->createAuxComponents();

        $this->comp1->addChild($this->comp2);
        $this->comp2->addChild($this->comp3);
        $this->comp3->addChild($this->comp4);
        $this->comp4->addChild($this->comp5);

        $this->component->addChild($this->comp1);

        $iter = $this->component->traverse();

        $this->assertEquals($this->comp1, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp2, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp3, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp4, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp5, $iter->current());
    }

    public function testTraverse3()
    {
        $this->createAuxComponents();

        $this->comp1->addChild($this->comp3);
        $this->comp1->addChild($this->comp4);
        $this->comp2->addChild($this->comp5);

        $this->component->addChild($this->comp1);
        $this->component->addChild($this->comp2);

        $iter = $this->component->traverse();

        $this->assertEquals($this->comp1, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp3, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp4, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp2, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp5, $iter->current());
    }
}