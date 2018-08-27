<?php

namespace Continental\Interactive\Controller\Adminhtml\Index;

class Grid extends \Magento\Backend\App\Action
{

    protected $resultPageFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        //Call page factory to render layout and page content
        $this->_setPageData();
        return $this->getResultPage();
    }

    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Continental_Interactive::interactive_manage');
    }

    public function getResultPage()
    {
        if (empty($this->_resultPage)) {
            $this->_resultPage = $this->_resultPageFactory->create();
        }
        return $this->_resultPage;
    }

    protected function _setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->setActiveMenu('Continental_Interactive::interactive');
        $resultPage->getConfig()->getTitle()->prepend((__('Spares Locations')));

        //Add bread crumb
        $resultPage->addBreadcrumb(__('Continental'), __('Continental'));
        $resultPage->addBreadcrumb(__('Interactive'), __('Spares Co-ordinates'));

        return $this;
    }

}