<?php
namespace Continental\HomeBanners\Controller\Adminhtml\Index;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Create new Banner  Template
     *
     * @return void
     */

    /*
    public function execute()
    {
       echo "Placeholder";
    }
    */

    public function execute()
    {
        $model = $this->_objectManager->create('Continental\HomeBanners\Model\Template');
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $model->load($id);
        }

        $this->_coreRegistry->register('_homebanners_template', $model);

        $this->_view->loadLayout();
        $this->_setActiveMenu('Continental_HomeBanners::homebanners_template');

        if ($model->getId()) {
            $breadcrumbTitle = __('Edit Banner');
            $breadcrumbLabel = $breadcrumbTitle;
        } else {
            $breadcrumbTitle = __('New Banner');
            $breadcrumbLabel = __('Create  Banner');
        }
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__(' Banners'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getTemplateId() : __('New Banner')
        );

        $this->_addBreadcrumb($breadcrumbLabel, $breadcrumbTitle);

        // restore data
        $values = $this->_getSession()->getData('homebanner_template_form_data', true);
        if ($values) {
            $model->addData($values);
        }

        $this->_view->renderLayout();
    }
}
