<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Controller\Adminhtml\Econtacts;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;

class Save extends \Me\Econtacts\Controller\Adminhtml\Econtacts
{
    /**
     * @var StateInterface
     */
    protected $_inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Me\Econtacts\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * Save constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param \Me\Econtacts\Helper\Data $helper
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        \Me\Econtacts\Helper\Data $helper,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider
    ) {
        parent::__construct($context, $coreRegistry);
        $this->_coreRegistry = $coreRegistry;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_helper = $helper;
        $this->_filterProvider = $filterProvider;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('econtacts_id');
            /** @var \Me\Econtacts\Model\Econtacts $model */
            $model = $this->_objectManager
                ->create('Me\Econtacts\Model\Econtacts')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(
                    __('This contact no longer exists.')
                );
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->_objectManager->get('Magento\Backend\Model\Session')
                    ->setFormData(false);
                $this->messageManager->addSuccess(__('You saved the contact.'));
                if ($this->getRequest()->getParam('send_answer')) {
                    if ($this->sendAnswer($model, $id)) {
                        $this->messageManager->addWarning(
                            __('Email sending error.')
                        );
                    } else {
                        $this->messageManager->addSuccess(
                            __('Your answer email was successfully sent.')
                        );
                    }
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['econtacts_id' => $model->getId()]
                    );
                }

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['econtacts_id' => $model->getId()]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')
                    ->setFormData($data);
                return $resultRedirect->setPath(
                    '*/*/edit',
                    [
                        'econtacts_id' => $this->getRequest()
                            ->getParam('econtacts_id')
                    ]
                );
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Send answer email
     *
     * @param \Me\Econtacts\Model\Econtacts $model
     * @param int $id
     * @return bool
     */
    protected function sendAnswer($model, $id = 0)
    {
        $error = false;
        $this->_inlineTranslation->suspend();

        $model->load($id);
        if (!$model->getId() && $id) {
            $error = true;
        }

        if (!$error) {
            $answer = $this->_filterProvider
                ->getBlockFilter()
                ->setStoreId($model->getStoreId())
                ->filter($model->getAnswer());
            $templateVars = [
                'name' => $model->getName(),
                'email' => $model->getEmail(),
                'telephone' => $model->getTelephone(),
                'comment' => $model->getComment(),
                'answer' => $answer,
                'incl_comment' => $this->_helper->getInclComment(),
                'incl_info' => $this->_helper->getInclInfo()
            ];

            $bcc = [];
            $copyTo = $this->_helper->getBccEmail();
            if ($copyTo && $this->_helper->isBccEnabled()) {
                $bcc = $copyTo;
            }

            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($this->_helper->getEmailTemplate())
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $model->getStoreId(),
                    ]
                )
                ->setTemplateVars($templateVars)
                ->setFrom($this->_helper->getSenderEmail())
                ->setReplyTo($this->_helper->getSenderEmail())
                ->addTo($model->getEmail())
                ->addBcc($bcc)
                ->getTransport();

            $transport->sendMessage();
            $this->_inlineTranslation->resume();
        }

        return $error;
    }
}
