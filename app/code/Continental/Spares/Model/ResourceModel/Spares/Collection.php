<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Continental\Spares\Model\ResourceModel\Spares;

use \Continental\Spares\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'spares_id';

    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Continental\Spares\Model\Spares', 'Continental\Spares\Model\ResourceModel\Spares');
        $this->_map['fields']['spares_id'] = 'main_table.spares_id';
    }
}
