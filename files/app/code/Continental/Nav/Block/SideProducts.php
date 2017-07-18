<?php
namespace Continental\Nav\Block;

/**
* Nav block
*/

use Magento\Customer\Model\Url;
use Magento\Framework\App\Http\Context;
use Magento\Framework\View\Element\Template;

class SideProducts
    extends \Magento\Framework\View\Element\Template
{

/** @var Url $_customerUrl */
    protected $_customerUrl;

    /** @var Context $httpContext */
    protected $httpContext;

/** @var object $category */
    protected $category;
    /**
     * Links constructor.
     * @param Template\Context $context
     * @param array $data
     * @param Url $customerUrl
     * @param Context $httpContext
     */
    public function __construct(Template\Context $context, Url $customerUrl, Context $httpContext, array $data)
    {
        $this->_customerUrl = $customerUrl;
        $this->httpContext = $httpContext;
	$this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();

	$this->category = $this->objectManager->get('Magento\Framework\Registry')->registry('current_category');
	

        parent::__construct($context, $data);
    }

    public function categoryBackgroundImage() {
        $imageHtml =  $this->category->getImageUrl();
	return $imageHtml;
	}

	function SideSubMenu($showImages = false) {
		$subcats = $this->category->getChildrenCategories();
//		$_helper = $this->helper('Magento\Catalog\Helper\Output');
?>

<ul>
    <?php
    foreach ($subcats as $subcat) {
        if ($subcat->getIsActive()) {
            $_category = $this->objectManager->create('Magento\Catalog\Model\Category')->load($subcat->getId());
            $subcaturl = $subcat->getUrl();

                /* @escapeNotVerified */
                printf('<li><a href="%s" class="block-promo" title="%s">%s</a></li>',
								$subcaturl, $subcat->getName(), $subcat->getName()
							);
        }
    } ?>
</ul>
<?php
}}
?>
