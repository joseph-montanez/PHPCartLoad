<?php

use CartLoad\Cart\Container;
use CartLoad\Cart\Events\CartAddItemBeforeEvent;
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
        $cart = new Container();

        //-- Add the event listener
        $cart->addListener(CartAddItemBeforeEvent::NAME, function (CartAddItemBeforeEvent $event) {
            $item = $event->getItem();
            if ($item->getProductId() === 1 && $item->getQty() > 2) {
               $event->addError('Sorry the limit is 2 per customer');
           }
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
}