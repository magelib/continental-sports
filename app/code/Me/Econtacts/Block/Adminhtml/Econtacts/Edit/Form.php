<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Block\Adminhtml\Econtacts\Edit;

/**
 * Adminhtml Econtacts edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Me\Econtacts\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore ,
     * @param \Me\Econtacts\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
        \Me\Econtacts\Helper\Data $helper,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_helper = $helper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('econtacts_form');
        $this->setTitle(__('Contact Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('me_econtact');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post'
                ]
            ]
        );

        $form->setHtmlIdPrefix('econtacts_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Contact Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getEcontactsId()) {
            $fieldset->addField(
                'econtacts_id',
                'hidden',
                ['name' => 'econtacts_id']
            );
        }

        $canModify = $this->_helper->isModifyEnabled();
        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => $canModify ? true : false,
                'disabled' => $canModify ? false : true
            ]
        );

        $fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
                'required' => $canModify ? true : false,
                'disabled' => $canModify ? false : true
            ]
        );

        $fieldset->addField(
            'telephone',
            'text',
            [
                'name' => 'telephone',
                'label' => __('Telephone'),
                'title' => __('Telephone'),
                'required' => $canModify ? true : false,
                'disabled' => $canModify ? false : true
            ]
        );

        $fieldset->addField(
            'comment',
            'textarea',
            [
                'name' => 'comment',
                'label' => __('Comment'),
                'title' => __('Comment'),
                'required' => $canModify ? true : false,
                'disabled' => $canModify ? false : true,
                'style' => 'height:20em'
            ]
        );

        /* Check is single store mode */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => $canModify ? true : false,
                    'disabled' => $canModify ? false : true,
                    'values' => $this->_systemStore
                        ->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                [
                    'name' => 'stores[]',
                    'value' => $this->_storeManager->getStore(true)->getId()
                ]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField(
            'answered',
            'select',
            [
                'label' => __('Answered'),
                'title' => __('Answered'),
                'name' => 'answered',
                'required' => true,
                'options' => ['1' => __('Yes'), '0' => __('No')]
            ]
        );

        $fieldset->addField(
            'answer',
            'editor',
            [
                'name' => 'answer',
                'label' => __('Answer'),
                'title' => __('Answer'),
                'style' => 'height:36em',
                'required' => true,
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
