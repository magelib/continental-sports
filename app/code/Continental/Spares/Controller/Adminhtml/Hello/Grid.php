<?php
/**
 Admin Hello Grid Controller
     *
     * @category    Webkul
     * @package     Webkul_Hello
     * @author      Webkul Software Private Limited
     *
     */
    namespace Continental\Spares\Controller\Adminhtml\Hello;
 
    class Grid extends \Continental\Spares\Controller\Adminhtml\Hello
    {
        /**
         * @var \Magento\Framework\View\Result\LayoutFactory
         */
        protected $resultLayoutFactory;
 
        public function __construct(
            \Magento\Backend\App\Action\Context $context,
            \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
        ) {
            parent::__construct($context);
            $this->resultLayoutFactory = $resultLayoutFactory;
        }
 
        /**
         * @return \Magento\Framework\View\Result\Layout
         */
        public function execute()
        {
            $resultLayout = $this->resultLayoutFactory->create();
            $resultLayout->getLayout()->getBlock('hello.hello.edit.tab.grid');
            return $resultLayout;
        }
 
    }
