<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Controller\Adminhtml\Econtacts;

class Edit extends \Me\Econtacts\Controller\Adminhtml\Econtacts
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit Econtacts block
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('econtacts_id');
        $model = $this->_objectManager->create('Me\Econtacts\Model\Econtacts');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(
                    __('This item no longer exists.')
                );
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')
            ->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('me_econtact', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();

        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Contact') : __('New Contact'),
            $id ? __('Edit Contact') : __('New Contact')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Keep Contacts'));
        $resultPage->getConfig()->getTitle()->prepend($model->getName());
        return $resultPage;
    }
}
