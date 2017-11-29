<?php
namespace Continental\Interactive\Block\Adminhtml\Interactive;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;

class Selector extends Template {

    protected $_template = 'Continental_Interactive::selector.phtml';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Continental\Documents\Model\ResourceModel\Documents\CollectionFactory $collectionFactory,
        ObjectManagerInterface $objectManager
    )
    {
        $this->scopeConfig = $context->getScopeConfig();
        $this->collectionFactory = $collectionFactory;
        $this->objectManager = $objectManager;
         parent::__construct($context);
    }
}