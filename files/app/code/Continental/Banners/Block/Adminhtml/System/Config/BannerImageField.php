<?php
namespace Continental\Banners\Block\Adminhtml\System\Config;

class BannerImageField extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /***
     * @var $_checkboxRenderer
     */
    protected $_checkboxRenderer;
/*
    public function __construct(\Continental\Banners\Block\Adminhtml\System\Config\Checkbox $checkboxRenderer) {
        $this->_checkboxRenderer = $checkboxRenderer;
    }

    public function __construct(
       // \Continental\Banners\Block\Adminhtml\System\Config\Checkbox $checkboxRenderer,
       // \Magento\Framework\View\Element\Template\Context $context
    ) {
       // $this->_checkboxRenderer = $checkboxRenderer;
       // parent::__construct($context);
    }
*/
    protected function _prepareToRender()
    {
//        $this->addColumn('active', ['label' => __('Active'), 'renderer' => $this->_checkboxRenderer]);
        $this->addColumn('active', ['label' => __('ActiveO'), 'type' => 'checkbox', 'renderer' => false]);
        $this->addColumn('bacon', ['label' => __('Active2'), 'type' => 'file']);
        $this->addColumn('bg_image', ['label' => __('Image'),  'renderer' => false]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
