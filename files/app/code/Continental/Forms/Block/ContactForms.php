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

	return $results;
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
