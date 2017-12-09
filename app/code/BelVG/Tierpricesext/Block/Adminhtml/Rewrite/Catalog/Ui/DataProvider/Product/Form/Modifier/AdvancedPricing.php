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
namespace BelVG\Tierpricesext\Block\Adminhtml\Rewrite\Catalog\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Directory\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Price;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Modal;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form\Element\Checkbox;

/**
 * Class AdvancedPricing
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AdvancedPricing extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AdvancedPricing /*AbstractModifier*/
{
    /**
     * Get tier price dynamic rows structure
     *
     * @param string $tierPricePath
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function getTierPriceStructure($tierPricePath)
    {
        $percentElement = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => Checkbox::NAME,
                        'componentType' => Field::NAME,
                        'dataType' => Number::NAME,
                        'label' => __('Percent'),
                        'dataScope' => 'percent',
                        'valueMap' => [
                            'false' => '0',
                            'true' => '1',
                        ]
                    ],
                ],
            ],
        ];
            
        $parentStructure = parent::getTierPriceStructure($tierPricePath);
        $fileds = $parentStructure['children']['record']['children'];
        $fileds = $this->arrayInsertBefore('price', $fileds, 'percent', $percentElement);
        $parentStructure['children']['record']['children'] = $fileds;
        
        return $parentStructure;
    }
    
    public function arrayInsertBefore($key, array &$array, $new_key, $new_value)
    {
        if (array_key_exists($key, $array)) {
            $new = array();
            foreach ($array as $k => $value) {
                if ($k === $key) {
                    $new[$new_key] = $new_value;
                }
                $new[$k] = $value;
            }
            return $new;
        }
        return FALSE;
    }

}