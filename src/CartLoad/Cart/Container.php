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
    protected EventDispatcher $dispatcher;

    /**
     * @var RepositoryInterface|null
     */
    protected ?RepositoryInterface $repository = null;

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
    public function addListener($event_name, $event_callable, int $priority = 0): void
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
        $this->dispatcher->dispatch($event, CartAddItemBeforeEvent::NAME);

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
     * @return \CartLoad\Cart\Item[]
     */
    public function getItems() : array
    {
        $items = [];

        //-- Call get item event
        foreach ($this->repository->getItems() as $item) {
            $event = new CartGetItemAfterEvent($this, $item);
            $this->dispatcher->dispatch($event, CartGetItemAfterEvent::NAME);


            if ($event->hasErrors()) {
                $item->addErrors($event->getErrors());
            }

            $items[] = $item;
        }
        unset($event);

        //-- Call get items event
        $event = new CartGetItemsAfterEvent($this, $this->repository->getItems());
        $this->dispatcher->dispatch($event, CartGetItemsAfterEvent::NAME);

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
    public function deleteItem(Item $item): bool
    {
        //-- Call before delete items event
        $event = new CartDeleteItemBeforeEvent($this, $item);
        $dispatchedEvent = $this->dispatcher->dispatch($event, CartDeleteItemBeforeEvent::NAME);

        //-- If some event stopped this function, return false
        if ($dispatchedEvent->isPropagationStopped()) {
            return false;
        }

        $results = $this->repository->deleteItem($item);

        //-- Call before delete items event
        $event = new CartDeleteItemAfterEvent($this, $item);
        $dispatchedEvent = $this->dispatcher->dispatch($event, CartDeleteItemAfterEvent::NAME);

        return $results;
    }

    //------------------------------------------------------------------------------------------------------------------
    //-- Custom Errors
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Clears errors in container and its child items
     *
     * @return static
     */
    public function clearErrors(): static
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
     * @return \CartLoad\Cart\RepositoryInterface|null
     */
    public function getRepository(): ?RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @param RepositoryInterface $repository
     *
     * @return static
     */
    public function setRepository(RepositoryInterface $repository): static
    {
        $this->repository = $repository;

        return $this;
    }
}
