<?php
namespace Continental\Interactive\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;

class Interactive extends Template
{
    /***
     * @var $scopeConfig;
     */
    protected $scopeConfig;

    /***
     * @var \Continental\Interactive\Model\ResourceModel\Interactive\CollectionFactory
     */
    protected $collectionFactory;

    /***
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /***
     * @var bool $sinteractive
     */
    protected $interactive;

    /***
     * @var $interactiveCount
     */
    protected $interactiveCount;

    /***
     * @param Template\Context $context
     * @param \Continental\Interactive\Model\ResourceModel\Interactive\CollectionFactory $collectionFactory
     * @param ObjectManagerInterface $objectManager
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Continental\Interactive\Model\ResourceModel\Interactive\CollectionFactory $collectionFactory,
        ObjectManagerInterface $objectManager
    )
    {

        $this->scopeConfig = $context->getScopeConfig();
        $this->collectionFactory = $collectionFactory;
        $this->objectManager = $objectManager;
        $this->interactive = false;
        parent::__construct($context);
    }

    /***
     * Check for arguments,provided in block call
     * @return mixed
     */
    public function getFrontInteractive()
    {
        $collection = $this->collectionFactory->create()->addFieldToFilter('status', 1);


        if ($ids_list = $this->getInteractiveBlockArguments()) {
            $collection->addFilter('continental_interactive_spare_id', ['in' => $ids_list], 'public');
        }


        return $collection;
    }

    /***
     * @return array
     */
    public function getInteractiveBlockArguments()
    {

        $list = $this->getInteractiveList();

        $listArray = [];

        if ($list != '') {
            $listArray = explode(',', $list);
        }

        return $listArray;
    }

    /***
     * Get media directory for interactive images
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
     * Return the number of interactive available.
     * @return int
     */
    public function getInteractiveCount()
    {
        return $this->interactiveCount;
    }

    /***
     * Return interactive object if found and false if not.
     * @return mixed
     */
    public function getInteractive()
    {
        return $this->interactive;
    }

    /***
     *  Show front end javascript to randomise background images
     */
    public function InteractiveJsArray()
    {

        $interactive = $this->getFrontInteractive();

        $this->interactiveCount = count($interactive);

        $interactiveIndex = 1;

        if ($this->interactiveCount > 0) {
            foreach ($interactive as $index => $interactiveObj) {
                $title = trim($interactiveObj->getTitle());

                if (preg_match('/^<p>(.*)<\/p>$/', $title, $matches)) {
                    $title = $matches[1];
                }

                if ($index === $interactiveIndex) {
                    $this->interactive = $interactiveObj;
                    $pubTitle = $title;
                }

                echo PHP_EOL;
                echo ' Interactive[' . $index . '] = { ';
                echo ' image:"' . $interactiveObj->getInteractiveimage() . '", title:"' . $title . '", ';
                echo ' linktext:"', $interactiveObj->getLinktext() . '", ';
                echo ' text:"' . $interactiveObj->getInteractivetext() . '", target:"' . $interactiveObj->getTarget() . '", style:"';
                echo ($interactiveObj->getTextcolour() == 0) ? '#333333' : '';
                echo '" };';
            }
        }
    }
}
