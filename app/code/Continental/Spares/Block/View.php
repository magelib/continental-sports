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

    public function sparesListing() {
        echo "Listing test ";
        echo $this->getProductSku();
        $collection = $this->collectionFactory->create()->addFieldToFilter('master_product_sku', array( 'like'=> $this->getProductSku() ) );
        foreach($collection as $spare) {
            var_dump($spare->getData());
        }
    }
}
