<?php
namespace Continental\Spares\Block;

class RelatedProducts extends \Magento\Framework\View\Element\Template
{
    /***
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /***
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /***
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepository;

    /**
     * @var \Continental\Spares\Helper\Listing
     */
    protected $_listingHelper;

    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        \Continental\Spares\Helper\Listing $listingHelper,
        array $data = []
    )
    {
        $this->_categoryFactory = $categoryFactory;
        $this->_registry = $registry;
        $this->_productRepository = $productRepository;
        $this->_listProdcut = $listProduct;
        parent::__construct($context, $data);
        $this->_listingHelper = $listingHelper;
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getRelatedProducts() {
        $productData = array();
        $currentProduct = $this->getCurrentProduct();

        $relatedProducts = $currentProduct->getRelatedProducts();
        
        foreach ($relatedProducts as $relatedProduct) {
            $product = $this->_productRepository->getById( $relatedProduct->getId() );
            $categoryIds = $relatedProduct->getCategoryIds();
            if (in_array($this->_listingHelper->getCategoryId('Spares'), $categoryIds)) {
                $productData[] = array(
                    'sku' => $relatedProduct->getSku(),
                    'image' => $product->getImage(),
                    'price' => number_format($product->getPrice(), 2),
                    'addtocarturl' =>  $this->_listProdcut->getAddToCartUrl($product),
                    'title' =>  $product->getName()
                );
            }
        }
        
        if (!empty($relatedProducts)) {
            return $productData;
        } else {

            return false;
        }
    }

    public function showLocations() {
        return '<table><tr><td>SSS</td><td>SSS</td></tr>';
    }

    public function getAllSparesMasterImages($mastersku) {

    }

    /***
     * Get spare for selected image
     * @param $mastersku
     * @return mixed
     */
    public function getSparesMasterImages($mastersku)
    {
        return $this->_listingHelper->filterSpareImages($mastersku);
    }

    /***
     * * Get all available images
     * @param $mastersku
     */

    public function testGetSparesMasterImages($mastersku)
    {
        echo "Looking for $mastersku<br />Here we go...";
        $collection = $this->_listingHelper->filterSpareImages($mastersku);
        $collection->getSelect()->assemble();
        $collection->getSelect()->__toString();
        echo $collection->getSelect();
        foreach ($collection as $thing) {
            echo $thing->getSpareimage();
        }
        return $collection;
    }
}
?>
