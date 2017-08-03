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
    ) {
	parent::__construct($context);
        $this->linkManagement = $linkManagement;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }


	protected function showConfigurables($product = null)
    {
	$sku= $product->getsku();
         $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('type_id', 'configurable')
            ->create();

        $configurableProducts = $this->productRepository->getList($searchCriteria);
        
//	printf('Found %d configurable products ...', $configurableProducts->getTotalCount());

        foreach ($configurableProducts->getItems() as $configurableProduct) {
            $childProducts = $this->linkManagement->getChildren($configurableProduct->getSku());
	    if ( $sku ===  $configurableProduct->getSku() ) {

                printf('Found %d children for %s', count($childProducts), $configurableProduct->getSku() );
		}
            }
        }

	public function childProducts($product) {
	    $this->showConfigurables($product);
	}
}
