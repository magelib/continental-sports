<?php
/**
 *  Poa class handles checkout for baskets containing poa items.
 */
    namespace Continental\Products;

    class Poa extends \Magento\Framework\App\Action\Action
    {
        /***
         * @var \Magento\Checkout\Model\Cart
         */
        protected $_cart;

        public function __construct(\Magento\Checkout\Model\Cart $cart) {
            $this->_cart = $cart;
        }

        public function execute() {
            $this->getBasket();
        }

        public function getBasket() {
            $basket = $this->_cart->getQuote()->getAllVisibleItems();
            foreach ($basket as $item) {
                var_dump($item->getData());
            }
        }

        protected function sendEmail() {
            // Get contact email

        }
    }


