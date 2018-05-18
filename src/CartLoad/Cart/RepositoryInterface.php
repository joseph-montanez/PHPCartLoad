<?php

namespace CartLoad\Cart;

use CartLoad\Cart\Item;


interface RepositoryInterface
{
    /**
     * @param \CartLoad\Cart\Item $item
     *
     * @return bool
     */
    public function addItem(Item $item);

    /**
     * @param \CartLoad\Cart\Item $item
     *
     * @return bool
     */
    public function deleteItem(Item $item);

    /**
     * @return array
     */
    public function getItems();

    /**
     * @param $item
     *
     * @return bool
     */
    public function setItems($item);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function findItem($id);

}