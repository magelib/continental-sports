<?php

	namespace Continental\HomeBanners\Model;

    class Banner extends \Magento\Framework\Model\AbstractModel
	{
    /**
     * Define resource model
	     */
    protected function _construct()
	    {
        $this->_init('Continental\HomeBanners\Model\Resource\Banner');
    }
}