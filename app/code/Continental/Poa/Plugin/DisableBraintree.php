<?php
	namespace Continental\Poa\Plugin;

    class DisableBraintree {

        /***
         * @var \Magento\Checkout\Model\Session
         */
        protected $session;

        protected $disabledForPoa;

        /***
         * @param \Magento\Checkout\Model\Session $checkoutSession
         * @param \Magento\Backend\Model\Session\Quote $backendQuoteSession
         * @param \Magento\Framework\App\State $state
         */
        public function __construct(
            \Magento\Checkout\Model\Session $checkoutSession,
            \Magento\Backend\Model\Session\Quote $backendQuoteSession,
            \Magento\Framework\App\State $state
        ) {
            if ($state->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML) {
                $this->session = $backendQuoteSession;
            } else {
                $this->session = $checkoutSession;
            }
        }

        /***
         * @param \Magento\Braintree\Model\PayPal $subject
         * @param $result
         * @return bool
         */
        public function afterIsAvailable(
/*            \Magento\Braintree\Model\PayPal $subject, */
            \Magento\Paypal\Model\Express
            $result
        ) {
            /** @var \Magento\Quote\Model\Quote $quote */
            $quote = $this->session->getQuote();
            /** @var \Magento\Quote\Model\Quote\Item[] $quoteItems */
            $quoteItems = $quote->getAllItems();
            // Check if any POA itemsin basket
            return false;
            /*foreach ($quoteItems as $quoteItem) {
                if (in_array($quoteItem->getSku(), $this->disabledForSku)) {
                    return false; // disable the method if we found product with specified sku
                }
            }
            return $result; // return default result
            */
        }

    }

