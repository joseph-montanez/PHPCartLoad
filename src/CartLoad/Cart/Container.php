<?php

namespace CartLoad\Cart;


use CartLoad\Cart\Events\CartAddItemBeforeEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Container
{

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var Item[]
     */
    protected $items;

    public function __construct()
    {
        $this->dispatcher = new EventDispatcher();
    }

    /**
     * @param $event_name
     * @param $event_callable
     * @param int $priority
     */
    public function addListener($event_name, $event_callable, $priority = 0)
    {
        $this->dispatcher->addListener($event_name, $event_callable, $priority);
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function addItem(Item $item) {
        $event = new CartAddItemBeforeEvent($this, $item);
        $this->dispatcher->dispatch(CartAddItemBeforeEvent::NAME, $event);

        if ($event->isPropagationStopped()) {
            return false;
        } else {
            $this->items []= $item;
            return true;
        }
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
}