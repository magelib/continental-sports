<?php


namespace Continental\Documents\Model;

use Continental\Documents\Api\DocumentsRepositoryInterface;
use Continental\Documents\Api\Data\DocumentsSearchResultsInterfaceFactory;
use Continental\Documents\Api\Data\DocumentsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Continental\Documents\Model\ResourceModel\Documents as ResourceDocuments;
use Continental\Documents\Model\ResourceModel\Documents\CollectionFactory as DocumentsCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class DocumentsRepository implements documentsRepositoryInterface
{

    protected $resource;

    protected $documentsFactory;

    protected $documentsCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataDocumentsFactory;

    private $storeManager;


    /**
     * @param ResourceDocuments $resource
     * @param DocumentsFactory $documentsFactory
     * @param DocumentsInterfaceFactory $dataDocumentsFactory
     * @param DocumentsCollectionFactory $documentsCollectionFactory
     * @param DocumentsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceDocuments $resource,
        DocumentsFactory $documentsFactory,
        DocumentsInterfaceFactory $dataDocumentsFactory,
        DocumentsCollectionFactory $documentsCollectionFactory,
        DocumentsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->documentsFactory = $documentsFactory;
        $this->documentsCollectionFactory = $documentsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDocumentsFactory = $dataDocumentsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Continental\Documents\Api\Data\DocumentsInterface $documents
    ) {
        /* if (empty($documents->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $documents->setStoreId($storeId);
        } */
        try {
            $documents->getResource()->save($documents);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the documents: %1',
                $exception->getMessage()
            ));
        }
        return $documents;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($documentsId)
    {
        $documents = $this->documentsFactory->create();
        $documents->getResource()->load($documents, $documentsId);
        if (!$documents->getId()) {
            throw new NoSuchEntityException(__('Documents with id "%1" does not exist.', $documentsId));
        }
        return $documents;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->documentsCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Continental\Documents\Api\Data\DocumentsInterface $documents
    ) {
        try {
            $documents->getResource()->delete($documents);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Documents: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($documentsId)
    {
        return $this->delete($this->getById($documentsId));
    }
}
