<?php
/**
 * Copyright © 2017 Attercopia. All rights reserved.
 */

namespace Mirasvit\Kb\Model\ResourceModel;

class Document extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mst_kb_documents', 'document_id');
    }
}