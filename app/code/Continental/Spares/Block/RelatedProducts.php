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

    /**
     * [getLocations description]
     * @param  [type] $mastersku [description]
     * @return [type]            [description]
     */
    public function getLocations($mastersku) {
    /* Get all location for this part */
        $list = $this->_listingHelper->filterSpareImages($mastersku);
        $locations = [];

        if ( count($list) > 0 ) {
                foreach ($list as $index => $obj) {
                    if(!empty($obj->getData('location')) && !empty($obj->getData('dimensions'))) {
                       
                        list($x1, $y1) = explode(',', $obj->getData('location'));
                       
                        list($x2, $y2) = explode(',', $obj->getData('dimensions'));
                       
                        $locations[] = array( 
                            "top" => $y1,
                            "left" => $x1,
                            "width" => abs(round($x2)),
                            "height" => abs(round($y2)),
                            "canvas" => "$x1, $y1, $x2, $y2"
                        );
                    }
                }
            return $locations;
        }
            return false;
    }

    /**
     * Get locations as json string
     * @param  [type] $mastersku [description]
     * @return [type]            [description]
     */
    public function getLocationsJson($mastersku) {
        $array = $this->getLocations($mastersku);
        if ( count ( $array ) > 0) {
            return json_encode( $array );
        } else {
            return '';
        }
    }

	public function display_hotspots( $mastersku ) {
        $html = '';
        if ( $locationDataArray = $this->getLocations($mastersku) ) {
            foreach ($locationDataArray as $index => $l) {
                $html .= sprintf('<a data-debug="%s" class="hotspot" id="hotspot-111" rel="111" style="display:block; position: absolute; width:%spx;height:%spx;top:%spx;left:%spx; border:2px solid #999" href="#"></a>', 
		$l['canvas'],
                $l['width'],
                $l['height'],
                $l['top'],
                $l['left']);
            }
        }
        return $html;
    }

    public function showLocations($mastersku) {
        /* Get all location for this part */
        $list = $this->_listingHelper->filterSpareImages($mastersku);
        $rowHtml = '<table>';

        if ( count($list) > 0 ) {
                foreach ($list as $index => $obj) {
                    if(!empty($obj->getData('location')) && !empty($obj->getData('dimensions'))) {
                        list($x1, $y1) = explode(',', $obj->getData('location'));
                           
                        list($x2, $y2) = explode(',', $obj->getData('dimensions'));
                        
                        if(!empty($obj->getData('location')) && !empty($obj->getData('dimensions'))) {
                            $rowHtml .= sprintf("<tr><td>Width: %s</td><td>Height :%s</td><td>x: %s</td><td>y: %s</td></tr>",
                                $x2, $y2, $x1, $x2);
                        }
                    }
                }
            $rowHtml .= '</table>';
            return $rowHtml;
        }
            return "-- No locations set this item --";


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
