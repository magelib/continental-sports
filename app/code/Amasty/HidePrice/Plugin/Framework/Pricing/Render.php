<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_HidePrice
 */


namespace Amasty\HidePrice\Plugin\Framework\Pricing;

use Magento\Framework\Pricing\Render as PricingRender;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\Price\PriceInterface;

class Render
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventManager;
    /**
     * @var \Amasty\HidePrice\Helper\Data
     */
    private $helper;
    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;

    /**
     * Render constructor.
     *
     * @param \Magento\Customer\Model\Session            $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry                $coreRegistry
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Amasty\HidePrice\Helper\Data $helper
    ) {
        $this->customerSession = $customerSession;
        $this->storeManager    = $storeManager;
        $this->coreRegistry    = $coreRegistry;
        $this->eventManager    = $eventManager;
        $this->helper = $helper;
        $this->jsonEncoder = $jsonEncoder;
    }

    /**
     * @param PricingRender $subject
     * @param callable $proceed
     * @param $priceCode
     * @param SaleableInterface $saleableItem
     * @param array $arguments
     * @return string
     */
    public function aroundRender(
        PricingRender $subject,
        callable $proceed,
        $priceCode,
        SaleableInterface $saleableItem,
        array $arguments = []
    ) {
        if ($this->isNeedRenderPrice($saleableItem, $arguments)) {
            // Show Price Box
            $result = $proceed($priceCode, $saleableItem, $arguments);
            return $result;
        }

        return $this->getNewPriceHtmlBox($saleableItem, $priceCode, $arguments);
    }

    /**
     * @param PricingRender $subject
     * @param callable $proceed
     * @param AmountInterface $amount
     * @param PriceInterface $price
     * @param SaleableInterface $saleableItem
     * @param array $arguments
     * @return string
     */
    public function aroundRenderAmount(
        PricingRender $subject,
        callable $proceed,
        AmountInterface $amount,
        PriceInterface $price,
        SaleableInterface $saleableItem = null,
        array $arguments = []
    ) {
        if ($this->isNeedRenderPrice($saleableItem, $arguments)) {
            // Show Price Box
            $result = $proceed($amount, $price, $saleableItem, $arguments);
            return $result;
        }

        return '';
    }

    /**
     * @param $saleableItem
     * @param $arguments
     *
     * @return bool
     */
    private function isNeedRenderPrice($saleableItem, $arguments)
    {
        // if Item not a product - show price
        $isNotProduct = !($saleableItem instanceof ProductInterface);
        // is current price block zone is not list or view
        $isNoZone = (key_exists('zone', $arguments)
            && !in_array($arguments['zone'], [PricingRender::ZONE_ITEM_LIST, PricingRender::ZONE_ITEM_VIEW]));

        $isShowPrice = !$this->helper->isModuleEnabled()
            || $isNotProduct
            || $isNoZone
            || !$this->helper->isNeedHideProduct($saleableItem);

        return $isShowPrice;
    }

    private function getNewPriceHtmlBox($saleableItem, $priceCode, $arguments)
    {
        $html = '';
        /* get price replacement only for final price - others is hided*/
        if (in_array($priceCode, ['final_price', 'wishlist_configured_price'])) {
            $html = $this->helper->getNewPriceHtmlBox($saleableItem);
            /* hack for hiding Add to Cart button on category page - with javascript*/
            if (array_key_exists('zone', $arguments)
                && $arguments['zone'] == PricingRender::ZONE_ITEM_LIST
                && $this->helper->getModuleConfig('information/hide_button')
            ) {
                $html .= $this->generateJsHideOnCategory($saleableItem->getId());
            }
        }

        return $html;
    }

    /**
     * Js for for hiding product button on category page
     * @param $productId
     * @return string
     */
    private function generateJsHideOnCategory($productId)
    {
        $productId = 'amhideprice-product-id-' . $productId;
        $html = '<span id="' . $productId . '" style="display: none !important;"></span>
         <script>
            require([
                "jquery",
                 "Amasty_HidePrice/js/amhideprice"
            ], function ($, amhideprice) {
                $( document ).ready(function() {                     
                    $("#' . $productId . '").amhideprice('.
                        $this->jsonEncoder->encode([
                            'parent' => $this->helper->getModuleConfig('developer/parent'),
                            'button' => $this->helper->getModuleConfig('developer/addtocart'),
                            'html'   => $this->helper->getNewAddToCartHtml(),
                            'hide_compare' => $this->helper->getModuleConfig('information/hide_compare'),
                            'hide_wishlist' => $this->helper->getModuleConfig('information/hide_wishlist')
                        ])
                    .');
                });
            });
        </script>';

        return $html;
    }
}
