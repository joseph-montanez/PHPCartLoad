<?php

use CartLoad\Cart\Container;
use CartLoad\Cart\Events\CartAddItemBeforeEvent;
use CartLoad\Cart\Events\CartGetItemAfterEvent;
use CartLoad\Cart\Item;

class CartContainerTest extends \Codeception\Test\Unit
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
        $repository = new \CartLoad\Cart\Repositories\ArrayRepository();
        $cart = new Container($repository);

        $this->assertEquals(true, $cart->getRepository() instanceof \CartLoad\Cart\Repositories\ArrayRepository);

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
        $this->assertEquals(0, count($cart->getItems()));
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
        $repository = new \CartLoad\Cart\Repositories\ArrayRepository();
        $cart = new Container($repository);

        $this->assertEquals(true, $cart->getRepository() instanceof \CartLoad\Cart\Repositories\ArrayRepository);

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
}