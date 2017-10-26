<?php
namespace Continental\Documents\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;

class Documents extends Template
{
    /***
     * @var $scopeConfig;
     */
    protected $scopeConfig;

    /***
     * @var \Continental\Documents\Model\ResourceModel\Documents\CollectionFactory
     */
    protected $collectionFactory;

    /***
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /***
     * @var bool $document
     */
    protected $document;

    /***
     * @var $documentCount
     */
    protected $documentCount;

    /***
     * @param Template\Context $context
     * @param \Continental\Documents\Model\ResourceModel\Documents\CollectionFactory $collectionFactory
     * @param ObjectManagerInterface $objectManager
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Continental\Documents\Model\ResourceModel\Documents\CollectionFactory $collectionFactory,
        ObjectManagerInterface $objectManager
    )
    {

        $this->scopeConfig = $context->getScopeConfig();
        $this->collectionFactory = $collectionFactory;
        $this->objectManager = $objectManager;
        $this->document = false;
        parent::__construct($context);
    }

    /***
     * Check for arguments,provided in block call
     * @return mixed
     */
    public function getFrontDocuments()
    {
        $collection = $this->collectionFactory->create()->addFieldToFilter('status', 1);


        if ($ids_list = $this->getDocumentBlockArguments()) {
            $collection->addFilter('documents_id', ['in' => $ids_list], 'public');
        }


        return $collection;
    }

    /***
     * @return array
     */
    public function getDocumentBlockArguments()
    {

        $list = $this->getDocumentList();

        $listArray = [];

        if ($list != '') {
            $listArray = explode(',', $list);
        }

        return $listArray;
    }

    /***
     * Get media directory for document images
     * @return mixed
     */
    public function getMediaDirectoryUrl()
    {

        $media_dir = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $media_dir;
    }

    /***
     * Return the number of documents available.
     * @return int
     */
    public function getDocumentCount()
    {
        return $this->documentCount;
    }

    /***
     * Return document object if found and false if not.
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

}
