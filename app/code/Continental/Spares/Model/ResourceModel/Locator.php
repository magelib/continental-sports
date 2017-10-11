<?php
/**
 * Copyright © 2017 Attercopia. All rights reserved.
 */

namespace Continental\Spares\Model\ResourceModel;

class Locator extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('continental_sparesimages', 'spares_id');


    }
}
