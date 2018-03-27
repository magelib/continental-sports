<?php

namespace Continental\General\Block\System\Config\Form\Field;

class Attributes extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{

    protected $_columns = [];

    protected $_attributesSetRenderer;

    protected $_addAfter = true;

    protected $_addButtonLabel;

    protected function _construct()
    {
        parent::_construct();
        $this->_addButtonLabel = __('Add');
    }

    protected function _prepareToRender()
    {
        $this->addColumn('postcode', array('label' => __('Postcodes')));
        $this->addColumn('rate', array('label' => __('Rate')));
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    public function renderCellTemplate($columnName)
    {
        if ($columnName == "active") {
            $this->_columns[$columnName]['class'] = 'input-text required-entry';
            $this->_columns[$columnName]['style'] = 'width:100px';
        }
        return parent::renderCellTemplate($columnName);
    }
}