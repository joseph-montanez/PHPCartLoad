<?php

namespace CartLoad\Cart\Events;


use CartLoad\Cart\Container;
use CartLoad\Cart\Errors;
use Symfony\Component\EventDispatcher\Event;

class CartGetItemsAfterEvent extends Event
{
    use Errors;

    const NAME = 'cart.get_items.after';

    /**
     * @var Container
     */
    protected $cart;

    /**
     * @var \CartLoad\Cart\Item[]
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
     * @return \CartLoad\Cart\Item[]
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
        if ($key) {
            $this->errors [$key]= $error;
        } else {
            $this->errors []= $error;
        }
        return $this;
    }
}