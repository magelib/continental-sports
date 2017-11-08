<?php
namespace Continental\Poa\Controller\Enquiry;
class Index extends \Magento\Framework\App\Action\Action
{
    const ADMIN_EMAIl = "enquiries@contisports.co.uk";
    /**
     * @var \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    protected $resultPageFactory;

    /**
     * Recipient email
     * @var
     */
    private $_to;
    /**
     * Sender email
     * @var
     */
    private $_from;

    private $_replyto;

    /**
     * @var
     */
    private $_subject;
    /**
     * Email subject
     * @var
     */
    protected $_post;
    /**
     * post variables from Enquiry form
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    protected $_errors = null; // Set to null when finished testing

    protected $_logger;

    private $_domain = 'https://www.continentalsports.co.uk';

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Me\Econtacts\Model\Econtacts $econtactsModel,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->_escaper = $escaper;
        $this->_transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->cart = $cart;
        $this->checkoutSession = $checkoutSession;
        $this->filterManager = $filterManager;
        $this->econtactsModel = $econtactsModel;
        $this->_logger = $logger;
    }

    /***
     * @return mixed
     */
    public function execute()
    {
        if ($this->_errors != null) {
            echo "<!-- Currently in dev mode -->";
        }
        $this->_post = $this->getRequest()->getPostValue();
        $this->sendEmailsOut();
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addHandle('continental_poa_autoresponder_enquiry');
    }

    private function checkFields()
    {
        $fields = array('email');

    }

    private function sendEmailsOut()
    {
        $this->sendEmailToCustomer();

        $this->sendEmailToContinental();

        // Save into Keep Me plugin. (TO DO add dependency check)
        $this->saveEnquiryToContacts();

        if ($this->_errors === null) {
            // Clear the cart/basket as order received
            $this->clearBasket();
            // Add success message
            $msg = 'Enquiry has been sent';
            $this->messageManager->addSuccess(__($msg));

            // Redirect customer to specific thank you page
            $this->_redirect('order-enquiry-thanks');
        } else {
            echo "error";
        }
    }

    private function convertBreaks($text) {
        $breaks = array("<br />","<br>","<br/>");
        $text = str_ireplace($breaks, "\r\n", $text);
        return $text;
    }

    /***
     * Convert the form post data so it can be saved as a contact us enquiry
     */
    protected function getFormatContactData() {

        return [
            'name'      => sprintf("%s %s", $this->getPost('firstname'), $this->getPost('lastname')),
            'company'   => $this->getPost('company'),
            'email'     => $this->getPost('email'),
            'telephone' => $this->getPost('telephone'),
            'message'   => $this->getPost('message') . PHP_EOL . 'Order Details: ' . PHP_EOL . '==========' . PHP_EOL . $this->convertBreaks($this->getBasketItems() )
        ];
    }

    protected function saveEnquiryToContacts() {
        try {
            $data = [
                'store_id' => $this->storeManager->getStore()->getId()
            ];

            // get post
            $post = $this->getFormatContactData();

            $data = array_merge($data, $post);
            /**
             * @var \Magento\Framework\Filter\FilterManager $filterManager
             */
            $data['comment'] = $this->filterManager->stripTags($this->getPost('message'));

            if (!empty($data)) {
                $this->econtactsModel->addData($data);
                $this->econtactsModel->save();
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->_logger->warning($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->_logger->warning($e->getMessage());
        } catch (\Exception $e) {
            $this->_logger->warning($e->getMessage());
        }
    }

    private function clearBasket()
    {
        // Empty basket
        $allItems = $this->checkoutSession->getQuote()->getAllVisibleItems();

        foreach ($allItems as $item) {
            $itemId = $item->getItemId();
            $this->cart->removeItem($itemId)->save();
        }

        $this->checkoutSession->clearQuote();
        $this->checkoutSession->clearQuote()->clearStorage();
        $this->messageManager->addSuccess(__("Basket has been cleared."));
    }

    protected function checkForm()
    {

    }

    /***
     * @return bool
     */
    protected function sendEmailToCustomer()
    {
        $toName = sprintf("%s %s", $this->getPost('firstname'), $this->getPost('surnname'));

        $this->_subject = 'Customer confirmation for POA order';
        $this->sendEmail($this->getPost('email'), $toName);

        return false;
    }

    /***
     * @return bool
     */
    protected function sendEmailToContinental()
    {
        $email = 'matthew.byfield@attercopia.co.uk';

        $toName = "Continental Sports";
        $this->_subject = 'New POA website enquiry';
        $this->sendEmail($this->getPost('email'), $toName);
    }

    private function getProtocol()
    {
        return ($this->storeManager->getStore()->isCurrentlySecure()) ? 'https://' : 'http://';
    }

    protected function sendEmail($to, $toName = "Continental Sports", $from = "test@continentalsports.co.uk", $fromName = "Continental Sports Admin")
    {
        // Testing
        preg_match('/http.*?\/\/(.*)\//', $this->storeManager->getStore()->getBaseUrl(), $matches);
        // Use this for testing on other domains
        if (!empty($matches[1])) {
            $this->domain = $this->getProtocol() . $matches[1];
            $from = str_replace('continentalsports.co.uk', $matches[1], $from);
        }

        if (!preg_match('/attercopia/', $to)) {
            $to = "matthew.byfield@attercopia.co.uk";
        }

        $toName = 'Developer Testing';

        try {
            $sender = [
                'name' => $this->_escaper->escapeHtml($fromName),
                'email' => $this->_escaper->escapeHtml($from)
            ];

            // Sender data ok
            $recipient = [
                'name' => $this->_escaper->escapeHtml($toName),
                'email' => $this->_escaper->escapeHtml($to)
            ];

            $recipient = $this->_escaper->escapeHtml($to);


            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('send_customer_email_template')// this code we have mentioned in the email_templates.xml
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(
                    [
                        'name' => sprintf("%s %s", $this->getPost('firstname'), $this->getPost('surname')),
                        'email' => $this->getPost('email'),
                        'telephone' => $this->getPost('telephone'),
                        'company' => $this->getPost('company'),
                        'message' => $this->getPost('message'),
                        'basket' => $this->getBasketItems(),
                        'domain' => $this->domain,
                        'subject' => $this->_subject
                    ])
                ->setFrom($sender)
                ->addTo($recipient)
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            return true;
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
            $msg = 'We can\'t process your request right now. Sorry, that\'s all we know.' . $e->getMessage();
            $this->_errors = 'Cannot send email';
            $this->messageManager->addError(__($msg));
            $this->_redirect($this->_redirect->getRefererUrl());
            return false;
        }
    }

    /**
     * @return string
     */
    protected function getBasketItems()
    {
        $basketItems = $this->cart->getQuote()->getAllVisibleItems();
        $basket = '';
        foreach ($basketItems as $item) {
            $basket .= '<br />ID: ' . $item->getProductId() . '<br />';
            $basket .= 'Name: ' . $item->getName() . '<br />';
            $basket .= 'Sku: ' . $item->getSku() . '<br />';
            $basket .= 'Quantity: ' . $item->getQty() . '<br />';
//            $basket .= 'Price: ' . $item->getPrice() . '<br />';
            $basket .= "<br />";
        }

        return $basket;
    }

    /***
     * @param $str
     * @return bool
     */
    public function getPost($str)
    {
        return !empty($this->_post[$str]) ? $this->_post[$str] : false;
    }
}
