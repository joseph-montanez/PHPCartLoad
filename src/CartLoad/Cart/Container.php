<?php

namespace CartLoad\Cart;


use CartLoad\Cart\Events\CartAddItemBeforeEvent;
use CartLoad\Cart\Events\CartGetItemAfterEvent;
use CartLoad\Cart\Events\CartGetItemsAfterEvent;
use CartLoad\Cart\Repositories\Session;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Container
{
    use Errors;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var Repository
     */
    protected $repository = null;

    public function __construct(Repository $repository = null)
    {
        $this->repository = $this->repository === null ? new Session() : $repository;
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

        if ($event->hasErrors()) {
            $this->addErrors($event->getErrors());
        }

        if ($event->isPropagationStopped()) {
            return false;
        } else {
            $this->repository->addItem($item);
            return true;
        }
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        $items = $this->repository->getItems();

        //-- Call get item event
        foreach ($items as $item) {
            $event = new CartGetItemAfterEvent($this, $item);
            $this->dispatcher->dispatch(CartGetItemAfterEvent::NAME, $event);


            if ($event->hasErrors()) {
                $item->addErrors($event->getErrors());
            }
        }
        unset($event);

        //-- Call get items event
        $event = new CartGetItemsAfterEvent($this, $this->repository->getItems());
        $this->dispatcher->dispatch(CartGetItemsAfterEvent::NAME, $event);

        if ($event->hasErrors()) {
            $this->addErrors($event->getErrors());
        }

        return $items;
    }

    /**
     * Get the item in the respository, if there is no match, return null
     * @param string $id
     * @return Item|null
     */
    public function findItem($id)
    {
        return $this->repository->findItem($id);
    }

    public function deleteItem(Item $item)
    {
        return $this->repository->deleteItem($item);
    }

    //------------------------------------------------------------------------------------------------------------------
    //-- Custom Errors
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Clears errors in container and its child items
     *
     * @return $this
     */
    public function clearErrors()
    {
        $this->errors = [];

        /**
         * @var $items Item[]
         */
        $items = $this->repository->getItems();
        foreach ($items as $item) {
            $item->clearErrors();
        }

        return $this;
    }
}