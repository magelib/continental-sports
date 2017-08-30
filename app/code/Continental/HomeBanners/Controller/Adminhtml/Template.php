<?php

namespace Continental\HomeBanners\Controller\Adminhtml;

/**
 *  controller
 */
abstract class Template extends \Magento\Backend\App\Action
{
    /**
     * @var \Amasty\HidePrice\Model\RequestRepository
     */
    protected $requestRepository;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Initiate action
     *
     * @return $this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();

        return $this;
    }
}


