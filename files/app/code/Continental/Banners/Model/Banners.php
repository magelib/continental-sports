<?php
/**
 * Copyright © 2017 Attercopia. All rights reserved.
 */

namespace Continental\Banners\Model\ResourceModel;

class Banners extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Continental\Banners\Model\ResourceModel\Banners');
    }
}
