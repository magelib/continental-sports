<?php
namespace Continental\General\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
/**
 * Created by PhpStorm.
 * User: MattB
 * Date: 06/07/2017
 * Time: 16:07
 */

class Core extends AbstractHelper
{

	public function __construct(
	\Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer) {
	$this->_currentCustomer = $currentCustomer;
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


        if ($this->_currentCustomer->isLoggedIn()) {
            $details['firstname'] = $this->_currentCustomer->getCustomer()->getFirstname();
            $details['surname']   = $this->_currentCustomer->getCustomer()->getLastname();
            $details['email']     = $this->_currentCustomer->getCustomer()->getEmail();

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

}
