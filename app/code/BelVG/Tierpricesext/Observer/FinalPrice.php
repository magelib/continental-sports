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
namespace BelVG\Tierpricesext\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Cart as CustomerCart;

class FinalPrice implements ObserverInterface
{
    /**
     * Https request
     *
     * @var \Zend\Http\Request
     */
    protected $_request;
    protected $_layout;
    protected $_cart;
    protected $_confModel;
    protected $_scopeConfig;
    
    /**
     * @param Item $item
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $conf,
        CustomerCart $cart
    ) {
        $this->_layout = $context->getLayout();
        $this->_request = $context->getRequest();
        $this->_confModel = $conf;
        $this->_cart = $cart;
        $this->_scopeConfig = $context->getScopeConfig();
    }
    
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $isEnabled = $this->_scopeConfig->getValue(
            'tierpricesext/settings/price_calc_conf',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        if ($isEnabled) {
            $product = $observer->getProduct();
            $tierPrice = $this->calculateConfTierPricing($product);
            if ($tierPrice && ($tierPrice < $product->getData('final_price'))) {
                $product->setData('final_price', $tierPrice);
            }
        }
        
        return $this;
    }
    
    public function calculateConfTierPricing($product)
    {
        if ($this->_request->getRouteName() == 'checkout') {
            $quote = $this->_cart->getQuote();
            $idQuantities = [];
            foreach ($quote->getAllVisibleItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                
                $id = $item->getProductId();
                $childProdIds = $this->_confModel->getChildrenIds($id);
                $childProdIds = is_array($childProdIds)?reset($childProdIds):$childProdIds;
                
                foreach ($childProdIds as $childId) {
                    $idQuantities[$childId][] = $item->getQty();
                }
            }
            
            if (array_key_exists($product->getId(), $idQuantities)) {
                $totalQty  = array_sum($idQuantities[$product->getId()]);
                $tierPrice = $product->getPriceModel()->getBasePrice($product, $totalQty);
            }
            
            return $tierPrice;
        }
        
        return false;
    }
    
}