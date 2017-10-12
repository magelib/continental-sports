<?php


namespace Continental\Documents\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DocumentsRepositoryInterface
{


    /**
     * Save Documents
     * @param \Continental\Documents\Api\Data\DocumentsInterface $documents
     * @return \Continental\Documents\Api\Data\DocumentsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Continental\Documents\Api\Data\DocumentsInterface $documents
    );

    /**
     * Retrieve Documents
     * @param string $documentsId
     * @return \Continental\Documents\Api\Data\DocumentsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($documentsId);

    /**
     * Retrieve Documents matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Continental\Documents\Api\Data\DocumentsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Documents
     * @param \Continental\Documents\Api\Data\DocumentsInterface $documents
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Continental\Documents\Api\Data\DocumentsInterface $documents
    );

    /**
     * Delete Documents by ID
     * @param string $documentsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($documentsId);
}
