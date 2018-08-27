<?php
    namespace Continental\Spares\Block;

    class Search extends \Magento\Framework\View\Element\Template
    {
        protected $scopeConfig;
        protected $collectionFactory;
        protected $objectManager;

        public function __construct(
            \Magento\Framework\View\Element\Template\Context $context,
            \Continental\Spares\Model\ResourceModel\Spares\CollectionFactory $collectionFactory,
            \Magento\Framework\ObjectManagerInterface $objectManager
        ) {

            $this->scopeConfig = $context->getScopeConfig();
            $this->collectionFactory = $collectionFactory;
            $this->objectManager = $objectManager;

            parent::__construct($context);
        }

        public function getSearchResults($str) {

        }


        public function getMediaDirectoryUrl()
        {

            $media_dir = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
                ->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

            return $media_dir;
        }

        public function getLayoutImge($product) {
            return true;
        }

    }
