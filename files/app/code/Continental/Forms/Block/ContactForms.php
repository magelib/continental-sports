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
        array $data = []
   )
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
        $this->directoryBlock = $directoryBlock;
        $this->checkSubmission();
    }

    public function checkSubmission()
    {
        $email = $this->getRequest()->getPost('email');
        if (!empty($email)) {
	        var_dump($this->braintreeHosted() );
            exit("submission");
        }
    }


    public function braintreeHosted()
    {
        $amount = $_POST['amount'];

        $nonceFromTheClient = $_POST["payment-method-nonce"];

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
