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



namespace Mirasvit\Kb\Controller\Adminhtml\Article;

class Save extends \Mirasvit\Kb\Controller\Adminhtml\Article
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getParams()) {
            $model = $this->_initModel();
<<<<<<< HEAD
            /* documents */
            if (!empty($data['documents'])) {
                $data['documents'] = implode(',', $data['documents']);
            }
=======
>>>>>>> matty

            if (!empty($data['categories'])) {
                $data['category_ids'] = explode(',', $data['categories']);
                if (!is_array($data['store_ids'])) {
                    $data['store_ids'] = explode(',', $data['store_ids']);
                }

                $categoryIds = [];
                $articleStoreIds = $this->articleManagement->getAvailableStores($model, $data['category_ids']);

                if (empty($data['store_ids'])) {
                    $data['store_ids'] = $articleStoreIds;
                } else {
                    if (in_array(0, $articleStoreIds)) { // if for all stores
                        $categoryIds = $data['store_ids'];
<<<<<<< HEAD
=======
                    } elseif (in_array(0, $data['store_ids'])) {
                        $categoryIds = [0];
>>>>>>> matty
                    } else {
                        foreach ($data['store_ids'] as $key => $storeId) {
                            if (in_array($storeId, $articleStoreIds)) {
                                $categoryIds[] = $data['store_ids'][$key];
                            }
                        }
                    }
                    $data['store_ids'] = array_unique($categoryIds);
                }
            }

            $model->addData($data);

            //@todo move to model _afterSave
            $this->kbTag->setTags($model, $data['tags']);

            //@todo kbHelper
            $this->kbData->setRating($model);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('Article was successfully saved'));
                $this->backendSession->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->backendSession->setFormData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);

                return;
            }
        }
        $this->messageManager->addError(__('Unable to find article to save'));
        $this->_redirect('*/*/');
    }
}
