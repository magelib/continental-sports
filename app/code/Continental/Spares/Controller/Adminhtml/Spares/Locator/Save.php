<?php
/**
 * Copyright © 2017 Attercopia. All rights reserved.
 */
namespace Continental\Spares\Controller\Adminhtml\Spares\Locator;

use Magento\Framework\Controller\ResultFactory;
use Continental\Spares\Model\LocatorFactory;
use Continental\Spares\Model\Spares;

/**
 * Class Save - saves Spares Location
 */
class Save extends \Magento\Backend\App\Action
{
    /***
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /***
     * @var LocatorFactory
     */
    protected $sparesFactory;
    /***
     * @var
     */
    protected $request;
    /**
     * @var $spares
     */
    protected $spares;

    /***
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * Save constructor.
     *
     * @param     \Magento\Backend\App\Action\Context $context,
     * @param     \Continental\Spares\Model\SparesFactory $sparesFactory,
     * @param     \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
     * @param     \Magento\Framework\Registry $registry,
     * @param     \Magento\Framework\App\Request\Http $request,
     * @param     \Continental\Spares\Model\Locator $locator
     */

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Continental\Spares\Model\SparesFactory $sparesFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        \Continental\Spares\Model\Spares $spares
    )
    {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->sparesFactory = $sparesFactory;
        $this->directory_list = $directory_list;
        $this->registry = $registry;
        $this->request = $request;
        $this->spares = $spares;
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
     * Save data controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */

    public function execute()
    {
        $productId = $this->getRequest()->getParam('id');
        $co_ords1 = $this->formatLocation( $this->getRequest()->getParam('co_ords1') );
        $co_ords2 = $this->formatLocation( $this->getRequest()->getParam('co_ords2') );
        $sparesimage = $this->getRequest()->getParam('sparesimage');

        $_product = $this->productRepository->getById($productId);

        // Use for post $postData = $this->getRequest()->getPostValue();
        // Fields with numbers didn't work....??

        $model = $this->spares;
/*        $model->setMaster_product_sku($_product->getSku());
        $model->setCo_ords1($co_ords1);
        $model->setCo_ords2($co_ords2);
        $model->save();*/


        $data = array(
            'master_product_sku' => $_product->getSku(),
            'location' => $co_ords1,
            'dimensions' => $co_ords2,
            'spareimage' => $sparesimage
            );

        // test
        $model->setData($data);

        $model->save();

        exit("Stpop 1");
        // Redirect
        $resultRedirect = $this->resultRedirectFactory->create();
        try{
            $this->messageManager->addSuccess(__('Save sucessful.'));
        }catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;

    }

    private function formatLocation($coord) {
        if (preg_match('/,/', $coord)) {
            list($x, $y) = explode(',', $coord);
            return sprintf("%d,%d", number_format($x), number_format($y));
        }
        return $coord;
    }
}