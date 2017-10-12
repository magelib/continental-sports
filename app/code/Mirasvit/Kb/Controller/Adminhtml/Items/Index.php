<?php
/**
 * Copyright © 2015 Attercopia. All rights reserved.
 */
namespace Mirasvit\Kb\Controller\Adminhtml\Items;

class Index extends  \Mirasvit\Kb\Controller\Adminhtml\Items
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        exit("holding pattern");
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mirasvit_Kb::Kb');
        $resultPage->getConfig()->getTitle()->prepend(__('Items'));
        $resultPage->addBreadcrumb(__('Mirasvit'), __('Mirasvit'));
        $resultPage->addBreadcrumb(__('Items'), __('Items'));
        return $resultPage;
    }
}
