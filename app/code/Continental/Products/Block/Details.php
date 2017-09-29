<?php
namespace Continental\Products\Block;

/**
*  Details block
*/

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Api\LinkManagementInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\State;
use Magento\Framework\Registry;

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
    /***
     * @var Registry
     */
    protected $registry;

    /**
     * @var Product
     */
    protected $product;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        LinkManagementInterface $linkManagement,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Registry $registry
    ) {
	parent::__construct($context);
        $this->linkManagement = $linkManagement;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
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

    public function showConfigurableCount()
    {
        $product = $this->getProduct();
        if ($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $_children = $product->getTypeInstance()->getUsedProducts($product);
            $count = count($_children);
            return ($count > 0 ) ? $count : false;
        }
        return false;
    }

    protected function showConfigurables()
    {
        $product = $this->getProduct();
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
                ?>
                function calcDiscount(d) {
                    var fullPrice = $("#product-price-1").data("price-amount");
                    var c = 100-((parseFloat(d)/parseFloat(fullPrice))*100);
                    return  Math.abs(parseFloat(c).toFixed(2));
                }

                function makeRow(val, i) {
                    var html = "";
                    html += "<div class=\"trow\">";
                    if ( typeof (val) != "undefined") {
                        d = calcDiscount(val);
                        html += "<span>" + i + "</span><span>" + d + "%</span><span>&pound;" + parseFloat(val).toFixed(2) + "</span>";
                        window.prodCount++;
                    }
                    html += "</div>";
                    console.log(window.prodCount);
                    return html;
                }

                function makeTable(sku) {
                    var t2 = "";
                    var t = "";
                    window.prodCount = 0;
                    $.each( window.configurables[sku], function( i, val ) {
                        if (window.prodCount < 2) {
                            t += makeRow(val, i);
                        } else {
                            t2 += makeRow(val, i);
                        }
                    });
                    $(".price-table div.tbody").html(t);
                    $(".price-table div.tbody.extra-fields").html(t2);
                }

                window.sku = "";
                jQuery('.sku div').on("DOMSubtreeModified",function(){
                var sku = $(".sku div.value").html();
                if (sku.length) {
                    if (window.sku == "") {
                        window.sku = sku;
                        makeTable(sku);
                    } else {
                        if (window.sku != sku) {
                            window.sku = sku;
                                makeTable(sku);
                        }
                    }
                }
                });
                <?php

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
                    printf(' window.configurables["%s"] = [];%s', $sku, PHP_EOL);
                    $tierPrices[$sku] = true;
                }
                printf(' window.configurables["%s"][%s] = "%s";%s', $sku, $price->getQty(), round($price->getValue(), 2), PHP_EOL);

            }
        }
    }

    public function childProducts()
    {
        $product = $this->getProduct();
        $this->showConfigurables($product);
    }
}
