<?php

namespace CartLoad\Cart\Events;


use CartLoad\Cart\Container;
use CartLoad\Cart\Item;
use CartLoad\Cart\Errors;
use Symfony\Component\EventDispatcher\Event;

class CartGetItemAfterEvent extends Event
{
    use Errors;

    const NAME = 'cart.get_item.after';

    /**
     * @var Container
     */
    protected $cart;

    /**
     * @var Item
     */
    protected $item;

    public function __construct(Container $cart, Item $item)
    {
        $this->cart = $cart;
        $this->item = $item;
    }

    /**
     * @return Container
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    //------------------------------------------------------------------------------------------------------------------
    //-- Custom Errors
    //------------------------------------------------------------------------------------------------------------------
    /**
     * @param $error
     * @return CartAddItemBeforeEvent
     */
    public function addError($error, $key = false)
    {
        $this->stopPropagation();
        if ($key) {
            $this->errors [$key]= $error;
        } else {
            $this->errors []= $error;
        }
        return $this;
    }
}