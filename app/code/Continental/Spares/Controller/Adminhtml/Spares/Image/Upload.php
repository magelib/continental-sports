<?php
/**
 * Copyright © 2017 Attercopia. All rights reserved.
 */
namespace Continental\Spares\Controller\Adminhtml\Spares\Image;

use Magento\Framework\Controller\ResultFactory;
use Continental\Spares\Model\LocatorFactory;

/**
 * Class Upload
 */
class Upload extends \Magento\Backend\App\Action
{
    /**
     * Image uploader
     *
     * @var \Magento\Catalog\Model\ImageUploader
     */
    protected $baseTmpPath;
    protected $sparesModel;
    protected $filesystem;
    protected $registry;
    protected $product;
    protected $request;

    /**
     * Upload constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\ImageUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Continental\Spares\Model\LocatorFactory $sparesFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Registry $registry,

        \Magento\Framework\App\Request\Http $request
    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->sparesFactory = $sparesFactory;
        $this->directory_list = $directory_list;
        $this->registry = $registry;
        $this->request;
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Continental_Spares::spares');
    }

    private function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = $this->registry->registry('product');

            if (!$this->product->getId()) {
                throw new LocalizedException(__('Failed to initialize product'));
            }
        }

        return $this->product;
    }

    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $mediaPath = $this->directory_list->getPath('media');
        $media = $mediaPath . '/spares/';

        if (isset($_FILES['product'])) { // Direct download
            $file_name = $_FILES['product']['name']['continental_sparesimages'];
            $file_size = $_FILES['product']['size']['continental_sparesimages'];
            $file_tmp = $_FILES['product']['tmp_name']['continental_sparesimages'];
            $file_type = $_FILES['product']['type']['continental_sparesimages'];
        } else { // From modal popup
            $file_name = $_FILES['file']['name'];
            $file_size = $_FILES['file']['size'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_type = $_FILES['file']['type'];
        }

        if (move_uploaded_file($file_tmp, $media . $file_name)) {
            $productId = (int)$this->getRequest()->getParam('id');

            // Get sku
            $sku = $this->productRepository->getById($productId)->getSku();

            $this->messageManager->addSuccess(__('File has been successfully uploaded !'));

            // Save into database
            $sampleModel = $this->_objectManager->create('Continental\Spares\Model\Locator');
            $sampleModel->setMaster_product_sku($sku);
            $sampleModel->setSpareimage($file_name);
            $sampleModel->save();
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            // Your code
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            $this->messageManager->addError(__('File was not uploaded !'));

            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
    }
}
