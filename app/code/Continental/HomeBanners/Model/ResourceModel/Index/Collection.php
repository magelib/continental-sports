<?php
/**
 * Copyright © 2017 Continental. All rights reserved.
 */

namespace Continental\HomeBanners\Model\ResourceModel\Index;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Continental\HomeBanners\Model\Index', 'Continental\HomeBanners\Model\ResourceModel\Index');
    }
}
