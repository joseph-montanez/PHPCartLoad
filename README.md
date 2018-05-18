# PHPCartLoad [![Build Status](https://travis-ci.org/joseph-montanez/PHPCartLoad.svg?branch=master)](https://travis-ci.org/joseph-montanez/PHPCartLoad) #

[![codecov](https://codecov.io/gh/joseph-montanez/PHPCartLoad/branch/master/graph/badge.svg)](https://codecov.io/gh/joseph-montanez/PHPCartLoad)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/326e83e2-7e5b-4071-a44d-a3ce71117982/mini.png)](https://insight.sensiolabs.com/projects/326e83e2-7e5b-4071-a44d-a3ce71117982)

PHPCartLoad is a library intended to help build shopping carts and general e-commerce applications. This library is to provide a foundation of easy to use classes to manage inventory pricing and carts. There is zero database requirements and is completely data driven.

## License

The library is MIT, do what you want with it.

## To Do

This is still alpha and undergoing changes for a better API. The following is to come:

 - Reach 100% code coverage
 - Create tests for weight
 - Finish Cart API
 - Consider multi-currency, multi-lingual support
 - Stock API
   - In Stock
   - Drop Shipped
   - Back Ordered (Including support for limiting to the amount back ordered)
 - Shipping API
   - Initial Support For USPS
   - Guidelines for more shipping providers
   - Math stuff for calculating box sizes based on product dimensions and weight
 - Order API
 - Invoice API

## Examples

Here are some examples of what is current available in the library.

 - [Simple Pricing](#simple-pricing)
 - [Bulk Pricing](#bulk-pricing)
 - [SKU Variations](#sku-variations)
 - [Combinations](#combinations)
 - [Cart Events](#cart)

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

### Cart Events

A generic cart system is provided with a PHP Session ($_SESSION) driver. Its possible to interject events to provide basic features like validation.

    <?php
    
    use CartLoad\Cart\Container;
    use CartLoad\Cart\Events\CartAddItemBeforeEvent;
    use CartLoad\Cart\Events\CartGetItemAfterEvent;
    use CartLoad\Cart\Item;
    use CartLoad\Cart\Repositories\Session;
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
    $repository = new Session();
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
        // array(1) {
        //    ["does-not-exist"]=> string(33) "Sorry this product "i do not exist" does not exist"
        // }
    
        foreach ($errors as $key => $error) {
            echo '"', $key, '" - ', $error, PHP_EOL;
        }
    }

## How To Build The Documentation

You will need python install on your computer.

    cd docs
    pip install -r requirements.txt
    make