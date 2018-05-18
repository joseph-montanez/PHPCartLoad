<?php

namespace CartLoad\Cart\Item;


use CartLoad\Cart\Item;
use CartLoad\Product\Feature\SkuInterface;
use CartLoad\Product\Product;

class SkuProcessor
{

    /**
     * @param Item $item
     * @param \CartLoad\Product\Product $product
     * @param \DateTime $now
     *
     * @return string
     */
    public function getSku(Item $item, Product $product, \DateTime $now = null)
    {
        //-- If this is a CartItem and the Product has a matching computed combination, then return the price from the
        // combination
        if (count($product->getCombinations()) > 0) {
            $combination = $item->getProductCombination($product);
            if ($combination) {
                return $combination->getSku();
            }
        }

        $sku = $product->getSku();

        //-- Get the configuration price
        if (count($product->getVariations()) > 0) {
            $default_effect = $item->getVariations() instanceof SkuInterface ? $item->getVariations()->getSkuEffect() : SkuInterface::SKU_END_OF;
            $default_delimiter = $item->getVariations() instanceof SkuInterface ? $item->getVariations()->getSkuDelimiter() : '-';

            $sku_list = [
                'replaces' => [],
                'starts' => [],
                'ends' => [],
            ];
            foreach ($product->getVariations() as $variation_set) {
                if ($variation_set->hasVariationIds($item->getVariations())) {
                    $variation_set_skus = $variation_set->calculateSkus($item, $now);
                    if (count($variation_set_skus['replaces']) > 0) {
                        $sku_list['replaces'] = array_merge($sku_list['replaces'], $variation_set_skus['replaces']);
                    }
                    if (count($variation_set_skus['starts']) > 0) {
                        $sku_list['starts'] = array_merge($sku_list['starts'], $variation_set_skus['starts']);
                    }
                    if (count($variation_set_skus['ends']) > 0) {
                        $sku_list['ends'] = array_merge($sku_list['ends'], $variation_set_skus['ends']);
                    }
                }
            }

            //-- If the SKU is to replace then use the follow logic.
            if (count($sku_list['replaces']) > 0) {
                $sku = array_reduce($sku_list['replaces'], function ($result, $sku_data) use ($default_delimiter) {
                    list($sku, $delimiter) = $sku_data;
                    if ($delimiter === null) {
                        $delimiter = $default_delimiter;
                    }

                    if (strlen($result) > 0) {
                        $result = implode($delimiter, [$result, $sku]);
                    } else {
                        $result = $sku;
                    }

                    return $result;
                }, $sku);
            } else {
                //-- Prepend anything to the beginning of the SKU
                if (count($sku_list['starts']) > 0) {
                    $sku_starts = array_reduce($sku_list['starts'], function ($result, $sku_data) use ($default_delimiter) {
                        list($starts_sku, $starts_delimiter) = $sku_data;
                        if ($starts_delimiter === null) {
                            $starts_delimiter = $default_delimiter;
                        }

                        if (strlen($result) > 0) {
                            $sku_parts = [$starts_sku, $result];
                            $filtered_sku_parts = array_filter($sku_parts, function ($var) {
                                return $var !== null && $var !== false;
                            });
                            $result = implode($starts_delimiter, $filtered_sku_parts);
                        } else {
                            $result = $starts_sku;
                        }

                        return $result;
                    }, '');


                    $sku_parts = [$sku, $sku_starts];
                    $filtered_sku_parts = array_filter($sku_parts, function ($var) {
                        return $var !== null && $var !== false;
                    });
                    $sku = implode($default_delimiter, $filtered_sku_parts);
                }

                //-- Append anything to the beginning of the SKU
                if (count($sku_list['ends']) > 0) {
                    $sku = array_reduce($sku_list['ends'], function ($result, $sku_data) use ($default_delimiter) {
                        list($sku_data, $delimiter) = $sku_data;
                        if (is_array($sku_data)) {
                            $sku_effect = $sku_data['effect'];
                            $sku = $sku_data['sku'];
                        } else {
                            $sku = $sku_data;
                        }
                        if ($delimiter === null) {
                            $delimiter = $default_delimiter;
                        }

                        if (strlen($result) > 0) {
                            $result = implode($delimiter, [$result, $sku]);
                        } else {
                            $result = $sku;
                        }

                        return $result;
                    }, $sku);
                }
            }
        }

        return $sku;
    }
}