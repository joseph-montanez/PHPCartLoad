<?php

namespace CartLoad\Cart;


use CartLoad\Cart\Events\CartAddItemBeforeEvent;
use CartLoad\Cart\Events\CartDeleteItemAfterEvent;
use CartLoad\Cart\Events\CartDeleteItemBeforeEvent;
use CartLoad\Cart\Events\CartGetItemAfterEvent;
use CartLoad\Cart\Events\CartGetItemsAfterEvent;
use CartLoad\Cart\Repositories\SessionRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Container
{
    use Errors;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var RepositoryInterface
     */
    protected $repository = null;

    public function __construct(RepositoryInterface $repository = null)
    {
        $this->setRepository($repository === null ? new SessionRepository() : $repository);

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
     *
     * @return bool
     */
    public function addItem(Item $item)
    {
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
        $items = [];

        //-- Call get item event
        foreach ($this->repository->getItems() as $item) {
            $event = new CartGetItemAfterEvent($this, $item);
            $this->dispatcher->dispatch(CartGetItemAfterEvent::NAME, $event);


            if ($event->hasErrors()) {
                $item->addErrors($event->getErrors());
            }

            $items[] = $item;
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
     * Get the item in the repository, if there is no match, return null
     *
     * @param string $id
     *
     * @return Item|null
     */
    public function findItem($id)
    {
        return $this->repository->findItem($id);
    }

    /**
     * Delete an item from the repository.
     *
     * @param Item $item
     *
     * @return bool
     */
    public function deleteItem(Item $item)
    {
        //-- Call before delete items event
        $event = new CartDeleteItemBeforeEvent($this, $item);
        $dispatchedEvent = $this->dispatcher->dispatch(CartDeleteItemBeforeEvent::NAME, $event);

        //-- If some event stopped this delete function, return false
        if ($dispatchedEvent->isPropagationStopped()) {
            return false;
        }

        $results = $this->repository->deleteItem($item);

        //-- Call before delete items event
        $event = new CartDeleteItemAfterEvent($this, $item);
        $dispatchedEvent = $this->dispatcher->dispatch(CartDeleteItemAfterEvent::NAME, $event);

        return $results;
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

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param RepositoryInterface $repository
     *
     * @return Container
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;

        return $this;
    }
}
