<?php

use CartLoad\Cart\Container;
use CartLoad\Cart\Events\CartAddItemBeforeEvent;
use CartLoad\Cart\Events\CartDeleteItemAfterEvent;
use CartLoad\Cart\Events\CartDeleteItemBeforeEvent;
use CartLoad\Cart\Events\CartGetItemAfterEvent;
use CartLoad\Cart\Events\CartGetItemsAfterEvent;
use CartLoad\Cart\Item;
use CartLoad\Cart\Repositories\ArrayRepository;

class CartContainerArrayRepositoryTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testAddBeforeEvent()
    {
        $repository = new ArrayRepository();
        $cart = new Container($repository);

        $this->assertEquals(true, $cart->getRepository() instanceof ArrayRepository);

        //-- Add the event listener
        $cart->addListener(CartAddItemBeforeEvent::NAME, function (CartAddItemBeforeEvent $event) {
            $item = $event->getItem();
            if ($item->getProductId() === 1 && $item->getQty() > 2) {
                $event->addError('Sorry the limit is 2 per customer');
            }


            $this->assertTrue($event->getCart() instanceof Container);
        });

        //-- This will fail to add to cart
        $item = Item::make([
            'id' => 1,
            'product_id' => 1,
            'qty' => 100,
        ]);
        $added = $cart->addItem($item);

        $this->assertFalse($added);
        $this->assertCount(0, count($cart->getItems()));
        $this->assertEquals('Sorry the limit is 2 per customer', $cart->getErrors()[0]);


        //-- Let make this able to add to cart now
        $cart->clearErrors();
        $item->setQty(2);
        $added = $cart->addItem($item);

        $this->assertTrue($added);
        $this->assertEquals(1, count($cart->getItems()));
        $this->assertEquals(0, count($cart->getErrors()));

    }

    public function testGetAfterEvent()
    {
        $repository = new ArrayRepository();
        $cart = new Container($repository);

        $this->assertEquals(true, $cart->getRepository() instanceof ArrayRepository);

        //-- Add the event listener
        $cart->addListener(CartGetItemAfterEvent::NAME, function (CartGetItemAfterEvent $event) {
            /**
             * @var $item CartLoad\Cart\Item
             */
            $item = $event->getItem();

            if ($item->getQty() < 1) {
                $event->addError('Please enter a quantity', 'qty');
            }
        });

        $cart->addListener(CartGetItemAfterEvent::NAME, function (CartGetItemAfterEvent $event) {
            /**
             * @var $cart CartLoad\Cart\Container
             */
            $cart = $event->getCart();

            /**
             * @var $item CartLoad\Cart\Item
             */
            $item = $event->getItem();

            if ($item->getQty() > 10) {
                $event->addError('Sorry there are only 10 items in stock!');
            }

            $this->assertTrue($cart instanceof Container);
        });

        //-- Add the event listener
        $cart->addListener(CartGetItemsAfterEvent::NAME, function (CartGetItemsAfterEvent $event) {
            /**
             * @var $item CartLoad\Cart\Item
             */
            $items = $event->getItems();

            /**
             * @var $item CartLoad\Cart\Container
             */
            $cart = $event->getCart();

            //-- Push an error but nothing will be stopped....
            $event->addError(count($items) .' items have been captured WHAT will you do?', 'error');
        });

        //-- This will fail to add to cart
        $item = Item::make([
            'id' => 1,
            'product_id' => 1,
            'qty' => 100,
        ]);
        $added = $cart->addItem($item);

        $this->assertTrue($added);
        $this->assertEquals(1, count($cart->getItems()));


        //-- Let change the qty to zero
        $cart->clearErrors();
        $item = $cart->findItem(1);
        $item->setQty(0);

        $items = $cart->getItems();
        $this->assertEquals(1, count($items));
        $this->assertEquals(1, count($item->getErrors()));



        //-- Let order more than possible!
        $cart->clearErrors();
        $item->setQty(20);
        $items = $cart->getItems();
        $this->assertEquals('Sorry there are only 10 items in stock!', $item->getErrors()[0]);


    }

    public function testCartDeleteEvent()
    {
        $repository = new ArrayRepository();
        $cart = new Container($repository);

        $item = Item::make([
            'id' => 1,
            'product_id' => 1,
            'qty' => 100,
        ]);
        $item2 = Item::make([
            'id' => 2,
            'product_id' => 2,
            'qty' => 10,
        ]);
        $added = $cart->addItem($item);

        //-- Delete item
        $deleted = $cart->deleteItem($item);
        $items = $cart->getItems();

        $this->assertEquals(true, $deleted);
        $this->assertEquals(0, count($items));

        $added = $cart->addItem($item);
        $added2 = $cart->addItem($item2);

        //-- Test deleting an item that does not exist
        $deleted = $cart->deleteItem(Item::make([]));
        $items = $cart->getItems();

        $this->assertEquals(false, $deleted);
        $this->assertEquals(2, count($items));

        //-- Lets test deleting an item with its delete feature being blocked,
        // by the listener

        //-- Add the event listener
        $cart->addListener(CartDeleteItemBeforeEvent::NAME, function (CartDeleteItemBeforeEvent $event) {
            /**
             * @var $item CartLoad\Cart\Item
             */
            $item = $event->getItem();

            /**
             * @var $item CartLoad\Cart\Container
             */
            $cart = $event->getCart();

            if ($item->getId() === 1) {
                $event->addError('You are not allowed to delete this item', 'error');
            }
        });

        //-- Add the event listener
        $cart->addListener(CartDeleteItemAfterEvent::NAME, function (CartDeleteItemAfterEvent $event) {
            /**
             * @var $item CartLoad\Cart\Item
             */
            $item = $event->getItem();

            /**
             * @var $item CartLoad\Cart\Container
             */
            $cart = $event->getCart();

            $error = 'You deleted this item... beware!!!';
            if ($item->getId() === 2) {
                //-- This will not stop the item from being deleted, but will still show an error
                $event->addError($error, 'error');
            }


            $this->assertEquals(true, $event->hasError('error'));
            $this->assertEquals($error, $event->getErrorByKey('error'));
            $this->assertEquals(false, $event->getErrorByKey('error2'));
        });

        //-- Delete item - this will fail
        $deleted = $cart->deleteItem($item);
        $items = $cart->getItems();
        $this->assertEquals(false, $deleted);
        $this->assertEquals(2, count($items));

        //-- Delete item - this will succeed
        $deleted = $cart->deleteItem($item2);
        $items = $cart->getItems();
        $this->assertEquals(true, $deleted);
        $this->assertEquals(1, count($items));
    }
}
