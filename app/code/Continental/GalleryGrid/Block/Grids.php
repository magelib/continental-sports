<?php

namespace Continental\GalleryGrid\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;

class Grids extends Template
{
    private $_galleryPlugin;

    public function __construct(\Continental\GalleryGrid\Plugin\Product $gallleryPlugin) {
        $this->_galleryPlugin = $gallleryPlugin;
    }

    public function showGrids() {
        // fetch images
        $images = $this->_galleryPlugin->getGridImages();

        return count($images);

    }
}