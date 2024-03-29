<?php

namespace CartLoad\Cart\Events;


use CartLoad\Cart\Container;
use CartLoad\Cart\Errors;
use CartLoad\Cart\Item;
use Symfony\Contracts\EventDispatcher\Event;

class CartGetItemsAfterEvent extends Event
{
    use Errors {
        addError as protected traitAddError;
    }

    const NAME = 'cart.get_items.after';

    /**
     * @var Container
     */
    protected $cart;

    /**
     * @var Item[]
     */
    protected $items;

    public function __construct(Container $cart, array $items)
    {
        $this->cart = $cart;
        $this->items = $items;
    }

    /**
     * @return Container
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    //------------------------------------------------------------------------------------------------------------------
    //-- Custom Errors
    //------------------------------------------------------------------------------------------------------------------
    /**
     * @param mixed $error
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