<?php
namespace Continental\Poa\Helper;
use Magento\Framework\Registry;
/**
 * Custom Module Email helper
 */
class Products extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /***
     * @var Registry
     */
    protected $_registry;

    private $_product;

    /**
     * @param Magento\Framework\App\Helper\Context $context
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        Registry $registry
    )
    {
        $this->_scopeConfig = $context;
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_categoryFactory     = $categoryFactory;
        $this->_listProduct = $listProduct;
        $this->_productRepository = $productRepository;
        $this->_registry = $registry;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        if (is_null($this->_product)) {
            $this->_product = $this->_registry->registry('product');

            if (!$this->_product->getId()) {
                //throw new LocalizedException(__('Failed to initialize product'));
            }
        }

        return $this->_product;
    }

    /***
     * Gets Categories id for filtering
     * Note to future developer - This doesn't adhere to DRY principles but we're in plugin land so what the heck..
     * @return mixed
     */

    public function getCategory($categoryName)
    {
        $categoryId = $this->getProduct()->getCategoryId($categoryName);
        $category = $this->_categoryFactory->create()->load($categoryId);
        return $category;
    }

    /***
     * Get a list of related products with spares stripped out.
     */
    public function getAccesories() {
        return $this->getProduct()->getRelatedProductCollection()
            ->addAttributeToSelect(
                [
                    'price',
                    'short_description',
                    'name',
                    'thumbnail',
                    'id'
                ]);
    }
}