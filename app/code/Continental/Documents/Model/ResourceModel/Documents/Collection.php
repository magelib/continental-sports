<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Continental\Documents\Model\ResourceModel\Documents;

use \Continental\Documents\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'documents_id';

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
        $this->_init('Continental\Documents\Model\Documents', 'Continental\Documents\Model\ResourceModel\Documents');
        $this->_map['fields']['documents_id'] = 'main_table.documents_id';
    }
}
