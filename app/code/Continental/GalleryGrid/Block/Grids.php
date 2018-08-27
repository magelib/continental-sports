<?php

namespace Continental\GalleryGrid\Block;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Product\Gallery\ReadHandler as GalleryReadHandler;

class Grids extends Template
{
    private $_gridImages;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Product
     */
    private $product;

    /***
     * @var
     */
    protected $galleryReadHandler;

    public function __construct(Template\Context $context, Registry $registry, GalleryReadHandler $galleryReadHandler, array $data)
    {
        $this->registry = $registry;
        $this->galleryReadHandler = $galleryReadHandler;
        parent::__construct($context, $data);
    }
    /***
     * @param null $images
     */

    /** Add image gallery to $product */
    public function addGallery()
    {
        $this->galleryReadHandler->execute($this->getProduct());
    }

    /**
     * @return Product
     */
    private function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = $this->registry->registry('product');

            if (!$this->product->getId()) {
                //throw new LocalizedException(__('Failed to initialize product'));
            }
        }

        return $this->product;
    }

   public function test() {
        return count($this->_gridImages);

    }

    /***
     * return a list of gridable images;
     * @return int
     */
    public function showGrids() {
        // fetch images
        $this->addGallery();
        $x = $this->getProduct()->getGalleryImages('images');
        return $x;
        //return $this->getProduct()->getGalleryImages();
//        return 'foobard';

    }
}