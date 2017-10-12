<?php


namespace Continental\Documents\Api\Data;

interface DocumentsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Documents list.
     * @return \Continental\Documents\Api\Data\DocumentsInterface[]
     */
    public function getItems();

    /**
     * Set description list.
     * @param \Continental\Documents\Api\Data\DocumentsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
