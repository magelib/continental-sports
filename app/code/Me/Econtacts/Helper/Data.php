<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Helper;

/**
 * Contact base helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Path to store config if extension is enabled
     *
     * @var string
     */
    const XML_PATH_ENABLED = 'econtacts/basic/enabled';

    /**
     * Path to store config if modify is enabled
     *
     * @var string
     */
    const XML_PATH_MODIFY = 'econtacts/basic/modify';

    /**
     * Path to store config email sender
     *
     * @var string
     */
    const XML_PATH_EMAIL_SENDER = 'econtacts/email/sender_email_identity';

    /**
     * Path to store config if bcc is enabled
     *
     * @var string
     */
    const XML_PATH_BCC_ENABLE = 'econtacts/email/email_bbc_enable';

    /**
     * Path to store config bcc email address
     *
     * @var string
     */
    const XML_PATH_BCC_EMAIL = 'econtacts/email/email_bcc';

    /**
     * Path to store config email template
     *
     * @var string
     */
    const XML_PATH_EMAIL_TEMPLATE = 'econtacts/email/email_template';

    /**
     * Path to store config whether include comment in the answer
     *
     * @var string
     */
    const XML_PATH_EMAIL_INCL_COMMENT = 'econtacts/email/include_comment';

    /**
     * Path to store config include original contact information in the answer
     *
     * @var string
     */
    const XML_PATH_EMAIL_INCL_INFO = 'econtacts/email/include_information';

    /**
     * Data constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Check if extension enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if administrator can modify original contact information
     *
     * @return bool
     */
    public function isModifyEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_MODIFY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get email sender
     *
     * @return string
     */
    public function getSenderEmail()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SENDER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if bcc enabled
     *
     * @return bool
     */
    public function isBccEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_BCC_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get bcc email address
     *
     * @return array|false
     */
    public function getBccEmail()
    {
        $data = $this->scopeConfig->getValue(
            self::XML_PATH_BCC_EMAIL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if (!empty($data)) {
            return explode(',', $data);
        }

        return false;
    }

    /**
     * Get email template
     *
     * @return string
     */
    public function getEmailTemplate()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_TEMPLATE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if include original comment in the answer
     *
     * @return bool
     */
    public function getInclComment()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_EMAIL_INCL_COMMENT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if include original contact information in the answer
     *
     * @return bool
     */
    public function getInclInfo()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_EMAIL_INCL_INFO,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if the captured post is valid
     *
     * @param array $post
     * @return bool
     */
    public function checkErrors($post)
    {
        $error = false;

	if ( empty($post['name']) ) {
		$post['name'] =  $post['firstname'] . ' ' . $post['lastname'];
	}

        if (!\Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
            $error = true;
        }
        if (\Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
            $error = true;
        }

        return $error;
    }
}
