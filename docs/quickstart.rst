==========
Quickstart
==========

This page provides a quick introduction to PHPCartLoad and introductory examples.
If you have not already installed, PHPCartLoad, head over to the :ref:`installation`
page.

Creating a Product
==================

With PHPCartLoad, you are given a very simple starting point for products, and its up to you to decide what is important to you. You can use the built-in traits to quickly cherry-pick product features to want to use such as date ranges, min / max quantity, and more.

.. code-block:: php

    use CartLoad\Product\Product;

    $apple = Product::make([
        'name' => 'Apple',
        'sku' => 'a',
        'price' => 19.95
    ]);

    $qty = 10;


    //-- This will return the simple price: 19.95
    echo $apple->getPrice($qty);


Understanding "::make"
----------------------

If you have noticed, with creating a product, you have called a static method on top of Product. PHPCartLoad does not want to impose any type of configuration as a requirement. The array style configuration is completely optional as instead you can use the rich API available to create a product.

.. code-block:: php

    use CartLoad\Product\Product;

    $apple = new Product();
    $apple->setName('Apple')
        ->setSku('a')
        ->setPrice(19.95);

So why choose PHP arrays to configure a product? Well for starters what if you want to use YAML or JSON? these easily map to PHP arrays and can be thrown into a factory style generator. Again, this is completely up to you can choose to embrace it or build your own. There are ways to extend the make feature to include new ways to setup a
product, and this is available in the :ref:`factories` documentation.