<?php
namespace Continental\Spares\Block\Adminhtml;

class SparesTab
{

    public function getTabUrl()
    {
        return $this->getUrl('continental_spares/spares/index/', ['_current' => true]);
    }

}