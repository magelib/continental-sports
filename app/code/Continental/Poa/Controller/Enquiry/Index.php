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
            echo "Currently in dev mode";
        }
        $this->_post = $this->getRequest()->getPostValue();
        print_r($this->_post);
        $this->sendEmailsOut();
        //return $this->resultPageFactory->create();
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

    protected function sendEmail($to, $toName = "Continental Sports", $from = "test@continentalsports.co.uk", $fromName="Continental Sports Admin")
    {
        echo "sending to " . $to;
        try {
            $sender = [
                'name' => $this->_escaper->escapeHtml($fromName),
                'email' => $this->_escaper->escapeHtml($from)
            ];

            $recipient = [
                'name' => $this->_escaper->escapeHtml($toName),
                'email' => $this->_escaper->escapeHtml($to)
            ];

            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('send_customer_email_template')// this code we have mentioned in the email_templates.xml
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $this->_post])
                ->setFrom($sender)
                ->addTo($recipient)
                ->getTransport();

            $transport->sendMessage();;
            $this->inlineTranslation->resume();
            $msg = 'Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.';
            echo $msg;
            //$this->messageManager->addSuccess( __($msg) );
            //$this->_redirect('*/*/');
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

    public function getPost($str) {
        return !empty($this->_post[$str]) ? $this->_post[$str] : false;
    }
}
