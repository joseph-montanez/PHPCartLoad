<?php

namespace CartLoad\Cart;


use CartLoad\Cart\Events\CartAddItemBeforeEvent;
use CartLoad\Cart\Repositories\Session;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Container
{

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var Repository
     */
    protected $repository = null;

    /**
     * @var string[]
     */
    protected $errors = [];

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
        return $this->repository->getItems();
    }

    /**
     * @param string[] $errors
     */
    public function addErrors(array $errors)
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    /**
     * @param $error
     * @return $this
     */
    public function addError($error)
    {
        $this->errors []= $error;

        return $this;
    }

    /**
     * @return \string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function clearErrors()
    {
        $this->errors = [];

        return $this;
    }
}