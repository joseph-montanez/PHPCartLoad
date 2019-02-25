==========
Cart Events
==========

PHPCartLoad provides an event based reaction library to hook into events such as adding an item to cart. You are able to cancel events, this lets you add features such as validation to prevent the item from being added to or delete from the cart.

Current Cart

Adding Item To Cart
==================

.. code-block:: php

    $repository = new \CartLoad\Cart\Repositories\SessionRepository();
    $cart = new Container($repository);

    //-- Add the event listener
    $cart->addListener(CartAddItemBeforeEvent::NAME, function (CartAddItemBeforeEvent $event) {
        $item = $event->getItem();
        if ($item->getProductId() === 1 && $item->getQty() > 2) {
            //-- This will prevent the cart item from being added.
            $event->addError('Sorry the limit is 2 per customer');
        }


        $this->assertTrue($event->getCart() instanceof Container);
    });


    //-- This will fail to add to cart
    $item = Item::make([
        'id' => 1,
        'product_id' => 1,
        'qty' => 100,
    ]);
    $added = $cart->addItem($item); // Added is false because of the error

    $errors = $cart->getErrors(); //-- This will have the cart error from the event
