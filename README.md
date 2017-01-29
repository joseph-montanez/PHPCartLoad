# PHPCartLoad [![Build Status](https://travis-ci.org/joseph-montanez/PHPCartLoad.svg?branch=master)](https://travis-ci.org/joseph-montanez/PHPCartLoad) #

[![codecov](https://codecov.io/gh/joseph-montanez/PHPCartLoad/branch/master/graph/badge.svg)](https://codecov.io/gh/joseph-montanez/PHPCartLoad)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/326e83e2-7e5b-4071-a44d-a3ce71117982/mini.png)](https://insight.sensiolabs.com/projects/326e83e2-7e5b-4071-a44d-a3ce71117982)

PHPCartLoad is a library intended to help build shopping carts and general e-commerce applications. This library is intended to provide a foundation of easy to use classes to manage inventory pricing and carts. This is a migration of my F# project so there is more to come.

## License

The library is MIT, do what you want with it.

## To Do

This is still alpha and undergoing changes for a better API. The following is to change:

 - Create tests for weight
 - Cart Container API
 - Validation / Contract Based Programming
 - Events
 - Shipping API
   - Initial Support For USPS
   - Guidelines for more shipping providers
   - Math stuff for calculating box sizes based on product dimensions and weight

## Examples

Here are some examples of what is current available in the library.

 - [Simple Pricing](#simple-pricing)
 - [Bulk Pricing](#bulk-pricing)
 - [SKU Variations](#sku-variations)
 - [Combinations](#combinations)

### Simple Pricing

This is the Hello World of this library, showing you how little you have to do to start to get the library working with your existing code base.

    <?php
    require_once __DIR__ . '/../vendor/autoload.php';
    
    use CartLoad\Product\Product;
    
    
    $apple = Product::make([
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
    require_once __DIR__ . '/../vendor/autoload.php';
    
    use CartLoad\Product\Product;

    $apple = new Product([
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
    
    use CartLoad\Product\Product;
    use CartLoad\Cart\Item;
    
    $shirt = Product::make([
        'id' => 1,
        'name' => 'Shirt',
        'sku' => 'shirt',
        'price' => [
            ['min_qty' => 1, 'max_qty' => 9, 'price' => 4.95],
            ['min_qty' => 10, 'max_qty' => 19, 'price' => 3.95],
        ],
        'variations' => [
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
    $cartItem = Item::make([
        'id'         => 1,
        'product_id' => 1, //Shirt product ID
        'qty'        => 1,
        'variations' => [2, 5] // Blue, Medium
    ]);
    
    //-- The unit price of a blue medium shirt is 6.45
    $unit_price = $cartItem->getPrice($shirt);
    //-- The resulting SKU is then "shirt-b-m"
    $unit_sku = $cartItem->getSku($shirt);
    
### Combinations

Combinations are ways to customize the resulting product variants. This could be as simple as saying a certain product
configuration is not available, or maybe you need a special price, SKU or weight for a specific combination. Another
possibility if you need to track stock for the variations, instead these can be linked to individual products for
greater customizations.

    <?php
    
    require_once __DIR__ . '/../vendor/autoload.php';
    
    use CartLoad\Product\Product;
    use CartLoad\Cart\Item;
    
    $shirt = Product::make([
        'id' => 1,
        'name' => 'Shirt',
        'sku' => 'shirt',
        'price' => [
            ['min_qty' => 1, 'max_qty' => 9, 'price' => 4.95],
            ['min_qty' => 10, 'max_qty' => 19, 'price' => 3.95],
        ],
        'variations' => [
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
        ],
        'combinations' => [
            //-- Blue Medium Shirt on Sale with special SKU to track all blue shirt sales in main system
            [
                'id' => 1,
                'variations' => [2, 5],
                'price' => 5.00,
                'sku' => 'shirt-b-m-sale',
            ]
        ]
    ]);
    
    //-- Blue Medium Shirt
    $cartItem = Item::make([
        'id'         => 1,
        'product_id' => 1, //Shirt product ID
        'qty'        => 1,
        'variations' => [2, 5] // Blue, Medium
    ]);
    
    //-- The unit price of a blue medium shirt is 5.00
    $unit_price = $shirt->getCartPrice($cartItem);
    //-- The resulting SKU is then "shirt-b-m-sale"
    $unit_sku = $shirt->getCartSku($cartItem);
    
    echo $unit_sku, ' - ', $unit_price;