<?php
namespace Continental\Spares\Block;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;

class Spares extends \Magento\Framework\View\Element\Template
{
    protected $scopeConfig;
    protected $collectionFactory;
    protected $objectManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Continental\Spares\Model\ResourceModel\Spares\CollectionFactory $collectionFactory,
        ObjectManagerInterface $objectManager
    ) {

        $this->scopeConfig = $context->getScopeConfig();
        $this->collectionFactory = $collectionFactory;
        $this->objectManager = $objectManager;

        parent::__construct($context);
    }


    public function getMediaDirectoryUrl()
    {

        $media_dir = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $media_dir;
    }

    /*
     * Get list of spares with images
     */
    public function getLayoutImge($product) {
        return true;
    }

    public function getSparesLocations() {
        /* get location, dimension, sku master
        1. Show Image
        2. Highlight hotsports
        3. on mouse over highlight product / show in popup box
*/
    }
}
