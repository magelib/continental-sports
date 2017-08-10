<?php
namespace Continental\Banners\Block;
 
class Homepage extends \Magento\Framework\View\Element\Template
{

    protected $_helper;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = [],
        \Continental\Banners\Helper\Data $helper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->_objectManager = $objectManager;
        $this->_scopeConfig = $scopeConfig;
    }

    public function getBlockTitle() {
     /* Note to self; fix the Continental_Banners case in the helper files and this should all work */
        //return 'Jumpy' . $this->_scopeConfig->getBlockTitle();
        //return $this->_helper->getBlockTitle();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->_scopeConfig->getValue('Continental_Banners/general/block_text', $storeScope);
    }

    public function getBlockLabel(){
        return $this->_helper->getBlockLabel();
    }

    public function getTextAlign(){
        return $this->_helper->getTextAlign();
    }

    protected function _toHtml()
    {
       if ($this->_helper->getEnable()){
            return parent::_toHtml();
       }
        else {
            return '';
        }
    }

    public function getCollection()
    {
        $model = $this->_objectManager->create('Continental\Banners\Model\Banners');
        $collection = $model->getCollection();

        return $collection;
    }

    public function getContents()
    {
        return array('title' => 'Title');;
    }
}
