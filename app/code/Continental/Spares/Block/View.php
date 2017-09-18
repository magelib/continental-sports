<?php
namespace Continental\Spares\Block;

/**
 * Admin CMS Spares Listing Block
 */
use Magento\Framework\Registry;
use Continental\Spares\Model;

class View extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Product
     */
    protected $product;

    /***
     * @var Registry
     */
    protected $registry;

    /****
     * @var
     */
    protected $collectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Continental\Spares\Model\ResourceModel\Spares\CollectionFactory $collectionFactory,
        Registry $registry
    )
    {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->registry = $registry;
    }

    /**
     * @return Product
     */
    private function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = $this->registry->registry('product');

            if (!$this->product->getId()) {
                throw new LocalizedException(__('Failed to initialize product'));
            }
        }

        return $this->product;
    }

    /***
     *  Return sku
     * @return mixed
     */
    public function getProductSku()
    {
        return $this->getProduct()->getSku();
    }

    public function sparesListing_old() {
        $tbl = '';
        $counter = 0;
        echo $this->getProductSku();
        $collection = $this->collectionFactory->create()->addFieldToFilter('master_product_sku', array( 'like'=> $this->getProductSku() ) );

        foreach($collection as $spare) {
            $counter++;
            $d = $spare->getData();
            $tbl .= sprintf("<tr><td><img src=\"%s\" alt=\"\" width=\"60\" /></td><td>%s</td><td>%s</td><td>&pound;%s</td><td><input type=\"number\" /></td><td><button>Add to Basket</button></td></tr>",
                $d['spareimage'],
                $d['title'],
                $d['sku'],
                number_format($d['price'], 2)
            );
        }

        if ($counter < 1) return false;

        return $tbl;
    }

    public function sparesListing() {
/*
        $related = $this->getRelatedProducts();
        $tbl = '';
        foreach ($related as $index=>$data) {
            //$tbl .= print_r($data, true);
            foreach ($data as $index2=>$val) {
                echo $index2;
            }
        }
        return 'done';
/*
        $tbl = '';
        $counter = 0;
        echo $this->getProductSku();
        $collection = $this->collectionFactory->create()->addFieldToFilter('master_product_sku', array( 'like'=> $this->getProductSku() ) );

        foreach($collection as $spare) {
            $counter++;
            $d = $spare->getData();
            $tbl .= sprintf("<tr><td><img src=\"%s\" alt=\"\" width=\"60\" /></td><td>%s</td><td>%s</td><td>&pound;%s</td><td><input type=\"number\" /></td><td><button>Add to Basket</button></td></tr>",
                $d['spareimage'],
                $d['title'],
                $d['sku'],
                number_format($d['price'], 2)
            );
        }

        if ($counter < 1) return false;

        return $tbl;
  */
    }

    public function getJsData() {
        $js = '';
        $collection = $this->collectionFactory->create()->addFieldToFilter('master_product_sku', array( 'like'=> $this->getProductSku() ) );
        foreach($collection as $spare) {
         $d = $spare->getData();
            $js .= sprintf("{ sparesimage: %s, title: %s, sku: %s, price: %s  },",
                $d['spareimage'],
                $d['title'],
                $d['sku'],
                $d['price']
            );
        }
        return rtrim($js,',');
    }

    public function _getRelatedProducts()
    {
        if (!$this->getProduct()->hasRelatedProducts()) {
            $products = [];
            $collection = $this->product->getRelatedProductCollection();
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $this->product->setRelatedProducts($products);
        }
        return $this->product->getData('related_products');
    }
}
