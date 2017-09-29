<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_HidePrice
 */


namespace Amasty\HidePrice\Model\ResourceModel\Request;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Amasty\HidePrice\Model\Request', 'Amasty\HidePrice\Model\ResourceModel\Request');
    }

    public function deleteByIds($ids)
    {
        $this->getConnection()->delete(
            $this->getMainTable(),
            ['request_id IN(?)' => implode(',', $ids)]
        );
    }
}
