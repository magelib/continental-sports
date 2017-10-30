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

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $managerInterface,
        \Magento\Framework\UrlInterface $url,
        \Amasty\HidePrice\Helper\Data $helper
    ) {
        $this->messageManager = $managerInterface;
        $this->url = $url;
        $this->_helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $this->messageCollection = $this->messageManager->getMessages(true);

        // Get message depending if Product is POA or not.
        $this->getAddToCartMessage();
        $this->message .= ' :: GXT :: ' . $this->poa;

        $this->messageManager->addSuccess($this->message);
    }

    protected function getAddToCartMessage() {
        $this->message = $this->messageCollection->getLastAddedMessage()->getText();

        // Check if POA
        if ($this->isPoa()) {
            /* TODO: add this to backend and make content editable */
            $this->message = 'This item is an enquiry only; your basket will be sent as ab enquiry and our dedicated customer support team will be in touch';
        }

    }

    protected function isPoa() {
        $this->poa = $this->_helper->getModuleConfig('frontend/link');
        // Debugging:
        if ($this->poa == 'AmastyHidePricePopup') {
            return true;
        }

        return false;
    }
}
