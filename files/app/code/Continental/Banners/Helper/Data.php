<?php
/**
 * @author Attercopia* @copyright Copyright (c) 2017 Continental (http://www.Continental.com)
 * @package Continental_Banners
 */
namespace Continental\Banners\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterfac
     */

    protected $_scopeConfig;

    CONST ENABLE = 'continental_banners/general/enable';
    CONST BLOCK_LABEL = 'continental_banners/general/block_label';
    CONST TEXT_ALIGN = 'continental_banners/general/text_align';

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);

        $this->_scopeConfig = $scopeConfig;
    }

    public function getEnable()
    {
        return $this->_scopeConfig->getValue(self::ENABLE);
    }

    public function getBlockLabel()
    {
        return $this->_scopeConfig->getValue(self::BLOCK_LABEL);
    }

    public function getTextAlign()
    {
        return $this->_scopeConfig->getValue(self::TEXT_ALIGN);
    }
}