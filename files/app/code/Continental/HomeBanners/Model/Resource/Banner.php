<?php
    namespace Continental\HomeBanners\Model\Resource;

	class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
	{
	    /**
	     * Define main table
	     */
	    protected function _construct()
    {
	        $this->_init('continental_homebanners', 'id');
	    }
	}