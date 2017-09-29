<?php
/**
 * Copyright © 2017 Attercopia. All rights reserved.
 */
namespace Mirasvit\Kb\Model;

class Document extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Mirasvit\Kb\Model\ResourceModel\Document');
    }
}
