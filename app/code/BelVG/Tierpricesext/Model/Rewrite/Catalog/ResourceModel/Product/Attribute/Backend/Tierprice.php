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
namespace BelVG\Tierpricesext\Model\Rewrite\Catalog\ResourceModel\Product\Attribute\Backend;

class Tierprice extends \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Tierprice
{
    /**
     * Add percent column
     *
     * @param array $columns
     * @return array
     */
    protected function _loadPriceDataColumns($columns)
    {
        $currentColumns = parent::_loadPriceDataColumns($columns);
        $newColumns = [
            'percent' => 'percent'
        ];
        
        return $this->_addNewColumns($currentColumns, $newColumns);
    }
    
    protected function _addNewColumns($data, $columns)
    {
        foreach ($columns as $index => $name) {
            $data[$index] = $name;
        }
        
        return $data;
    }
    
}