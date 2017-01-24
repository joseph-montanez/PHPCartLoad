<?php
namespace CartLoad\Product\Price;

use CartLoad\Product\Feature\MinMaxDateInterface;
use CartLoad\Product\Feature\MinMaxDateTrait;
use CartLoad\Product\Feature\MinMaxQtyInterface;
use CartLoad\Product\Feature\MinMaxQtyTrait;
use CartLoad\Product\Feature\PriceInterface;
use CartLoad\Product\Feature\PriceTrait;


class Bulk implements PriceInterface, MinMaxQtyInterface, MinMaxDateInterface
{
    use PriceTrait, MinMaxQtyTrait, MinMaxDateTrait;
}