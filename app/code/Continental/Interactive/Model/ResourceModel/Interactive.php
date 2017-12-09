<?php

namespace Continental\Interactive\Model\ResourceModel;

class Interactive extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
        
        
           
    protected function _construct()
    {
        $this->_init('continental_interactive_spare', 'continental_interactive_spare_id');
    }
}
