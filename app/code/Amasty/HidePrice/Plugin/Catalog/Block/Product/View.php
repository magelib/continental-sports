<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_HidePrice
 */


namespace Amasty\HidePrice\Plugin\Catalog\Block\Product;

use Magento\Catalog\Block\Product\View as MagentoView;

class View
{
    /**
     * @var \Amasty\HidePrice\Helper\Data
     */
    private $helper;

    public function __construct(
        \Amasty\HidePrice\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Hide Add to cart Button
     * @param MagentoView $subject
     * @param $result
     * @return string
     */
    public function afterToHtml(
        MagentoView $subject,
        $result
    ) {
        $matchedNames = [
            'product.info.addtocart.additional',
            'product.info.addtocart',
            'product.info.addtocart.bundle'
        ];

        if (in_array($subject->getNameInLayout(), $matchedNames)
            && $this->helper->getModuleConfig('information/hide_button')
            && $this->helper->isApplied($subject->getProduct())
        ) {
            $result = $this->helper->getNewAddToCartHtml();
        }

        return $result;
    }
}
