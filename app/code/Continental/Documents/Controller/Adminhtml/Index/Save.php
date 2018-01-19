<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Continental\Documents\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Continental\Documents\Model\Documents;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\RequestInterface;

//use Magento\Framework\Stdlib\DateTime\DateTime;
//use Magento\Ui\Component\MassAction\Filter;
//use Continental\News\Model\ResourceModel\Test\CollectionFactory;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    protected $scopeConfig;
   
    protected $_escaper;
    protected $inlineTranslation;
    protected $_dateFactory;
    //protected $_modelNewsFactory;
  //  protected $collectionFactory;
   //  protected $filter;
    /**
     * @param Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateFactory
    ) {
       // $this->filter = $filter;
       // $this->collectionFactory = $collectionFactory;
        $this->dataPersistor = $dataPersistor;
         $this->scopeConfig = $scopeConfig;
         $this->_escaper = $escaper;
        $this->_dateFactory = $dateFactory;
         $this->inlineTranslation = $inlineTranslation;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('documents_id');

            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Block::STATUS_ENABLED;
            }
            if (empty($data['documents_id'])) {
                $data['documents_id'] = null;
            }

           
            /** @var \Magento\Cms\Model\Block $model */
            $model = $this->_objectManager->create('Continental\Documents\Model\Documents')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This Document no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            
            if (isset($data['documentfile'][0]['name']) && isset($data['documentfile'][0]['tmp_name'])) {
                $data['documentfile'] ='/documents/'.$data['documentfile'][0]['name'];
            } elseif (isset($data['documentfile'][0]['name']) && !isset($data['image'][0]['tmp_name'])) {
                $data['documentfile'] =$data['documentfile'][0]['name'];
            } else {
                $data['documentfile'] = null;
            }


           
            $model->setData($data);

            $this->inlineTranslation->suspend();
            try {
                    //////////////////// email
                $model->save();
                $this->messageManager->addSuccess(__('Document Saved successfully'));
                $this->dataPersistor->clear('continental_documents');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['documents_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the document.'));
            }

            $this->dataPersistor->set('continental_documents', $data);
            return $resultRedirect->setPath('*/*/edit', ['documents_id' => $this->getRequest()->getParam('documents_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
