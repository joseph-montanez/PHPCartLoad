<?php

namespace CartLoad\Cart\Repositories;

use CartLoad\Cart\Item;
use CartLoad\Cart\RepositoryInterface;

class ArrayRepository implements RepositoryInterface
{
    public $items = [];


    /**
     * @param \CartLoad\Cart\Item $item
     *
     * @return bool
     */
    public function addItem(Item $item)
    {
        $pushed = array_push($this->items, $item);

        return $pushed > 0;
    }

    /**
     * @param \CartLoad\Cart\Item $item
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

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param $items
     *
     * @return bool
     */
    public function setItems($items)
    {
        $this->items = $items;

        return true;
    }

    /**
     * Get the item in the repository, if there is no match, return null
     *
     * @param string $id
     *
     * @return \CartLoad\Cart\Item|null
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