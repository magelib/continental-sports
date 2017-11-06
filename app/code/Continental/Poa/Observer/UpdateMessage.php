<?php
namespace Continental\Poa\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;


class UpdateMessage implements ObserverInterface
{
    /** @var \Magento\Framework\Message\ManagerInterface */
    protected $messageManager;
    /***
     * @var
     */
    protected $messageCollection;
    /***
     * @var
     */
    protected $message;
    /***
     * @var \Amasty\HidePrice\Helper\Data
     */
    protected $_helper;

    /***
     * @var
     */
    private $poa;
    /** @var \Magento\Framework\UrlInterface */

    protected $url;

    protected $_cart;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $managerInterface,
        \Magento\Framework\UrlInterface $url,
        \Amasty\HidePrice\Helper\Data $helper,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Continental\Products\Helper\Accessories $accessoriesHelper
    ) {
        $this->messageManager = $managerInterface;
        $this->url = $url;
        $this->_helper = $helper;
        $this->_cart = $cartHelper;
        $this->_product = $accessoriesHelper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        // Get basket
        $this->messageCollection = $this->messageManager->getMessages(true);

        // Get message depending if Product is POA or not.
        $this->getAddToCartMessage();

//        $this->message .= $this->getBasket();

        $this->messageManager->addSuccess($this->message);
    }

    protected function getAddToCartMessage() {
        $this->message = $this->messageCollection->getLastAddedMessage()->getText();

        // Check if POA
        if ($this->isPoa()) {
            /* TODO: add this to backend and make content editable */
            $this->message .= 'This item is an enquiry only; your basket will be sent as an enquiry and our dedicated customer support team will be in touch';
        }
    }

    /***
     * Checks basket to get most recently added product and retrieves product id
     * @return int
     */
    protected function getBasket() {
        $quote = $this->_cart->getQuote();

        $items = $quote->getAllVisibleItems();

        $maxId = 0;

        foreach ($items as $item){
            if ($item->getId() > $maxId) {
                // This was working when maxId was commented out
                $maxId = $item->getId(); // MaxId should be the last item added.
                $latestProductId = $item->getProductId();
            }
        }

        $this->productId = $latestProductId;

        return $this->productId;
    }

    /***
     * Checks if latest product is Poa
     * @return bool
     */
    protected function isPoa() {
        // Get current product
        $productId = $this->getBasket();
        $product = $this->_product->getProductByID( $productId );
        return  $this->_helper->isNeedHideProduct( $product );
    }
}
