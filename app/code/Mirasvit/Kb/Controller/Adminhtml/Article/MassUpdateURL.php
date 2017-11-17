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
 * @version   1.0.41
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\Kb\Controller\Adminhtml\Article;

use Magento\Framework\Controller\ResultFactory;

class MassUpdateURL extends \Magento\Backend\App\Action
{
    public function __construct(
        \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory $collectionFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Backend\App\Action\Context $context,
        \Mirasvit\Kb\Model\Config $config,
        \Mirasvit\Core\Api\UrlRewriteHelperInterface $urlRewrite,
        \Magento\Framework\App\CacheInterface $cacheManager
    ) {
        $this->filter            = $filter;
        $this->context           = $context;
        $this->config            = $config;
        $this->urlRewrite        = $urlRewrite;
        $this->cacheManager      = $cacheManager;
        $this->collectionFactory = $collectionFactory;

        parent::__construct($context);
    }

    /**
     *
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if (!$this->getRequest()->getParams('namespace')) {
            return $resultRedirect->setPath('*/*/');
        }

        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $article) {
            $categoryKey = '';
            $article->afterLoad();
            if ($category = $article->getCategory()) {
                if (!$this->config->getCategoryURLExcluded()) {
                    $categoryKey = $category->getUrlKey();
                }

                $this->cacheManager->clean([$article::CACHE_KB_ARTICLE_CATEGORY . '_' . $category->getId()]);
            }

            $this->urlRewrite->updateUrlRewrite(
                'KBASE',
                'ARTICLE',
                $article,
                [
                    'article_key'  => $article->getUrlKey(),
                    'category_key' => $categoryKey
                ]
            );

        }

        $this->messageManager->addSuccessMessage(__('A total of %1 article(s) have been updated.', $collectionSize));


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