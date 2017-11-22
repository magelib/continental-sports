<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
<<<<<<< HEAD
 * @version   1.0.29
=======
 * @version   1.0.41
>>>>>>> matty
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */


<<<<<<< HEAD

=======
>>>>>>> matty
namespace Mirasvit\Kb\Controller\Adminhtml\Article;

use Magento\Framework\Controller\ResultFactory;

<<<<<<< HEAD
class MassDelete extends \Mirasvit\Kb\Controller\Adminhtml\Article
{
=======
class MassDelete extends \Magento\Backend\App\Action
{
    public function __construct(
        \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory $collectionFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->filter            = $filter;
        $this->context           = $context;
        $this->collectionFactory = $collectionFactory;

        parent::__construct($context);
    }

>>>>>>> matty
    /**
     *
     */
    public function execute()
    {
<<<<<<< HEAD
        $ids = $this->getRequest()->getParam('article_id');
        if (!is_array($ids)) {
            $this->messageManager->addError(__('Please select article(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = $this->articleFactory->create()
                        ->load($id);
                    $model->delete();
                }
                $this->messageManager->addSuccess(
                    __('Total of %1 record(s) were successfully deleted', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}
=======
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if (!$this->getRequest()->getParams('namespace')) {
            return $resultRedirect->setPath('*/*/');
        }

        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $object) {
            $object->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));


        return $resultRedirect->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->context->getAuthorization()->isAllowed('Mirasvit_Kb::kb_article');
    }
}
>>>>>>> matty
