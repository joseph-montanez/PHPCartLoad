# PHPCartLoad [![Build Status](https://travis-ci.org/joseph-montanez/phpflo.svg?branch=master)](https://travis-ci.org/joseph-montanez/phpflo) #

PHPCartLoad is a library intended to help build shopping carts and general e-commerce applications. This library is intended to provide a foundation of easy to use classes to manage inventory pricing and carts.

## License

What is this AGPL? It makes it impossible to use with my library, why not BSD or MIT? Well there is still lots of work to do as this library is not complete. As the library reaches a 1.0 release I'll decide on what license to use.

## Examples

### Simple Pricing

This is the Hello World of this library, showing you how little you have to do to start to get the library working with your existing code base.

    <?php
    use CartLoad\Product\Item;

    require_once __DIR__ . '/../vendor/autoload.php';

    $apple = new Item([
        'name' => 'Apple',
        'sku' => 'a',
        'price' => 19.95
    ]);

    $qty = 10;

    //-- This will return the simple price: 19.95
    var_dump($apple->getPrice($qty)->getPrice());

### Bulk Pricing

You can also expand on pricing to include bulk pricing, again keeping with something as simple as possible.

    <?php
    use CartLoad\Product\Item;

    require_once __DIR__ . '/../vendor/autoload.php';

    $apple = new Item([
        'name' => 'Apple',
        'sku' => 'a',
        'price' => [
            19.95,
            ['minQty' => 10, 'price' => 14.95],
        ]
    ]);

    $qty = 10;

    //-- This will return the simple price: 14.95
    var_dump($apple->getPrice($qty)->getPrice());

