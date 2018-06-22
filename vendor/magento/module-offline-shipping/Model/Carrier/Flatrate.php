<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\OfflineShipping\Model\Carrier;

use Magento\OfflineShipping\Model\Carrier\Flatrate\ItemPriceCalculator;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;

/**
 * Flat rate shipping model
 */
class Flatrate extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'flatrate';

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $_rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $_rateMethodFactory;

    /**
     * @var ItemPriceCalculator
     */
    private $itemPriceCalculator;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param ItemPriceCalculator $itemPriceCalculator
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\OfflineShipping\Model\Carrier\Flatrate\ItemPriceCalculator $itemPriceCalculator,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->itemPriceCalculator = $itemPriceCalculator;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @param RateRequest $request
     * @return Result|bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $freeBoxes = $this->getFreeBoxesCount($request);
        $this->setFreeBoxes($freeBoxes);

        /** @var Result $result */
        $result = $this->_rateResultFactory->create();

        $shippingPrice = $this->getShippingPrice($request, $freeBoxes);

        if ($shippingPrice !== false) {

            $requires = false;
            foreach ($request->getAllItems() as $item) {
                $product = $item->getProduct();
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $product = $objectManager->create('Magento\Catalog\Model\Product')->load($product->getId());
                if($product->getData('installation_required') == 1) {
                    $requires = true;
                    break;
                }
            }

            $textTo = $requires ? __('Installation required') : '';
            $method = $this->createResultMethod($shippingPrice, $textTo);
            $result->append($method);
        }

        return $result;
    }

    /**
     * @param RateRequest $request
     * @return int
     */
    private function getFreeBoxesCount(RateRequest $request)
    {
        $freeBoxes = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    $freeBoxes += $this->getFreeBoxesCountFromChildren($item);
                } elseif ($item->getFreeShipping()) {
                    $freeBoxes += $item->getQty();
                }
            }
        }
        return $freeBoxes;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['flatrate' => $this->getConfigData('name')];
    }

    /**
     * @param RateRequest $request
     * @param int $freeBoxes
     * @return bool|float
     */
    private function getShippingPrice(RateRequest $request, $freeBoxes)
    {
        $shippingPrice = false;

        $configPrice = $this->getConfigData('price');
        if ($this->getConfigData('type') === 'O') {
            // per order
            $shippingPrice = $this->itemPriceCalculator->getShippingPricePerOrder($request, $configPrice, $freeBoxes);
        } elseif ($this->getConfigData('type') === 'I') {
            // per item
            $shippingPrice = $this->itemPriceCalculator->getShippingPricePerItem($request, $configPrice, $freeBoxes);
        }

        $shippingPrice = $this->getFinalPriceWithHandlingFee($shippingPrice);

        if ($shippingPrice !== false && (
                $request->getFreeShipping() === true || $request->getPackageQty() == $freeBoxes
            )
        ) {
            $shippingPrice = '0.00';
        }

        $longMatch = false;
        $postalCode = $request->getData('dest_postcode');

        $this->getAllPostCodes();
     
        foreach ($request->getAllItems() as $item) {
            $product = $item->getProduct();
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $product = $objectManager->create('Magento\Catalog\Model\Product')->load($product->getId());
            if($product->getData('installation_required') == 1) {
                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                $vals = $this->_scopeConfig->getValue('continental-attributes/attributes_configurations/attribute_set_top',
                    $storeScope);
               
               $postcodes = $this->getAllPostCodes();

               foreach ($postcodes as $index => $arr) {
                    foreach ($arr as $i => $row) {
                        $code = trim($row['code']);
                        if ((substr($postalCode, 0, strlen($code)) === $code) || ($code == $postalCode)) {
                            $shippingPrice = $row['rate'];
                            break 2;
                        }
                    }
                }               
            }
        }
        return $shippingPrice;
    }

    function debugShip($str) {
        error_log($str  . PHP_EOL, 3, '/var/www/continental-staging/test.txt'); 
    }

    function getAllPostCodes() {
        $postcodes = [];
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $vals = $this->_scopeConfig->getValue('continental-attributes/attributes_configurations/attribute_set_top',
                    $storeScope);
                foreach (unserialize($vals) as $val) {
                    foreach (explode(',', $val['postcode']) as $code) {
                        $code = trim($code);
                        $pLen = strlen($code);
                        if (!isset($postcodes[$pLen])) {
                            $postcodes[$pLen][0] = array("code" => $code, "rate" => $val['rate']);
                        } else {
                            $postcodes[$pLen][] = array("code" => $code, "rate" => $val['rate']);
                        }
                        
                    }
                }
        return array_reverse($postcodes);
    }
    
    /**
    * @param string $code postcode
    * @return string $code formatted code (uppercase alphanumeric only)
    */
    private function formatPostCodes($code) {
        $code = strtoupper($code);
        $code = preg_replace('/[^A-Z0-9]/','', $code);
        return $code;
    }

    /**
     * @param int|float $shippingPrice
     * @return \Magento\Quote\Model\Quote\Address\RateResult\Method
     */
    private function createResultMethod($shippingPrice, $requires_installation = '')
    {
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier('flatrate');
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod('flatrate');
        if($requires_installation) {
            $method->setMethodTitle($requires_installation . $this->getConfigData('name'));
        }
        else {
            $method->setMethodTitle($this->getConfigData('name'));
        }

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);
        return $method;
    }

    /**
     * @param mixed $item
     * @return mixed
     */
    private function getFreeBoxesCountFromChildren($item)
    {
        $freeBoxes = 0;
        foreach ($item->getChildren() as $child) {
            if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                $freeBoxes += $item->getQty() * $child->getQty();
            }
        }
        return $freeBoxes;
    }
}
