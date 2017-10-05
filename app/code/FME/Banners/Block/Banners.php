<?php
namespace FME\Banners\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;

class Banners extends Template
{
    /***
     * @var $scopeConfig;
     */
    protected $scopeConfig;

    /***
     * @var \FME\Banners\Model\ResourceModel\Banners\CollectionFactory
     */
    protected $collectionFactory;

    /***
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /***
     * @var bool $sbanner
     */
    protected $banner;

    /***
     * @var $bannerCount
     */
    protected $bannerCount;

    /***
     * @param Template\Context $context
     * @param \FME\Banners\Model\ResourceModel\Banners\CollectionFactory $collectionFactory
     * @param ObjectManagerInterface $objectManager
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \FME\Banners\Model\ResourceModel\Banners\CollectionFactory $collectionFactory,
        ObjectManagerInterface $objectManager
    )
    {

        $this->scopeConfig = $context->getScopeConfig();
        $this->collectionFactory = $collectionFactory;
        $this->objectManager = $objectManager;
        $this->banner = false;
        parent::__construct($context);
    }

    /***
     * Check for arguments,provided in block call
     * @return mixed
     */
    public function getFrontBanners()
    {
        $collection = $this->collectionFactory->create()->addFieldToFilter('status', 1);


        if ($ids_list = $this->getBannerBlockArguments()) {
            $collection->addFilter('banners_id', ['in' => $ids_list], 'public');
        }


        return $collection;
    }

    /***
     * @return array
     */
    public function getBannerBlockArguments()
    {

        $list = $this->getBannerList();

        $listArray = [];

        if ($list != '') {
            $listArray = explode(',', $list);
        }

        return $listArray;
    }

    /***
     * Get media directory for banner images
     * @return mixed
     */
    public function getMediaDirectoryUrl()
    {

        $media_dir = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $media_dir;
    }

    /***
     * Return the number of banners available.
     * @return int
     */
    public function getBannerCount()
    {
        return $this->bannerCount;
    }

    /***
     * Return banner object if found and false if not.
     * @return mixed
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /***
     *  Show front end javascript to randomise background images
     */
    public function BannersJsArray()
    {

        $banners = $this->getFrontBanners();

        $this->bannerCount = count($banners);

        $bannerIndex = 1;

        if ($this->bannerCount > 0) {
            foreach ($banners as $index => $bannerObj) {
                $title = trim($bannerObj->getTitle());

                if (preg_match('/^<p>(.*)<\/p>$/', $title, $matches)) {
                    $title = $matches[1];
                }

                if ($index === $bannerIndex) {
                    $this->banner = $bannerObj;
                    $pubTitle = $title;
                }

                echo PHP_EOL;
                echo ' Banners[' . $index . '] = { ';
                echo ' image:"' . $bannerObj->getBannerimage() . '", title:"' . $title . '", ';
                echo ' linktext:"', $bannerObj->getLinktext() . '", ';
                echo ' text:"' . $bannerObj->getBannertext() . '", target:"' . $bannerObj->getTarget() . '", style:"';
                echo ($bannerObj->getTextcolour() == 0) ? '#333333' : '';
                echo '" };';
            }
        }
    }
}
