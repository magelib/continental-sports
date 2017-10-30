<?php
namespace Continental\Poa\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class UpdateMessage implements ObserverInterface
{
    /** @var \Magento\Framework\Message\ManagerInterface */
    protected $messageManager;

    /** @var \Magento\Framework\UrlInterface */
    protected $url;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $managerInterface,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->messageManager = $managerInterface;
        $this->url = $url;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $messageCollection = $this->messageManager->getMessages(true);

        $this->messageManager->addSuccess("The donuts are rolling down the hill - towards the goats");
    }
}
