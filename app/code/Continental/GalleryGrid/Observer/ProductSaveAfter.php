<?php

namespace Continental\GalleryGrid\Observer;

use Magento\Framework\Event\ObserverInterface;

class ProductSaveAfter implements ObserverInterface {

    protected $request;
    protected $resource;

    /**
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResourceConnection $resource\
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request, \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->request = $request;
        $this->resource = $resource;
    }

    private function _log() {
        $writer = new \Zend\Log\Writer\Stream($_SERVER['DOCUMENT_ROOT'] . '/potus_sucks.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('aw gawd');
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_log();

        $data = $this->request->getPostValue();

        if (isset($data['product']['media_gallery']['images'])) {
            // print_r($images);exit;
            $connection = $this->resource->getConnection();
            $tableName = 'catalog_product_entity_media_gallery'; //gives table name with prefix
            $product = $observer->getProduct();
            $mediaGallery = $product->getMediaGallery();

            if (isset($mediaGallery['images'])) {
                foreach ($mediaGallery['images'] as $image) {
                    //Update Data into table
                    $vmValue = !empty($image['gridable']) ? (int)$image['gridable'] : 0;
                    $sql = "UPDATE " . $tableName . " SET gridable = " . $vmValue . " WHERE value_id = " . $image['value_id'];
                    $connection->query($sql);
                }
            }
        }
    }

}