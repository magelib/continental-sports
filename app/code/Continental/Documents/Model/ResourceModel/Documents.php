<?php


namespace Continental\Documents\Model\ResourceModel;

class Documents extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('continental_documents_documents', 'documents_id');
    }
}
