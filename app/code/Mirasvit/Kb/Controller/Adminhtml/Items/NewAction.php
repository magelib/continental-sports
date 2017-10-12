<?php
/**
 * Copyright © 2015 Attercopia. All rights reserved.
 */

namespace Attercopia\Homepage_banner\Controller\Adminhtml\Items;

class NewAction extends \Attercopia\Homepage_banner\Controller\Adminhtml\Items
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
