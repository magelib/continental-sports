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

class CartSaveBefore implements ObserverInterface
{
    protected $_context;
    
    /**
     * @param \Magento\Framework\View\Element\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context
    ) {
        $this->_context = $context;
    }
    
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $cart = $observer->getCart();
        $quote = $cart->getQuote();
        $items = $quote->getAllItems();

        $qty = $this->calculateQty($quote);
        $this->recalculatePrice($quote, $qty);
        
        return $this;
    }
    
    public function calculateQty($quote)
    {
        $items = $quote->getAllVisibleItems();
        $result = [];
        foreach ($items as $item) {
            if ($item->getProduct()->getTypeId() == 'configurable') {
                $currentQty = isset($result[$item->getProductId()]) ? $result[$item->getProductId()] : 0;
                $result[$item->getProductId()] = $item->getQty();
            }
        }
        
        return $result;
    }
    
    public function recalculatePrice($quote, $qty)
    {
        $items = $quote->getAllVisibleItems(); 
        foreach($items as $item) {
            if ($item->getProduct()->getTypeId() == 'configurable') {
                $prodQty = isset($qty[$item->getProductId()]) ? $qty[$item->getProductId()] : 1;
                $price = $item->getProduct()->getFinalPrice($prodQty);
                $item->setCustomPrice($price);
                $item->setOriginalCustomPrice($price);
                $item->getProduct()->setIsSuperMode(true);
            }
        }    
    }
}
