<?php

namespace CartLoad\Cart\Events;

use CartLoad\Cart\Container;
use CartLoad\Cart\Item;
use Symfony\Component\EventDispatcher\Event;

class CartAddItemBeforeEvent extends Event
{

    const NAME = 'cart.add_item.before';

    /**
     * @var Container
     */
    protected $cart;

    /**
     * @var Item
     */
    protected $item;

    /**
     * @var \string[]
     */
    protected $errors = [];

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

    /**
     * @return \string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param \string[] $errors
     * @return CartAddItemBeforeEvent
     */
    public function setErrors($errors)
    {
        $this->stopPropagation();
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param $error
     * @return CartAddItemBeforeEvent
     */
    public function addError($error)
    {
        $this->stopPropagation();
        $this->errors []= $error;
        return $this;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

}
