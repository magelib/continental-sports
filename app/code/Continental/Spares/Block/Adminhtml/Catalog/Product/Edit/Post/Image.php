<?php
namespace Continental\Spares\Block\Adminhtml\Catalog\Product\Edit\Post;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;

class Image extends \Magento\Framework\View\Element\Template
{
     /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve product
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    public function getImages() {
        return true;
    }

}
