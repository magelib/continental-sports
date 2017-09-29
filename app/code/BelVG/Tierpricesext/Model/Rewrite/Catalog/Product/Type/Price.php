<?php
/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
 ********************************************************************
 * @category   BelVG
 * @package    BelVG_Tierpricesext
 * @copyright  Copyright (c) 2010 - 2016 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */
namespace BelVG\Tierpricesext\Model\Rewrite\Catalog\Product\Type;

class Price extends \Magento\Catalog\Model\Product\Type\Price
{
    /**
     * Gets the 'tear_price' array from the product
     *
     * @param Product $product
     * @param string $key
     * @param bool $returnRawData
     * @return array
     */
    protected function getExistingPrices($product, $key, $returnRawData = false)
    {
        $attribute = $product->getResource()->getAttribute($key);
        if ($attribute) {
            $attribute->getBackend()->afterLoad($product);
        }
        
        return parent::getExistingPrices($product, $key, $returnRawData);
    }
    
}