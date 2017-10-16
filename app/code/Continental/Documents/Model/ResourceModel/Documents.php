<?php

namespace Continental\Documents\Model\ResourceModel;

class Documents extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
        
        
           
    protected function _construct()
    {
        $this->_init('continental_documents', 'documents_id');
    }
}
