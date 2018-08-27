<?php

namespace Wiley\General\Block\Adminhtml\Form\Field;

class AttributesSet extends \Magento\Framework\View\Element\Html\Select {

    protected $groupfactory;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Catalog\Model\Product\AttributeSet\Options $groupfactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->groupfactory = $groupfactory;
    }

    public function _toHtml() {
        if (!$this->getOptions()) {

            foreach ($this->groupfactory->toOptionArray() as $attrSet) {
                $this->addOption($attrSet['value'], $attrSet['label']);
            }
        }
        return parent::_toHtml();
    }

    public function setInputName($value) {
        return $this->setName($value);
    }
}