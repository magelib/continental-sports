<?php

namespace Continental\Documents\Model;

class Documents extends \Magento\Framework\Model\AbstractModel
{
        
        
    protected function _construct()
    {
        $this->_init('Continental\Documents\Model\ResourceModel\Documents');
    }
        
        
    public function getAvailableStatuses()
    {
                
                
        $availableOptions = ['1' => 'Enable',
                          '0' => 'Disable'];
                
        return $availableOptions;
    }
}
