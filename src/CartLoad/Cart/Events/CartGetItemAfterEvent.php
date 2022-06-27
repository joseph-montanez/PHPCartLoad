<?php

namespace CartLoad\Cart\Events;


use CartLoad\Cart\Container;
use CartLoad\Cart\Item;
use CartLoad\Cart\Errors;
use Symfony\Contracts\EventDispatcher\Event;

class CartGetItemAfterEvent extends Event
{
    use Errors {
        addError as protected traitAddError;
    }

    const NAME = 'cart.get_item.after';

    /**
     * @var Container
     */
    protected $cart;

    /**
     * @var \CartLoad\Cart\Item
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
     * @return \CartLoad\Cart\Item
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
     * @param bool $key
     *
     * @return self
     */
    public function addError($error, $key = false)
    {
        $this->stopPropagation();
        return $this->traitAddError($error, $key);
    }
}