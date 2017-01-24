<?php
use CartLoad\Product\Product;

require_once __DIR__ . '/../vendor/autoload.php';


$apple = Product::make([
    'name' => 'Apple',
    'sku' => 'a',
    'price' => [
        ['min_qty' => 1, 'max_qty' => 9, 'price' => 19.95],
        ['min_qty' => 10, 'price' => 14.95],
    ]
]);

$qty = 10;


//-- This will return the simple price: 14.95
var_dump($apple->getPrice($qty));