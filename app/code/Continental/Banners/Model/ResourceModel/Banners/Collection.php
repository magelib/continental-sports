<?php
/**
 * Copyright © 2017 Continental. All rights reserved.
 */

namespace Continental\Banners\Model\ResourceModel\Banners;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Continental\Banners\Model\Banners', 'Continental\Banners\Model\ResourceModel\Banners');
    }
}
