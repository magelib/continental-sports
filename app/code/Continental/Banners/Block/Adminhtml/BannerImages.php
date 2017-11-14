<?php
namespace Continental\Banners

class BannerImages extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray

function _construct()
{
   $this->addColumn('name', ['label' => __('Name')]);
   $this->addColumn('value', ['label' => __('Value')]);
   $this->_addAfter = false;
   $this->_addButtonLabel = __('Add');
   parent::_construct();
}

}
