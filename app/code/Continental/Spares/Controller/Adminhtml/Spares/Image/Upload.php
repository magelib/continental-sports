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
        \Continental\Spares\Model\LocatorFactory $sparesFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request
    ) {
        parent::__construct($context);
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
        $media  =  $mediaPath .'/spares/';
        $file_name = $_FILES['product']['name']['continental_sparesimages'];
        $file_size = $_FILES['product']['size']['continental_sparesimages'];
        $file_tmp =  $_FILES['product']['tmp_name']['continental_sparesimages'];
        $file_type=  $_FILES['product']['type']['continental_sparesimages'];

        if (move_uploaded_file($file_tmp,$media.$file_name))
        {
            echo $file_name;
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $product = $objectManager->get('Magento\Framework\Registry')->registry('current_product');//get current product

            // This won't work as ajax call
            $productId = (int) $this->request->getParam('id');
            echo $productId;
            exit();
//            echo $product->getSku();
            //echo $this->getProduct()->getSku();
            $this->messageManager->addSuccess(__('File has been successfully uploaded'));
            //$sampleModel = $this->sparesFactory->create();
            $sampleModel = $this->_objectManager->create('Continental\Spares\Model\Locator');

            // Check for existing image and ignore if same name
            /* now we need to update the database... */
            // Load the item with ID is 1
            /*
             * $sModel = $sampleModel->loadByAttribute('master_product_sku', 'Other');
            $item = $sampleModel->load(1);
            var_dump($item->getData());
            // Get sample collection
            $sampleCollection = $sampleModel->getCollection();
            // Load all data of collection
            var_dump($sampleCollection->getData());
*/
            // Save into database
            $sampleModel->setMaster_product_sku('sku test');
            $sampleModel->setSpareimage($file_name);
            $sampleModel->save();
            exit("ok");
        }
        else
        {
            echo "File was not uploaded";
        }

    }
}
