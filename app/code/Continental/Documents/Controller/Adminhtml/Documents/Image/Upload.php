<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Continental\Documents\Controller\Adminhtml\Documents\Image;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class Upload
 */
class Upload extends \Magento\Backend\App\Action
{
    /**
     * Image uploader
     *
     * @var \Magento\Catalog\Model\DocumentUploader
     */
    protected $baseTmpPath;
    protected $imageUploader;

    /**
     * Upload constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\DocumentUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Continental\Documents\Model\DocumentUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Continental_Documents::documents');
    }

    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        try {
            $result = $this->imageUploader->saveFileToTmpDir('documentfile');

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
