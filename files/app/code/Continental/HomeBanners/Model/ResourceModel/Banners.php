<?php
/**
 * Copyright © 2015 Continental. All rights reserved.
 */

namespace Continental\HomeBanners\Model\ResourceModel;

class Banners extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('continental_homebanners', 'id');
    }
}
