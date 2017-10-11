<?php
namespace Continental\Spares\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        // most of the methods can be called directly in the .phtml view and just call the existing helpers
        //$params = $this->getRequest()->getParams();

        $resultPage = $this->resultPageFactory->create();
        //$resultPage->addHandle('spares_search_results'); //loads the layout of spares_search_results.xml file with its name
/*
        $block = $resultPage->getLayout()
            ->createBlock('Continental\Spares\Block\Search')
            ->setTemplate('Continental_Spares::search_results.phtml')
            ->toHtml();
        $this->getResponse()->setBody($block);
*/
        return $resultPage;
    }


}
