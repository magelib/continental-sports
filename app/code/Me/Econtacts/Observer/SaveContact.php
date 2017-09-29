<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;

class SaveContact implements ObserverInterface
{
    /**
     * Object manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Extension helper
     *
     * @var \Me\Econtacts\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    protected $_logger;

    /**
     * SaveContact constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Me\Econtacts\Helper\Data $helper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        \Me\Econtacts\Helper\Data $helper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        $this->_helper = $helper;
        $this->_logger = $logger;
    }

    /**
     * Save contact
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|bool
     * @throws \Me\Econtacts\Observer\Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helper->isEnabled()) {
            return false;
        }

        $controller = $observer->getControllerAction();
        $post = $controller->getRequest()->getPostValue();

        if ($post) {
            try {
                $isNotValid = $this->_helper->checkErrors($post);

                if ($isNotValid) {
                    throw new Exception('Econtacts: form fields are invalid.');
                }

                $data = [
                    'store_id' => $this->_storeManager->getStore()->getId()
                ];

                $data = array_merge($data, $post);
                /**
                 * @var \Magento\Framework\Filter\FilterManager $filterManager
                 */
                $filterManager = $this->_objectManager
                    ->get('Magento\Framework\Filter\FilterManager');
                $data['comment'] = $filterManager->stripTags($data['comment']);

                if (!empty($data)) {
                    $model = $this->_objectManager
                        ->create('Me\Econtacts\Model\Econtacts');
                    $model->addData($data);
                    $model->save();
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->_logger->warning($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->_logger->warning($e->getMessage());
            } catch (\Exception $e) {
                $this->_logger->warning($e->getMessage());
            }
        }

        return $this;
    }
}
