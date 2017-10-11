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



namespace Mirasvit\SearchAutocomplete\Index;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mirasvit\SearchAutocomplete\Api\Data\Index\InstanceInterface;
use Mirasvit\SearchAutocomplete\Api\Data\IndexInterface;
use Mirasvit\SearchAutocomplete\Api\Repository\IndexRepositoryInterface;

abstract class AbstractIndex implements InstanceInterface
{
    /**
     * @var AbstractCollection
     */
    protected $collection;

    /**
     * @var IndexInterface
     */
    protected $index;

    /**
     * @var int
     */
    protected $limit = 10;

    /**
     * @var \Magento\Framework\Search\Response\QueryResponse|\Magento\Framework\Search\ResponseInterface
     */
    protected $queryResponse;

    /**
     * @var IndexRepositoryInterface
     */
    private $indexRepository;

    /**
     * @return $this
     */
    public function getCollection()
    {
        if (!$this->collection) {
            $this->collection = $this->indexRepository->getCollection($this->index);
            $this->collection->setPageSize($this->limit);

            if ($this->index->getIdentifier() !== 'magento_search_query') {
                if (method_exists($this->collection, 'getSelect')) {
                    $this->collection->getSelect()->order('score desc');
                }
            }
        }

        return $this->collection;
    }

    /**
     * @return false|\Magento\Framework\Search\Response\QueryResponse|\Magento\Framework\Search\ResponseInterface
     */
    public function getQueryResponse()
    {
        if (!$this->queryResponse) {
            $this->queryResponse = $this->indexRepository->getQueryResponse($this->index);
        }

        return $this->queryResponse;
    }

    /**
     * @return DataObject[]
     */
    public function getQueryResponseItems()
    {
        $items = [];

        if ($this->getQueryResponse()) {
            /** @var \Magento\Framework\Api\Search\Document $item */
            foreach ($this->getQueryResponse()->getIterator() as $item) {
                $data = $item->getCustomAttribute('data');

                if ($data) {
                    $value = $data->getValue();

                    if (isset($value['autocomplete_raw'])) {
                        $items[] = new DataObject($value['autocomplete_raw']);
                    }
                }
            }
        }

        return $items;
    }

    /**
     * @param IndexInterface $index
     * @return $this
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit > 0 ? $limit : 10;

        return $this;
    }

    /**
     * @param IndexRepositoryInterface $indexRepository
     * @return $this
     */
    public function setRepository(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        if ($this->getQueryResponse()) {
            return $this->getQueryResponse()->count();
        } else {
            return $this->getCollection()->getSize();
        }
    }

    /**
     * @return array
     */
    abstract public function getItems();
}
