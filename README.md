# PHPCartLoad [![Build Status](https://travis-ci.org/joseph-montanez/PHPCartLoad.svg?branch=master)](https://travis-ci.org/joseph-montanez/PHPCartLoad) #

[![codecov](https://codecov.io/gh/joseph-montanez/PHPCartLoad/branch/master/graph/badge.svg)](https://codecov.io/gh/joseph-montanez/PHPCartLoad)

PHPCartLoad is a library intended to help build shopping carts and general e-commerce applications. This library is intended to provide a foundation of easy to use classes to manage inventory pricing and carts. This is a migration of my F# project so there is more to come.

## License

The library is MIT, do what you want with it.

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
    var_dump($apple->getPrice($qty));

### Bulk Pricing

You can also expand on pricing to include bulk pricing, again keeping with something as simple as possible.

    <?php
    use CartLoad\Product\Item;

    require_once __DIR__ . '/../vendor/autoload.php';

    $apple = new Item([
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

### SKU Variations

SKU Variations, or options are ways to let a base product serve as a platform for all the variations. So if you have a shirt with several sizes and colors, then you can have custom pricing for each of those variants / options.

    <?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use CartLoad\Product\Item as ProductItem;
    use CartLoad\Cart\Item as CartItem;

    $shirt = new ProductItem([
        'id' => 1,
        'name' => 'Shirt',
        'sku' => 'shirt',
        'price' => [
            ['min_qty' => 1, 'max_qty' => 9, 'price' => 4.95],
            ['min_qty' => 10, 'max_qty' => 19, 'price' => 3.95],
        ],
        'options' => [
            [
                'id' => 1,
                'name' => 'Color',
                'required' => true,
                'items' => [
                    ['id' => 1, 'name' => 'Red', 'price' => 0.5, 'sku' => 'r'],
                    ['id' => 2, 'name' => 'Blue', 'price' => 0.4, 'sku' => 'b'],
                    ['id' => 3, 'name' => 'Green', 'price' => 0.6, 'sku' => 'g'],
                ]
            ],
            [
                'id' => 2,
                'name' => 'Size',
                'required' => true,
                'items' => [
                    ['id' => 4, 'name' => 'Small', 'price' => 1.0, 'sku' => 's'],
                    ['id' => 5, 'name' => 'Medium', 'price' => 1.1, 'sku' => 'm'],
                    ['id' => 6, 'name' => 'Large', 'price' => 1.2, 'sku' => 'l'],
                ]
            ],
        ]
    ]);

    //-- Blue Medium Shirt
    $cartItem = new CartItem([
        'id'         => 1,
        'product_id' => 1, //Shirt product ID
        'qty'        => 1,
        'options'    => [2, 5] // Blue, Medium
    ]);

    //-- The unit price of a blue medium shirt is 6.45
    $unit_price = $shirt->getPrice($cartItem);
    //-- The resulting SKU is then "shirt-b-m"
    $unit_sku = $shirt->getSku($cartItem);