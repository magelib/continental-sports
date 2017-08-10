<?php
namespace Continental\Banners\Block;
 
class Homepage extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Amasty\HelloWorld\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context, array $data = [],
        \Continental\Banners\Helper\Data $helper,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->_objectManager = $objectManager;
    }

    public function getBlockTitle() {
	return 'Jumpy' . $this->_helper->getBlockTitle();
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
