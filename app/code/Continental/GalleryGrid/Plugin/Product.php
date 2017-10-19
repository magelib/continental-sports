<?php

namespace Continental\GalleryGrid\Plugin;

class Product
{
    protected $_block;

    public function __construct(\Continental\GalleryGrid\Helper\Data $helper) {
        $this->helper = $helper;
    }

    public function afterGetMediaGalleryImages(\Magento\Catalog\Model\Product $subject, $result)
    {
       $gridableImages = [];

        foreach ($result as $key => $image) {
            if ($image['gridable']) {
                $gridableImages[$key] = $image;
                $result->removeItemByKey($key);
            }
        }

        if (!empty($gridableImages)) {
            $this->helper->SetGridImage($gridableImages);
        }

        return $result;
    }

 }