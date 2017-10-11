<?php
namespace Continental\Spares\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;


class Listing extends AbstractHelper

{
    /***
     * @var \Continental\Spares\Model\SparesFactory
     */
    protected $_sparesFactory;

    /***
     * @var \Continental\Spares\Model\Spares
     */
    protected $_spares;

    /***
     * @var \Magento\Framework\Api\Filter
     */
    protected $_filter;

    /***
     * @var \Magento\Framework\Api\Search\FilterGroup
     */
    protected $_filterGroup;

    /***
     * @var \Magento\Framework\Api\SearchCriteriaInterface
     */
    protected $_searchCriteria;

    /***
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepository;

    /***
     * @var \Magento\Catalog\Block\Product\ListProduct
     */
    protected $_listProduct;

    /***
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Continental\Spares\Model\SparesFactory $sparesFactory,
        \Continental\Spares\Model\Spares $spares,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        \Continental\Spares\Model\LocatorFactory $locatorFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
	    \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->_sparesFactory 	    = $sparesFactory;
        $this->_spares		        = $spares;
        $this->_filter 		        = $filter;
        $this->_filterGroup 	    = $filterGroup;
        $this->_searchCriteria 	    = $searchCriteria;
        $this->_productRepository   = $productRepository;
        $this->_listProduct 	    = $listProduct;
        $this->_locatorFactory      = $locatorFactory;
        $this->_productFactory      = $productFactory;
	    $this->_categoryFactory     = $categoryFactory;
        $this->_request             = $request;
    }

    public function formatPrice($price) {
        return number_format($price, 2);
    }
    public function showQuery() {
        return $this->_request->getParam('query');
    }

    public function getSparesImageData($mastersku) {
        $model = $this->_spares;
    }

    public function getSparesLocationCollection(){
        return $this->_sparesFactory->create()->getCollection();
    }

    public function getSparesCollectionFilter($mastersku) {
            return $this->getSparesLocationCollection()->addAttributeToFilter('master_product_sku',['eq' => $mastersku]);
    }

    public function setFilter($field, $value)
    {
        // Create Filter
        $this->_filter->setData('field', $field);
        $this->_filter->setData('value', $value);
        $this->_filter->setData('condition_type', 'like');

        //add our filter(s) to a group
        $this->_filterGroup->setData('filters', [$this->_filter]);

        //add the group(s) to the search criteria object
        $this->_searchCriteria->setFilterGroups([$this->_filterGroup]);
    }

    /***
     * Get a collection of technical images for spares locator for a specific sku
     * @param $mastersku
     * @return mixed
     */
    public function filterSpareImages($mastersku, $field = 'master_product_sku') {
        return $this->_sparesFactory->create()->getCollection()->addFieldToSelect('*')
            ->addFieldToFilter($field, $mastersku)
            ->addFieldToFilter('spareimage', ['neq' => ''])
            ->addFieldToFilter('spareimage', ['neq' => 'NULL']);
    }

    public function filterSpares($field, $value) {
        $this->setFilter($field, $value);
        //query the repository for the object(s)
        return $this->_sparesFactory->create()->getCollection()->addFieldToSelect('*')
            ->addFieldToFilter($field, $value)
            ->addFieldToFilter($field, ['neq' => 'NULL']);
    }

    public function getCategory()
    {
        $categoryId = $this->getCategoryId('Spares');
        $category = $this->_categoryFactory->create()->load($categoryId);
        return $category;
    }
    public function getSparesProductCollection()
    {
        return $this->getCategory()->getProductCollection()->addAttributeToSelect('*')
            ->addFieldToFilter(
                'name',
                array('like' => '%'.$this->_request->getParam('query').'%')
            );
    }

    /***
     *
     */
    public function sparesSearch() {
        $collection = $this->_productFactory->create->getCollection();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(
            array(
                'eq' => $this->getCategoryId('Spares')
            )
        );
        $collection->getSelect()->assemble();
        $collection->getSelect()->__toString();
        echo $collection->getSelect();
    }

    public function filterProducts($field, $value) {
        $this->setFilter($field, $value);
        //query the repository for the object(s)
        $result = $this->_productRepository->getList($this->_searchCriteria);
        return  $result->getItems();
    }

    public function getLocation($val) {
        return explode(',', $val);
    }

    public function getDimensions($val) {
        return explode(',', $val);
    }

    public function getProductBySku($sku)
    {
        return $product = $this->_productRepository->get($sku);
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
