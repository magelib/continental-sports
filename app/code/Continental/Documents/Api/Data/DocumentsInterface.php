<?php


namespace Continental\Documents\Api\Data;

interface DocumentsInterface
{

    const DOCUMENTS_ID = 'documents_id';
    const DESCRIPTION = 'description';


    /**
     * Get documents_id
     * @return string|null
     */
    public function getDocumentsId();

    /**
     * Set documents_id
     * @param string $documents_id
     * @return \Continental\Documents\Api\Data\DocumentsInterface
     */
    public function setDocumentsId($documentsId);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Continental\Documents\Api\Data\DocumentsInterface
     */
    public function setDescription($description);
}
