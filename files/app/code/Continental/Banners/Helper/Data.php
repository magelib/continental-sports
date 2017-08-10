<?php
/**
 * @author Continental Team
 * @copyright Copyright (c) 2017 Continental 
 * @package Continental_Banners
 */
namespace Continental\Banners\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    CONST ENABLE      = 'continental_banners/general/enable';
    CONST BLOCK_LABEL = 'continental_banners/general/block_label';
    CONST TEXT_ALIGN  = 'continental_banners/general/text_align';
    CONST BLOCK_TITLE = 'continental_banners/general/block_title';
    CONST BLOCK_TEXT = 'continental_banners/general/block_text';

    public function __construct(
        \Magento\Framework\App\Helper\Context $context
     ){
        parent::__construct($context);
        $this->_scopeConfig = $this->scopeConfig;
    }

    public function getEnable(){
        return $this->_scopeConfig->getValue(self::ENABLE);
    }

    public function getBlockLabel(){
        return $this->_scopeConfig->getValue(self::BLOCK_LABEL);
    }

    public function getTextAlign(){
        return $this->_scopeConfig->getValue(self::TEXT_ALIGN);
    }
 
    public function getBlockText(){
	return 'Bouncy';
        return $this->_scopeConfig->getValue(self::BLOCK_TEXT);
    }

    public function getBlockTitle(){
        return $this->_scopeConfig->getValue(self::BLOCK_TITLE);
    }
}
