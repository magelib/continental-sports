<?php
/**
* @author Attercopia Team
* @copyright Copyright (c) 2017 Attercopia
* @package Gallery Grid
*/
namespace Continental\GalleryGrid\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_gridImages;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
        parent::__construct($context);
        $this->_scopeConfig = $this->scopeConfig;
    }

    public function SetGridImage($images = null) {

        if ($images != null) {
            $this->_gridImages = $images;
        }
    }

    public function getGridImages() {
        return $this->_gridImages;
    }

}
