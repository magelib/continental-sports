<?php
/**
 * Copyright © 2017 Attercopia. All rights reserved.
 */

namespace Attercopia\Spares\Model\ResourceModel;

class Spares extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('continental_spares', 'id');


    }
}
