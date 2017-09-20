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
    /**
     * Upload constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\ImageUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Continental\Spares\Model\LocatorFactory $sparesFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list
    ) {
        parent::__construct($context);
        $this->sparesFactory = $sparesFactory;
        $this->directory_list = $directory_list;
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
            $this->messageManager->addSuccess(__('File has been successfully uploaded'));
            //$sampleModel = $this->sparesFactory->create();
            $sampleModel = $this->_objectManager->create('Continental\Spares\Model\Locator');
            /* now we need to update the database... */
            /*
            $sampleModel = $this->sparesFactory->create();
            echo "model setup";
            /*
            // Load the item with ID is 1
            $item = $sampleModel->load(1);
            var_dump($item->getData());
            // Get sample collection
            $sampleCollection = $sampleModel->getCollection();
            // Load all data of collection
            var_dump($sampleCollection->getData());
*/

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
