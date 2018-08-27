<?php

namespace Continental\Interactive\Model;

class Interactive extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Continental\Interactive\Model\ResourceModel\Interactive');
    }

    public function getAvailableStatuses()
    {
        $availableOptions = [
            '1' => 'Enable',
            '0' => 'Disable'
        ];

        return $availableOptions;
    }
}