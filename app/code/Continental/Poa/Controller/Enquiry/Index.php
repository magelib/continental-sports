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

    protected $_errors = 'testing'; // Set to null when finished testing

    private $_domain = 'https://www.continentalsports.co.uk';

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Checkout\Model\Cart $cart
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
        //print_r($this->_post);
        $this->sendEmailsOut();
        //$this->resultPageFactory->create();
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addHandle('continental_poa_autoresponder_enquiry');
    }

    private function checkFields() {
        $fields = array('email');

    }

    private function sendEmailsOut() {
        $this->sendEmailToCustomer();

        if ($this->_errors === null) {
            $this->clearBasket();
        }
    }

    private function clearBasket() {
        // Empty basket
           $this->cart->truncate();
    }

    protected function checkForm() {

    }

    protected function basket() {

    }

    /***
     * @return bool
     */
    protected function sendEmailToCustomer() {
        $toName = sprintf("%s %s", $this->getPost('firstname'), $this->getPost('surnname'));

        $this->sendEmail($this->getPost('email'), $toName);

        return false;
    }

    /***
     * @return bool
     */
    protected function sendEmailToContinental() {
        $email = 'matthew.byfield@attercopia.co.uk';
        /*
        $order = $this->_objectManager->create('Magento\Sales\Model\Order')->load(1); // this is entity id
        $order->setCustomerEmail($email);
        if ($order) {
            try {
                $this->_objectManager->create('\Magento\Sales\Model\OrderNotifier')
                    ->notify($order);
                $this->messageManager->addSuccess(__('You sent the order email.'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__('We can\'t send the email order right now.'));
                $this->_objectManager->create('Magento\Sales\Model\OrderNotifier')->critical($e);
            }
        }

    }
        */
        return false;
    }

    private function getProtocol() {
        return ($this->storeManager->getStore()->isCurrentlySecure()) ? 'https://' : 'http://';
    }

    protected function sendEmail($to, $toName = "Continental Sports", $from = "test@continentalsports.co.uk", $fromName="Continental Sports Admin")
    {
        // Testing
        preg_match('/http.*?\/\/(.*)\//', $this->storeManager->getStore()->getBaseUrl(), $matches);
        // Use this for testing on other domains
        if (!empty($matches[1])) {
            $this->domain = $this->getProtocol() . $matches[1];
            $from = str_replace('continentalsports.co.uk', $matches[1], $from);
        }

        $to = "matthew.byfield@attercopia.co.uk";
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
                        'domain' => $this->domain
                    ])
                ->setFrom($sender)
                ->addTo($recipient)
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            $msg = 'Enquiry has been sent.';
            //echo $msg;
            $this->messageManager->addSuccess( __($msg) );
            // Clear the cart/basket as order received
            $this->clearBasket();
            // Redirect customer to specific thank you page
            $this->_redirect('order-enquiry-thanks');
            return;
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
            $msg = 'We can\'t process your request right now. Sorry, that\'s all we know.' . $e->getMessage();
            echo $msg;
            //$this->messageManager->addError( __($msg) );
            //$this->_redirect('*/*/');
            return;
        }
    }

    protected function getBasketItems() {
        $basketItems = $this->cart->getQuote()->getAllVisibleItems();
        $basket = '';
        foreach($basketItems as $item) {
            $basket .= '<br />ID: '. $item->getProductId().'<br />';
            $basket .= 'Name: '. $item->getName().'<br />';
            $basket .= 'Sku: '. $item->getSku().'<br />';
            $basket .= 'Quantity: '. $item->getQty().'<br />';
            $basket .= 'Price: '. $item->getPrice().'<br />';
            $basket .= "<br />";
        }

        return $basket;

    }

    public function getPost($str) {
        return !empty($this->_post[$str]) ? $this->_post[$str] : false;
    }
}
