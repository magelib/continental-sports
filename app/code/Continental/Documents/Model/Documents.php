<?php


namespace Continental\Documents\Model;

use Continental\Documents\Api\Data\DocumentsInterface;

class Documents extends \Magento\Framework\Model\AbstractModel implements DocumentsInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Continental\Documents\Model\ResourceModel\Documents');
    }

    /**
     * Get documents_id
     * @return string
     */
    public function getDocumentsId()
    {
        return $this->getData(self::DOCUMENTS_ID);
    }

    /**
     * Set documents_id
     * @param string $documentsId
     * @return \Continental\Documents\Api\Data\DocumentsInterface
     */
    public function setDocumentsId($documentsId)
    {
        return $this->setData(self::DOCUMENTS_ID, $documentsId);
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Set description
     * @param string $description
     * @return \Continental\Documents\Api\Data\DocumentsInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }
}
