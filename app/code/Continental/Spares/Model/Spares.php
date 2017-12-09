<?php
/**
 * Copyright © 2017 Attercopia. All rights reserved.
 */

namespace Continental\Spares\Model;

class Spares extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Continental\Spares\Model\ResourceModel\Spares');
    }
}
