<?php
use CartLoad\Product\Product;

require_once __DIR__ . '/../vendor/autoload.php';


$apple = Product::make([
    'name' => 'Apple',
    'sku' => 'a',
    'price' => 19.95
]);

$qty = 10;


//-- This will return the simple price: 19.95
var_dump($apple->getPrice($qty));