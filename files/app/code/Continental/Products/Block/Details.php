<?php
namespace Continental\Products\Block;

/**
 *  Details block
 */

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Api\LinkManagementInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\State;


class Details extends \Magento\Framework\View\Element\Template
{

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var LinkManagementInterface
     */
    protected $linkManagement;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        LinkManagementInterface $linkManagement,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        parent::__construct($context);
        $this->linkManagement = $linkManagement;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function showConfirurableCount($product = null)
    {
        if ($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $_children = $product->getTypeInstance()->getUsedProducts($product);
            $count = count($_children);
            return ($count > 0 ) ? $count : false;
        }
        return false;
    }

    protected function showConfigurables($product = null)
    {
        $sku = $product->getsku();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('type_id', 'configurable')
            ->create();

        $configurableProducts = $this->productRepository->getList($searchCriteria);

        foreach ($configurableProducts->getItems() as $configurableProduct) {
            $childProducts = $this->linkManagement->getChildren($configurableProduct->getSku());
            if ($sku === $configurableProduct->getSku()) {

                echo '<script>window.configurables = [];';
                foreach ($childProducts as $childprod) {

                    $this->getTierPrices($childprod->getSku());

                }
        echo 'require(["jquery"], function($){';
		echo 'jQuery("body").on(\'DOMSubtreeModified\', ".product.attribute.sku .value", function() {';
    		echo 'x = jQuery(this).html();';
    		echo 'if (x.length) {';
        	echo 'alert(x);';
		echo '}});';
        echo '});';
                echo '</script>';
            }
        }
    }

    public function getTierPrices($sku)
    {
        $product = $this->productRepository->get($sku);

        $tierPricesList = $product->getPriceInfo()->getPrice('tier_price')->getTierPriceList();

        $tier_price = $product->getTierPrices();
        $tierPrices = [];
        if(count($tier_price) > 0){

            foreach($tier_price as $price){
                if ( empty($tierPrices[$sku]) ) {
                    printf(' window.configurables["%s"] = [];', $sku);
                    $tierPrices[$sku] = true;
                }

                printf(' window.configurables["%s"][%s] = "%s";%s', $sku, $price->getQty(), $price->getValue(), PHP_EOL);

                /*echo $price->getQty();
                echo "\t";
                echo $price->getValue();
                echo "\t";
                echo $price->getCustomerGroupId();
                echo "\t";
                echo "\n";
                print_r($price->getData());
                echo "\t";
                echo "\n";
                */
            }
        }
    }

    public function childProducts($product)
    {
        $this->showConfigurables($product);
    }
}
