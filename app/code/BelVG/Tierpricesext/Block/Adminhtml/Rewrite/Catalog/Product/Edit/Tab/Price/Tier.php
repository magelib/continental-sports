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
namespace BelVG\Tierpricesext\Block\Adminhtml\Rewrite\Catalog\Product\Edit\Tab\Price;

/**
 * Adminhtml tier price item renderer
 */
class Tier extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Price\Tier
{
    /**
     * @var string
     */
    protected $_template = 'rewrite/catalog/product/edit/price/tier.phtml';
    
}