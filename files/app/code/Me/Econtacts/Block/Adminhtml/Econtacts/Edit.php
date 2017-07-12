<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Block\Adminhtml\Econtacts;

/**
 * CMS econtacts edit form container
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'econtacts_id';
        $this->_blockGroup = 'Me_Econtacts';
        $this->_controller = 'adminhtml_econtacts';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Contact'));
        $this->buttonList->update('delete', 'label', __('Delete Contact'));

        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ],
                ]
            ],
            -100
        );

        $this->buttonList->add(
            'send_answer',
            [
                'label' => __('Send Answer'),
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'save',
                            'target' => '#edit_form',
                            'eventData' => [
                                'action' => ['args' => ['send_answer' => '1']]
                            ],
                        ],
                    ],
                ],
                'class' => 'add'
            ],
            -110
        );

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('answer') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'answer');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'answer');
                }
            }
        ";
    }

    /**
     * Get edit form container header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('me_econtact')->getId()) {
            return __(
                "Edit Contact '%1'",
                $this->escapeHtml(
                    $this->_coreRegistry->registry('me_econtact')->getName()
                )
            );
        }
    }
}
