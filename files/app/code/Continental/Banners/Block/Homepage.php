<?php
namespace Continental\Banners\Block;
 
class Homepage extends \Magento\Framework\View\Element\Template
{

    protected $_helper;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = [],
        \Continental\Banners\Helper\Data $helper,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->_objectManager = $objectManager;
    }

    public function getBlockTitle() {
     /* Note to self; fix the Continental_Banners case in the helper files and this should all work */
        //return 'Jumpy' . $this->_scopeConfig->getBlockTitle();
        //return $this->_helper->getBlockTitle();
        #$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        #return $this->_scopeConfig->getValue('Continental_Banners/general/block_text', $storeScope);
        return $this->_helper->getBlockTitle();
    }

    public function getBlockBack(){
        return $this->_helper->getBlockBack();
    }
    
    public function getBlockText(){
        return $this->_helper->getBlockText();
    }
    
    public function getBlockHref(){
        return $this->_helper->getBlockHref();
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
