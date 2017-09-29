<?php
/**
 * Magentbro
 */
namespace Continental\Spares\Block\Adminhtml\Hello\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('hello_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Your Title'));
    }

    /**
     * Prepare Layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'hello',
            [
                'label' => __('Products'),
                'url' => $this->getUrl('hello/*/grid', ['_current' => true]),
                'class' => 'ajax'
            ]
        );
        return parent::_prepareLayout();
    }

}