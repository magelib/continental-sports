<?php

namespace Continental\GalleryGrid\Plugin;

class Product
{
    /***
     * Store Gridable items here
     * @var array
     */
    private $_gridableImages;

    /**
     * @param \Magento\Catalog\Model\Product $subject
     * @param \Magento\Framework\Data\Collection $result
     * @return mixed
     */
    public function afterGetMediaGalleryImages(\Magento\Catalog\Model\Product $subject, $result)
    {
        $this->_gridableImages = [];

        foreach ($result as $key => $image) {
            if ($image['gridable']) {
                $this->_gridableImages[$key] = $image;
                $result->removeItemByKey($key);
            }
        }

        return $result;
    }

    public function getGridImages() {
        return $this->_gritableImages;
    }
}