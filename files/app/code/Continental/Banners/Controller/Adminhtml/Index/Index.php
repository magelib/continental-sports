<?php
namespace Continental\Banners\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
        //Call page factory to render layout and page content
        $resultPage = $this->resultPageFactory->create();
        //Set the menu which will be active for this page
        $resultPage->setActiveMenu('Continental_Banners::banner_manage');

        //Set the header title of grid
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Banners'));
        //Add bread crumb
        $resultPage->addBreadcrumb(__('Continental'), __('Continental'));
        $resultPage->addBreadcrumb(__('Continental'), __('Manage Banners'));
        return $resultPage;
    }
    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Continental_Banners::banner_manage');
    }
}