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
 * @package   mirasvit/module-search
 * @version   1.0.42
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\Search\Model;

use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;
use Mirasvit\Search\Api\Data\IndexInterface;
use Mirasvit\Search\Api\Repository\IndexRepositoryInterface;
use Mirasvit\Search\Api\Service\IndexServiceInterface;
use Magento\Search\Model\QueryFactory;
use Magento\Search\Model\ResourceModel\Query\CollectionFactory as QueryCollectionFactory;

class AutocompleteProvider
{
    /**
     * @var IndexRepositoryInterface
     */
    private $indexRepository;

    /**
     * @var IndexServiceInterface
     */
    private $indexService;

    /**
     * @var QueryCollectionFactory
     */
    private $queryCollectionFactory;

    /**
     * @var QueryFactory
     */
    private $queryFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        IndexRepositoryInterface $indexRepository,
        IndexServiceInterface $indexService,
        QueryCollectionFactory $queryCollectionFactory,
        QueryFactory $queryFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->indexRepository = $indexRepository;
        $this->indexService = $indexService;
        $this->queryCollectionFactory = $queryCollectionFactory;
        $this->queryFactory = $queryFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndices()
    {
        $indices = [];
        $collection = $this->indexRepository->getCollection()
            ->addFieldToFilter(IndexInterface::IS_ACTIVE, 1);

        /** @var IndexInterface $index */
        foreach ($collection as $index) {
            $indices[] = new DataObject([
                'identifier' => $index->getIdentifier(),
                'title'      => $index->getTitle(),
            ]);
        }

        $indices[] = new DataObject([
            'title'      => __('Popular suggestions')->__toString(),
            'identifier' => 'magento_search_query',
        ]);

        return $indices;
    }

    /**
     * @param IndexInterface $index
     * @return array
     */
    public function getCollection($index)
    {
        if ($index->getIdentifier() == 'magento_search_query') {
            $query = $this->queryFactory->get();

            return $this->queryCollectionFactory->create()
                ->setQueryFilter($query->getQueryText())
                ->addFieldToFilter('query_text', ['neq' => $query->getQueryText()])
                ->addStoreFilter([$this->storeManager->getStore()->getId()])
                ->setOrder('popularity')
                ->distinct(true);
        }

        $index = $this->indexRepository->get($index->getIdentifier());

        return $this->indexService->getSearchCollection($index);
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryResponse($index)
    {
        if ($index->getIdentifier() == 'magento_search_query') {
            return false;
        }
        $index = $this->indexRepository->get($index->getIdentifier());

        return $this->indexService->getQueryResponse($index);
    }
}