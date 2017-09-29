<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mirasvit\Kb\Model\ResourceModel\Document;

use \Mirasvit\Kb\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'document_id';

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
        $this->_init('Mirasvit\Kb\Model\Document', 'Mirasvit\Kb\Model\ResourceModel\Document', 'document_id');
    }
}