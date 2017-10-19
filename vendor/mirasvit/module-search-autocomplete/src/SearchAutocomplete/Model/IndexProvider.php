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
 * @package   mirasvit/module-search-autocomplete
 * @version   1.1.17
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\SearchAutocomplete\Model;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mirasvit\SearchAutocomplete\Api\Data\IndexProviderInterface;
use Mirasvit\SearchAutocomplete\Api\Data\IndexInterface;
use Magento\Search\Model\QueryFactory;
use Magento\Search\Model\ResourceModel\Query\CollectionFactory as QueryCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;

class IndexProvider implements IndexProviderInterface
{
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

    /**
     * @var LayerResolver
     */
    private $layerResolver;

    public function __construct(
        QueryCollectionFactory $queryCollectionFactory,
        QueryFactory $queryFactory,
        LayerResolver $layerResolver,
        StoreManagerInterface $storeManager
    ) {
        $this->queryCollectionFactory = $queryCollectionFactory;
        $this->queryFactory = $queryFactory;
        $this->storeManager = $storeManager;
        $this->layerResolver = $layerResolver;
    }

    /**
     * @return array
     */
    public function getIndices()
    {
        $indices = [];

        $indices[] = new DataObject([
            'title'      => __('Products')->__toString(),
            'identifier' => 'catalogsearch_fulltext',
        ]);

        $indices[] = new DataObject([
            'title'      => __('Popular suggestions')->__toString(),
            'identifier' => 'magento_search_query',
        ]);

        return $indices;
    }

    /**
     * @param IndexInterface $index
     * @return AbstractCollection
     * @throws \Exception
     */
    public function getCollection($index)
    {
        $query = $this->queryFactory->get();

        if ($index->getIdentifier() == 'magento_search_query') {
            return $this->queryCollectionFactory->create()
                ->setQueryFilter($query->getQueryText())
                ->addFieldToFilter('query_text', ['neq' => $query->getQueryText()])
                ->addStoreFilter([$this->storeManager->getStore()->getId()])
                ->setOrder('popularity')
                ->distinct(true);
        } elseif ($index->getIdentifier() == 'catalogsearch_fulltext') {
            return $this->layerResolver->get()->getProductCollection();
        } else {
            throw new \Exception("Undefined index");
        }
    }

    /**
     * @param IndexInterface $index
     * @return bool
     * @SuppressWarnings(PHPMD)
     */
    public function getQueryResponse($index)
    {
        return false;
    }
}