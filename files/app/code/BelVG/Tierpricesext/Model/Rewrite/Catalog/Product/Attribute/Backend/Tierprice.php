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
namespace BelVG\Tierpricesext\Model\Rewrite\Catalog\Product\Attribute\Backend;

class Tierprice extends \Magento\Catalog\Model\Product\Attribute\Backend\Tierprice
{
    /**
     * Add percent to unique fields
     *
     * @param array $objectArray
     * @return array
     */
    protected function _getAdditionalUniqueFields($objectArray)
    {
        $uniqueFields = parent::_getAdditionalUniqueFields($objectArray);
        if (isset($objectArray['percent'])) {
            $val = ($objectArray['percent'] == 0)?'-1':'1';
        } else {
            $val = '-1';
        }
        $uniqueFields['percent'] = $val;
        
        return $uniqueFields;
    }
    
    public function afterLoad($object)
    {
        $storeId = $object->getStoreId();
        $websiteId = null;
        if ($this->getAttribute()->isScopeGlobal()) {
            $websiteId = 0;
        } elseif ($storeId) {
            $websiteId = $this->_storeManager->getStore($storeId)->getWebsiteId();
        }
        
        $data = $this->_getResource()->loadPriceData($object->getId(), $websiteId);
        
        foreach ($data as $k => $v) {
            $data[$k]['website_price'] = $v['price'];
            if ($v['all_groups']) {
                $data[$k]['cust_group'] = $this->_groupManagement->getAllCustomersGroup()->getId();
            }
        }
        
        if (!$object->getData('_edit_mode') && $websiteId) {
            $data = $this->preparePriceData($data, $object->getTypeId(), $websiteId);
        }
        
        if (!$object->getData('_edit_mode')) {
            foreach ($data as $key => $price) {
                if ($newPrice = $this->calculatePercentPrices($price, 'price', $object->getPrice())) {
                    unset($data[$key]['percent']);
                    $data[$key]['price'] = $newPrice;
                    $data[$key]['website_price'] = $this->calculatePercentPrices($price, 'website_price', $object->getPrice());
                }
            }
        }
        
        $object->setData($this->getAttribute()->getName(), $data);
        $object->setOrigData($this->getAttribute()->getName(), $data);
        
        $valueChangedKey = $this->getAttribute()->getName() . '_changed';
        $object->setOrigData($valueChangedKey, 0);
        $object->setData($valueChangedKey, 0);
        
        return $this;
    }
    
    public function calculatePercentPrices($data, $param = 'price', $basePrice)
    {
        if (isset($data[$param])) {
/* if percentage sign found */
	    exit($data['price']);
            if (preg_match('/.*%$/', $data['price'])) {
		exit("You have  entered a percentage bro");
	}
            if (isset($data['percent']) && ($data['percent'] >= 1)) {
                unset($data['percent']);
//                return ($basePrice * $data[$param] / 100);
                return ($basePrice - ($basePrice * ($data[$param] / 100) ) );
            }
        }
        
        return false;
    }
    
}
