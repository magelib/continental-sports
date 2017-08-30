<?php
    namespace Continental\HomeBanners\Model\Resource\Banner;

   class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
	{
	    /**
	     * Define model & resource model
	     */
	    protected function _construct()
	    {
	        $this->_init(
	            'Continental\HomeBanners\Model\Banner',
	            'Continental\HomeBanners\Model\Resource\Banner'
	        );
	    }
	}