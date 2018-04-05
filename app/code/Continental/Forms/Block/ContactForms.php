<?php
namespace Continental\Forms\Block;

/**
 * Features block
 */

use Magento\Framework\View\Element\Template;

class ContactForms extends \Magento\Contact\Block\ContactForm
{

    public function __construct(
        Template\Context $context,
        \Magento\Directory\Block\Data $directoryBlock,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
        $this->directoryBlock = $directoryBlock;
        $this->messageManager = $messageManager;
        $this->checkSubmission();
    }

    public function checkSubmission()
    {
        $email = $this->getRequest()->getPost('email');
        if (!empty($email)) {
            //var_dump($this->braintreeHosted() );
            $x = $this->braintreeHosted();
            if (isset($x->success) && $x->success) {
                // send email and update
                $this->saveContact();
                //redirect to thank you page
                return $this->messageManager->addSuccess('Payment registered !');
            }
            else {
                return $this->messageManager->addError('Payment failed, please try again !');
            }
        }
    }

    private function saveContact()
    {
        // Build comment
        $comment = '';
        $comment .= 'Reference: ' . $this->getRequest()->getPost('reference') . PHP_EOL;
        $comment .= 'Amount: ' . $this->getRequest()->getPost('amount') . PHP_EOL;
        $comment .= 'Message: ' . $this->getRequest()->getPost('message') . PHP_EOL;
        $comment .= 'Company: ' . $this->getRequest()->getPost('company') . PHP_EOL;
        $comment .= 'Address: ' . $this->getRequest()->getPost('address1') . PHP_EOL;
        if ($this->getRequest()->getPost('address2')) {
            $comment .= '         ' . $this->getRequest()->getPost('address2') . PHP_EOL;
        }
        if ($this->getRequest()->getPost('address2')) {
            $comment .= '         ' . $this->getRequest()->getPost('address2') . PHP_EOL;
        }
        $comment .= '         ' . $this->getRequest()->getPost('country') . PHP_EOL;
        $comment .= '         ' . $this->getRequest()->getPost('postcode') . PHP_EOL;

        $data = array(
            "name" => $this->getRequest()->getPost('firstname') . " " . $this->getRequest()->getPost('lastname'),
            "email" => $this->getRequest()->getPost('email'),
            "telephone" => $this->getRequest()->getPost('telephone'),
            "comment" => $comment,
            "store_id" => "1"
        );

        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $om->create('Me\Econtacts\Model\Econtacts');
        $model->addData($data);
        $model->save();
    }

    public function braintreeHosted()
    {
        if(isset($_POST['amount']) &&
        isset($_POST['reference'])) {
            $amount = $_POST['amount'];

            $nonceFromTheClient = isset($_POST["payment-method-nonce"]) ? $_POST["payment-method-nonce"] : '';

            $invoice = $_POST['reference'];

            $firstname = $lastname = $company = $telephone = $email = $address1 = $address2 = $county = $country = $postcode = null;

            $fields = array('county', 'lastname', 'firstname', 'company', 'telephone', 'email', 'postcode', 'country');

            foreach ($fields as $val) {
                ${$val} = $this->getRequest()->getPost($val);
            }

            /* Need to use Braintree adapter - use this for testing */
            \Braintree\Configuration::merchantId('hskdkw4bv8v49vr9');
            \Braintree\Configuration::environment('sandbox');
            \Braintree\Configuration::publicKey('j8w5xhzjs9bvb2rv');
            \Braintree\Configuration::privateKey('ff2238a060754e6d3f4673618f110a60');

            $result = \Braintree\Transaction::sale([
                'amount' => $amount,
                'orderId' => $invoice,
                'paymentMethodNonce' => $nonceFromTheClient,
                'customer' => [
                    'firstName' => $firstname,
                    'lastName' => $lastname,
                    'company' => $company,
                    'phone' => $telephone,
                    'email' => $email
                ],
                'billing' => [
                    'firstName' => $firstname,
                    'lastName' => $lastname,
                    'company' => $company,
                    'streetAddress' => $address1,
                    'extendedAddress' => $address2,
                    'locality' => $county,
                    'postalCode' => $postcode,
                    'countryCodeAlpha2' => $country
                ],
                'shipping' => [
                    'firstName' => $firstname,
                    'lastName' => $lastname,
                    'company' => $company,
                    'streetAddress' => $address1,
                    'extendedAddress' => $address2,
                    'locality' => $county,
                    'postalCode' => $postcode,
                    'countryCodeAlpha2' => $country
                ],
                'options' => [
                    'submitForSettlement' => true
                ]
            ]);

            return $result;
        }
    }

    public function getCountries()
    {
        $country = $this->directoryBlock->getCountryHtmlSelect();
        return $country;
    }

    public function getRegion()
    {
        $region = $this->directoryBlock->getRegionHtmlSelect();
        return $region;
    }
}
