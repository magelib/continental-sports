<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Block\Adminhtml;

/**
 * Adminhtml econtacts content block
 */
class Econtacts extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Me_Econtacts';
        $this->_controller = 'adminhtml_econtacts';
        $this->_headerText = __('Keep Contacts');
        parent::_construct();
    }
}
