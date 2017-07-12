<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Controller\Adminhtml\Econtacts;

class Delete extends \Me\Econtacts\Controller\Adminhtml\Econtacts
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('econtacts_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager
                    ->create('Me\Econtacts\Model\Econtacts');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(
                    __('You deleted the contact.')
                );
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['econtacts_id' => $id]
                );
            }
        }
        // display error message
        $this->messageManager->addError(
            __('We can\'t find the contact to delete.')
        );
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
