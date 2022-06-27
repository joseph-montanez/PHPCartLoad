<?php

use CartLoad\Cart\Container;
use CartLoad\Cart\Events\CartAddItemBeforeEvent;
use CartLoad\Cart\Events\CartGetItemAfterEvent;
use CartLoad\Cart\Item;
use CartLoad\Cart\Repositories\SessionRepository;
use CartLoad\Product\Product;

require_once __DIR__ . '/../vendor/autoload.php';

//--------------------------------------------------------------------------------------------------------------
//-- Products
//--------------------------------------------------------------------------------------------------------------
$products = [
    'd97c4f2f-fd06-4e7d-b161-51f4038ee898' => Product::make([
        'id' => 'd97c4f2f-fd06-4e7d-b161-51f4038ee898',
        'name' => 'Apple',
        'sku' => 'a',
        'price' => 19.95
    ]),
    'b99cb34e-7edb-45d3-9dfe-5e39f6b71587' => Product::make([
        'id' => 'b99cb34e-7edb-45d3-9dfe-5e39f6b71587',
        'name' => 'Orange',
        'sku' => 'o',
        'price' => 21.99
    ]),
];


//--------------------------------------------------------------------------------------------------------------
//-- Cart
//--------------------------------------------------------------------------------------------------------------
$repository = new SessionRepository();
$container = new Container($repository);

//--------------------------------------------------------------------------------------------------------------
//-- Validation
//--------------------------------------------------------------------------------------------------------------
//-- @ before adding to cart
$container->addListener(CartAddItemBeforeEvent::NAME, function (CartAddItemBeforeEvent $event) use ($products) {
    /**
     * @var $item CartLoad\Cart\Item
     */
    $item = $event->getItem();

    /** @var CartLoad\Product\Product $product */
    $product = isset($products[$item->getProductId()]) ? $products[$item->getProductId()] : null;

    if ($product === null) {
        $event->addError('Sorry this product "' . $item->getProductId() . '" does not exist', 'does-not-exist');
    }
});

//-- @ after getting an item
$container->addListener(CartGetItemAfterEvent::NAME, function (CartGetItemAfterEvent $event) {
    /**
     * @var $item CartLoad\Cart\Item
     */
    $item = $event->getItem();

    if ($item->getQty() < 1) {
        $event->addError('Please enter a quantity', 'qty');
    }
});

//-- Example of a good cart add
$apple = $products['d97c4f2f-fd06-4e7d-b161-51f4038ee898'];
$cartAppleData = [
    'id'         => uniqid(),
    'product_id' => $apple->getId(),
    'qty'        => 3,
];
$cartAppleItem = Item::make($cartAppleData);
$appleAdded = $container->addItem($cartAppleItem); //-- returns true

//-- Example of a bad cart add
$cartBadAppleData = [
    'id'         => uniqid(),
    'product_id' => 'i do not exist',
    'qty'        => 3,
];
$cartBadAppleItem = Item::make($cartBadAppleData);
$badAppleAdded = $container->addItem($cartBadAppleItem); //-- returns false

if (!$badAppleAdded) {
    $errors = $container->getErrors();

    foreach ($errors as $key => $error) {
        echo '"', $key, '" - ', $error, PHP_EOL;
    }
}