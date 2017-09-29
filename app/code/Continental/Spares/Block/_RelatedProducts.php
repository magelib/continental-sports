<?php
namespace Continental\Spares\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Registry;
use Magento\Catalog\Block\Product\AbstractProduct;

class RelatedProducts extends AbstractProduct
{
     /**
     * @var Filter
     */
    protected $_filter;

    protected $_productFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        parent::__construct($context);
        $this->_filter = $filter;
        $this->_categoryFactory = $categoryFactory;
        $this->_productFactory = $productFactory;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getRelatedProducts()
    {
        $_product = $this->_productFactory->create();

        if (!$_product->hasRelatedProducts()) {
            $products = [];
            $collection = $_product->getRelatedProductCollection()->addCategoriesFilter(array('in' => $this->getCategoryId('Spares')));
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $_product->setRelatedProducts($products);
        }
        return $this->getData('related_products');
    }

    public function getRelatedSpares() {
        $this->_filter->setData('field','category');
        $this->_filter->setData('value', $this->getCategoryId('Spares'));
        $this->_filter->setData('condition_type','like');
    }

    protected function getCategoryId($categoryTitle)
    {
        $collection = $this->_categoryFactory->create()->getCollection()->addAttributeToFilter('name', $categoryTitle)->setPageSize(1);

        if ($collection->getSize())
        {
            return $collection->getFirstItem()->getId();
        }
        return false;
    }

    public function getProductCollection() {
        $productCollection = $this->_productFactory->create()->getCollection();
        return $productCollection;
    }

}