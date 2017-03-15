<?php

namespace CartLoad\Cart\Repositories;

use CartLoad\Cart\Repository;
use CartLoad\Cart\Item;

class Session implements Repository
{
    public $items = [];
    public $namespace = '';

    public function __construct($namespace = '')
    {
        $this->namespace = $namespace;
    }

    public function addItem(Item $item)
    {
        if (strlen($this->namespace) > 0) {
            if (!isset($_SESSION[$this->namespace])) {
                $_SESSION[$this->namespace] = [];
            }
            if (!isset($_SESSION[$this->namespace]['items'])) {
                $_SESSION[$this->namespace]['items'] = [];
            }

            $_SESSION[$this->namespace]['items'] []= $item;
        } else {
            if (!isset($_SESSION['items'])) {
                $_SESSION['items'] = [];
            }

            $_SESSION['items'] []= $item;

        }
    }

    public function deleteItem(Item $item)
    {
        $items = $this->getItems();
        $index = array_search($item, $items, true);

        if ($index !== false) {
            unset($items[$index]);
            $this->setItems($items);
        }
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
            $_SESSION['items'] []= $items;
        }
    }

    /**
     * Get the item in the respository, if there is no match, return null
     * @param Item $item
     * @return mixed|null
     */
    public function findItem(Item $item)
    {
        $items = $this->getItems();
        $index = array_search($item, $items);

        return $index ? $items[$index] : null;
    }
}