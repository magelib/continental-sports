<?php


namespace Continental\Documents\Model\ResourceModel\Documents;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Continental\Documents\Model\Documents',
            'Continental\Documents\Model\ResourceModel\Documents'
        );
    }
}
