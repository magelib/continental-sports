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
        array $data = [],
        \Magento\Framework\ObjectManagerInterface $objectmanager 
   )
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
        $this->directoryBlock = $directoryBlock;
        $this->checkSubmission();
	$this->_objectmanager = $objectmanager;
    }

    public function checkSubmission()
    {
        $email = $this->getRequest()->getPost('email');
        if (!empty($email)) {
            exit("submission");
        }
    }

    public function customerDetails()
    {
       
	 $details = array(
            'firstname' => '',
            'surname' => '',
            'email' => '',
            'street' => '',
            'city' => '',
            'postcode' => '',
	    'country_id' => '',
	    'company' => '',
            'telephone' => '',
            'street' => ''
        );

/*
        $customerSession = $om->get('Magento\Customer\Model\Session');

        if ($customerSession->isLoggedIn()) {
            $details['firstname'] = $customerSession->getCustomer()->getFirstname();
            $details['surname'] = $customerSession->getCustomer()->getLastname();
            $details['email'] = $customerSession->getCustomer()->getEmail();


            /* get address details 
            foreach ($customerSession->getAddresses() as $address) {
                $customerAddress[] = $address->toArray();
            }

            foreach ($customerAddress as $customerAddres) {
                $details['street'] = $customerAddres['street'];
                $details['city'] = $customerAddres['city'];
                $details['telephone'] = $customerAddres['telephone'];
                $details['postcode'] = $customerAddres['postcode'];
                $details['country_id'] = $customerAddres['country_id'];
                $details['company'] = $customerAddres['company'];
            }

        }
*/
        return $details;
    }

    public function braintreeHosted()
    {
        $amount = $_POST['amount'];
        $nonceFromTheClient = $_POST["payment_method_nonce"];
        $invoice = $_POST['invoice'];

        $firstname = $lastname = $company = $telephone = $email = $address1 = $address2 = $county = $country = $postcode = null;

        $fields = array('county', 'lastname', 'firstname', 'company', 'telephone', 'email', 'postcode', 'country');

        foreach ($fields as $val) {
            ${$val} = $this->getRequest()->getPost($val);
        }

        $result = Braintree_Transaction::sale([
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
