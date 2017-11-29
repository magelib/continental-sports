<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Continental\Interactive\Model\ResourceModel\Interactive;

use \Continental\Interactive\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'continental_interactive_spare_id';

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
        $this->_init('Continental\Interactive\Model\Interactive', 'Continental\Interactive\Model\ResourceModel\Interactive');
        $this->_map['fields']['continental_interactive_spare_id'] = 'main_table.continental_interactive_spare_id';
    }
}
