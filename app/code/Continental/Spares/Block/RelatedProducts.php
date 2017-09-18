<?php
namespace Continental\Spares\Block;

class RelatedProducts extends \Magento\Framework\View\Element\Template
{
    protected $_registry;

    protected $_categoryFactory;

    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,

        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        array $data = []
    )
    {
        $this->_categoryFactory = $categoryFactory;
        $this->_registry = $registry;
        $this->_productRepository = $productRepository;
        $this->_listProdcut = $listProduct;
        parent::__construct($context, $data);
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
            if (in_array($this->getCategoryId('Spares'), $categoryIds)) {
                $productData[] = array(
                    'sku' => $relatedProduct->getSku(),
                    'image' => $product->getImage(),
                    'price' => number_format($product->getPrice(), 2),
                    'addtocarturl' => $addToCartUrl =  $this->_listProdcut->getAddToCartUrl($product)
                );
            }
        }
        
        if (!empty($relatedProducts)) {
            return $productData;
        } else {

            return false;
        }
    }

    public function getCategoryId($categoryTitle)
    {
        $collection = $this->_categoryFactory->create()->getCollection()->addAttributeToFilter('name', $categoryTitle)->setPageSize(1);

        if ($collection->getSize())
        {
            return $collection->getFirstItem()->getId();
        }
        return false;
    }
}
?>
