<?php
/**
 * @author Continental Team
 * @copyright Copyright (c) 2017 Continental
 * @package Continental_Banners
 */
namespace Continental\HomeBanners\Helper;

use Continental\HomeBanners\Model\BannerFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */

    protected $collection;

    protected $_modelBanners;

    public $currentBanner;

    public function __construct(
        BannerFactory $modelBanners,
        \Magento\Framework\App\Helper\Context $context
    )
    {
        $this->_modelBanners = $modelBanners;
        parent::__construct($context);
        $this->getCollection();
        $this->randomSelection();
    }

    /*
     public function execute() {
            $this->getCollection();
        }
    */

    /***
     *  Retrieve banner data from database
     */
    public function getCollection()
    {
        $resultPage = $this->_modelBanners->create();
        $this->collection = $resultPage->getCollection()->getData();
    }

    /***
     *  Select a single banner from the banner collection
     */
    private function randomSelection()
    {
        $this->currentBanner = array(
            'title' => '',
            'text' => '',
            'image' => '',
            'link' => ''
        );

        $arrCount = count($this->collection);
        if ($arrCount > 0) {
            $rand_key = array_rand($this->collection, 1);
            $this->currentBanner = $this->collection[$rand_key];
        }
    }

    /***
     * Set the background image
     * @return string
     */
    public function getBlockBack()
    {
        return '/pub/media/banners/' . trim($this->currentBanner['image']);
    }

    public function getBlockHref()
    {
        $href = $this->currentBanner['link'];
        return $href;
    }

    public function getBlockText()
    {
        $text = $this->currentBanner['text'];
        return $text;
    }

    public function getBlockTitle()
    {
        $title = $this->currentBanner['title'];
        return $title;
    }
}
