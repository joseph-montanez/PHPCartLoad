<?php

namespace CartLoad\Cart\Repositories;

use CartLoad\Cart\Item;
use CartLoad\Cart\RepositoryInterface;

class SessionRepository implements RepositoryInterface
{
    public $items = [];
    public $namespace = '';

    public function __construct($namespace = '')
    {
        $this->namespace = $namespace;
    }

    /**
     * @param \CartLoad\Cart\Item $item
     *
     * @return bool
     */
    public function addItem(Item $item)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return false;
        }

        if (strlen($this->namespace) > 0) {
            if (!isset($_SESSION[$this->namespace])) {
                $_SESSION[$this->namespace] = [];
            }
            if (!isset($_SESSION[$this->namespace]['items'])) {
                $_SESSION[$this->namespace]['items'] = [];
            }

            $_SESSION[$this->namespace]['items'] [] = $item;
        } else {
            if (!isset($_SESSION['items'])) {
                $_SESSION['items'] = [];
            }

            $_SESSION['items'] [] = $item;
        }

        return true;
    }

    /**
     * @param Item $item
     *
     * @return bool
     */
    public function deleteItem(Item $item)
    {
        $items = $this->getItems();
        $index = array_search($item, $items, true);

        if ($index !== false) {
            unset($items[$index]);
            $this->setItems($items);
            return true;
        }

        return false;
    }

    public function getItems()
    {
        $items = [];
        if (strlen($this->namespace) > 0) {
            if (isset($_SESSION[$this->namespace]) && isset($_SESSION[$this->namespace]['items'])) {
                $items = $_SESSION[$this->namespace]['items'];
            }
        } else {
            if (isset($_SESSION['items'])) {
                $items = $_SESSION['items'];
            }

        }

        return $items;
    }

    public function setItems($items)
    {
        if (strlen($this->namespace) > 0) {
            if (!isset($_SESSION[$this->namespace])) {
                $_SESSION[$this->namespace] = [];
            }
            $_SESSION[$this->namespace]['items'] = $items;

        } else {
            $_SESSION['items'] = $items;
        }
    }

    /**
     * Get the item in the repository, if there is no match, return null
     *
     * @param string $id
     *
     * @return mixed|null
     */
    public function findItem($id)
    {
        $items = $this->getItems();
        $index = null;
        foreach ($items as $i => $item) {
            if ($item->getId() === $id) {
                $index = $i;
                break;
            }
        }

        return $index !== null ? $items[$index] : null;
    }
}