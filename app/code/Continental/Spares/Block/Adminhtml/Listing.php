<?php
namespace Continental\Spares\Block;

    /**
     * Admin CMS Spares Listing Block
     */
use Magento\Framework\Registry;
use Continental\Spares\Model;

class Listing extends \Magento\Framework\View\Element\Template
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
    protected $sparesModel;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Continental\Spares\Model\Spares $sparesModel,
        Registry $registry
    )
    {
        parent::__construct($context);
        $this->$sparesModel = $sparesModel;
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

        $collection = $this->sparesModel->getCollection()->addFieldToFilter( 'name', array( 'like'=> $this->getProductSku() ) );
        foreach($collection as $spare) {
            var_dump($spare->getData());
        }
        die('test');
    }
}
