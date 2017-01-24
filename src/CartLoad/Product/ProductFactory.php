<?php namespace CartLoad\Product;


use CartLoad\Product\Variation\VariationSet;

class ProductFactory
{

    /**
     * @param array $data
     * @param Product $product
     * @return Product
     */
    public function make(array $data, Product $product = null)
    {
        if (is_null($product)) {
            $product = new Product();
        }
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'id':
                    $product->setId($value);
                    break;
                case 'name':
                    $product->setName($value);
                    break;
                case 'description':
                    $product->setDescription($value);
                    break;
                case 'sku':
                    $product->setSku($value);
                    break;
                case 'weight':
                    $product->setWeight($value);
                    break;
                case 'price':
                    $price_table = (new PriceTableFactory())->make($value);
                    $product->setPriceTable($price_table);
                    break;
                case 'variations':
                    $variation_sets = array_map(function ($variation_set) {
                        return new VariationSet($variation_set);
                    }, $value);
                    $product->setVariations($variation_sets);
                case 'combinations':
                    /**
                     * @var Combination[]
                     */
                    $combinations = array_map(function ($combination_data) {
                        return (new CombinationFactory())->make($combination_data);
                    }, $value);
                    $product->setCombinations($combinations);
            }
        }

        return $product;
    }
}