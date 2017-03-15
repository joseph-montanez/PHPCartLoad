<?php
/**
 * Created by PhpStorm.
 * User: xingo
 * Date: 3/15/2017
 * Time: 8:33 AM
 */

namespace CartLoad\Cart;
use CartLoad\Cart\Item;


interface Repository
{
    public function addItem(Item $item);
    public function deleteItem(Item $item);
    public function getItems();
    public function setItems($item);
    public function findItem($id);

}